<?php

namespace App\Filament\Resources\CaseResource\Pages;

use App\Filament\Resources\CaseResource;
use App\Models\CaseLog;
use App\Models\CaseModel;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ViewCase extends ViewRecord
{
    protected static string $resource = CaseResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->record->loadMissing(['people', 'media', 'station', 'photographer']);
        $this->ensureMediaAuditLogs();
    }

    protected function ensureMediaAuditLogs(): void
    {
        foreach ($this->record->media as $media) {
            $alreadyLogged = $this->record->logs()
                ->where('action', 'MEDIA_UPLOADED')
                ->where('metadata->media_id', $media->getKey())
                ->exists();

            if ($alreadyLogged) {
                continue;
            }

            $uploader = $media->uploader;

            CaseLog::create([
                'case_id' => $this->record->getKey(),
                'user_id' => $media->uploaded_by,
                'action' => 'MEDIA_UPLOADED',
                'role' => $uploader?->role ?? 'SYSTEM',
                'description' => "Media uploaded by photographer: {$uploader?->name}.",
                'metadata' => [
                    'media_id' => $media->getKey(),
                    'title' => $media->title,
                    'file_paths' => $media->getFilePaths(),
                ],
                'ip_address' => null,
                'created_at' => $media->created_at,
            ]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back_to_cases')
                ->label('Back to Cases')
                ->icon('heroicon-o-arrow-left')
                ->url(CaseResource::getUrl('index'))
                ->color('gray'),
            EditAction::make()
                ->visible(fn (CaseModel $record) => Auth::user()?->role === 'PHOTOGRAPHER' && $record->photographer_id === Auth::id()),

            Action::make('reassign')
                ->label('Reassign')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn () => Auth::user()?->role === 'ADMIN')
                ->form([
                    Forms\Components\Select::make('photographer_id')
                        ->label('Select Photographer')
                        ->options(User::where('role', 'PHOTOGRAPHER')->get()->pluck('name', 'id'))
                        ->required(),
                ])
                ->action(function (CaseModel $record, array $data): void {
                    $record->update(['photographer_id' => $data['photographer_id']]);
                }),

            Action::make('create_exhibit')
                ->label('Create Exhibit')
                ->icon('heroicon-o-building-library')
                ->color('primary')
                ->visible(fn () => Auth::user()?->role === 'ADMIN')
                ->form([
                    Forms\Components\CheckboxList::make('photos')
                        ->label('Select photographs for the exhibit')
                        ->options(fn (CaseModel $record): array => $this->getExhibitPhotoOptions($record))
                        ->allowHtml()
                        ->searchable()
                        ->required()
                        ->columns(2),
                ])
                ->action(function (CaseModel $record, array $data) {
                    return redirect()->route('cases.exhibit.preview', [
                        'case' => $record->getKey(),
                        'photos' => implode(',', array_map('strval', $data['photos'] ?? [])),
                    ]);
                }),

            Action::make('view_logs')
                ->label('Logs')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('gray')
                ->modalHeading(fn (CaseModel $record) => "Case Audit Trail - {$record->scene_reference_number}")
                ->modalContent(fn (CaseModel $record) => view('filament.pages.case-logs-modal', ['logs' => $record->logs()->with('user')->get()])),
        ];
    }

    public static function generateExhibitPdf(CaseModel $record, array $selectedPhotos)
    {
        $exhibits = self::getExhibitData($record, $selectedPhotos);

        return Pdf::loadView('filament.pages.case-exhibit-report', [
            'case' => $record,
            'exhibits' => $exhibits,
        ])->setPaper('a4', 'portrait')->stream('Exhibit_'.Str::slug($record->scene_reference_number).'.pdf');
    }

    public static function getExhibitData(CaseModel $record, array $selectedPhotos): array
    {
        $record->load(['media.uploader', 'station', 'photographer']);

        return self::buildExhibits($record, $selectedPhotos);
    }

    protected function getExhibitPhotoOptions(CaseModel $record): array
    {
        $options = [];

        foreach ($record->media as $media) {
            foreach ($media->getFilePaths() as $index => $path) {
                $absolutePath = Storage::disk('public')->path($path);

                if (! is_file($absolutePath) || ! str_starts_with((string) (mime_content_type($absolutePath) ?: ''), 'image/')) {
                    continue;
                }

                $title = e(self::validUtf8($media->title));
                $filename = e(self::validUtf8(basename($path)));
                $imageUrl = e(asset('storage/'.ltrim($path, '/')));

                $options[$media->getKey().':'.$index] = '<span style="align-items:center;display:flex;gap:0.75rem;"><img src="'.$imageUrl.'" alt="" style="background:#f3f4f6;border-radius:0.375rem;height:4.5rem;object-fit:contain;width:6rem;"><span>'.$title.' - '.$filename.'</span></span>';
            }
        }

        return $options;
    }

    protected static function validUtf8(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return iconv('UTF-8', 'UTF-8//IGNORE', $value) ?: '';
    }

    protected static function buildExhibits(CaseModel $record, array $selectedPhotos): array
    {
        $selectedPhotos = array_fill_keys(array_map('strval', $selectedPhotos), true);
        $qrData = implode("\n", [
            $record->scene_reference_number,
            $record->photographer?->name ?? 'Not recorded',
            $record->photographer?->phone_number ?? 'Not recorded',
        ]);
        $qrCode = (new QRCode(new QROptions([
            'outputType' => QROutputInterface::MARKUP_SVG,
            'outputBase64' => true,
            'quietzoneSize' => 2,
        ])))->render($qrData);
        $exhibits = [];

        foreach ($record->media as $media) {
            foreach ($media->getFilePaths() as $index => $path) {
                if (! isset($selectedPhotos[$media->getKey().':'.$index])) {
                    continue;
                }

                $absolutePath = Storage::disk('public')->path($path);
                if (! is_file($absolutePath)) {
                    continue;
                }

                $mime = mime_content_type($absolutePath) ?: 'application/octet-stream';
                if (! str_starts_with($mime, 'image/')) {
                    continue;
                }

                $exhibits[] = [
                    'media' => $media,
                    'path' => $path,
                    'image' => 'data:'.$mime.';base64,'.base64_encode(file_get_contents($absolutePath)),
                    'hash' => hash_file('sha256', $absolutePath),
                    'qr_code' => $qrCode,
                ];
            }
        }

        return $exhibits;
    }
}
