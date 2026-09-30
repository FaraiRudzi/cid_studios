<?php

namespace App\Filament\Resources\CaseResource\Pages;

use App\Exceptions\EvidenceProtectionException;
use App\Filament\Resources\CaseResource;
use App\Models\CaseLog;
use App\Models\CaseModel;
use App\Models\Media;
use App\Models\User;
use App\Services\EvidenceStorage;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ViewCase extends ViewRecord
{
    protected static string $resource = CaseResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->record->loadMissing(['people', 'media', 'station', 'photographer']);
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
                ->visible(fn (CaseModel $record) => CaseResource::canEdit($record)),

            Action::make('reassign')
                ->label('Reassign')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn () => Auth::user()?->isAdmin())
                ->form([
                    Forms\Components\Select::make('photographer_id')
                        ->label('Select Photographer')
                        ->options(fn (): array => User::query()
                            ->where('role', 'PHOTOGRAPHER')
                            ->where('is_active', true)
                            ->get()
                            ->pluck('name', 'id')
                            ->all())
                        ->required(),
                    Forms\Components\Textarea::make('reason')
                        ->label('Reason for reassignment')
                        ->required()
                        ->maxLength(1000),
                ])
                ->action(function (CaseModel $record, array $data): void {
                    $this->applyAdminChange(
                        $record,
                        ['photographer_id' => (int) $data['photographer_id']],
                        (string) $data['reason'],
                        'Case reassigned',
                    );
                }),

            Action::make('change_status')
                ->label('Change status')
                ->icon('heroicon-o-flag')
                ->color('gray')
                ->visible(fn () => Auth::user()?->isAdmin())
                ->form([
                    Forms\Components\Select::make('status')
                        ->options([
                            'OPEN' => 'OPEN',
                            'PENDING_REVIEW' => 'PENDING_REVIEW',
                            'CLOSED' => 'CLOSED',
                            'ARCHIVED' => 'ARCHIVED',
                        ])
                        ->required(),
                    Forms\Components\Textarea::make('reason')
                        ->label('Reason')
                        ->required()
                        ->maxLength(1000),
                ])
                ->action(function (CaseModel $record, array $data): void {
                    $this->applyAdminChange(
                        $record,
                        ['status' => (string) $data['status']],
                        (string) $data['reason'],
                        'Status updated',
                    );
                }),

            Action::make('remove_media')
                ->label('Remove media')
                ->icon('heroicon-o-eye-slash')
                ->color('danger')
                ->visible(fn (CaseModel $record) => Auth::user()?->isAdmin()
                    && $record->media()->whereNull('removed_at')->exists())
                ->modalDescription('The record and the file are retained for the audit trail. The item is hidden from the case and cannot be used in exhibits.')
                ->form([
                    Forms\Components\Select::make('media_id')
                        ->label('Media item')
                        ->options(fn (CaseModel $record): array => $record->media()
                            ->whereNull('removed_at')
                            ->orderBy('id')
                            ->get()
                            ->mapWithKeys(fn (Media $media) => [
                                $media->getKey() => '#'.$media->getKey().' - '.$media->title.' ('.count($media->getFilePaths()).' file(s))',
                            ])
                            ->all())
                        ->required(),
                    Forms\Components\Textarea::make('reason')
                        ->label('Reason for removal')
                        ->required()
                        ->maxLength(1000),
                ])
                ->action(function (CaseModel $record, array $data): void {
                    abort_unless(Auth::user()?->isAdmin(), 403);

                    $media = $record->media()->whereNull('removed_at')->findOrFail($data['media_id']);

                    DB::transaction(function () use ($record, $media, $data): void {
                        $media->markRemoved((int) Auth::id(), (string) $data['reason']);

                        CaseLog::create([
                            'case_id' => $record->getKey(),
                            'user_id' => Auth::id(),
                            'action' => 'MEDIA_REMOVED',
                            'role' => Auth::user()->role,
                            'description' => "Media #{$media->getKey()} \"{$media->title}\" removed from the case. Files are retained.",
                            'metadata' => [
                                'media_id' => $media->getKey(),
                                'reason' => (string) $data['reason'],
                                'files' => collect($media->getFilePaths())
                                    ->map(fn (string $path) => ['path' => $path, 'sha256' => $media->hashFor($path)])
                                    ->all(),
                            ],
                            'ip_address' => request()->ip(),
                        ]);
                    });

                    Notification::make()->title('Media removed from the case')->success()->send();
                }),

            Action::make('create_exhibit')
                ->label('Create Exhibit')
                ->icon('heroicon-o-building-library')
                ->color('primary')
                ->visible(fn () => Auth::user()?->isAdmin())
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

    /** Reassign / status change: admin only, reason mandatory, written to the audit log by CaseObserver. */
    protected function applyAdminChange(CaseModel $record, array $attributes, string $reason, string $successTitle): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);

        try {
            $record->auditReason = $reason;
            $record->update($attributes);
        } catch (EvidenceProtectionException $e) {
            Notification::make()->title('Not changed')->body($e->getMessage())->danger()->send();

            return;
        }

        $this->record->refresh();

        Notification::make()->title($successTitle)->success()->send();
    }

    public static function generateExhibitPdf(CaseModel $record, array $selectedPhotos)
    {
        $exhibits = self::getExhibitData($record, $selectedPhotos);
        self::logExhibit($record, $exhibits, 'EXHIBIT_GENERATED');

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

    /** Every time evidence is put into an exhibit, record which files and whether their hashes still match. */
    public static function logExhibit(CaseModel $record, array $exhibits, string $action): void
    {
        CaseLog::create([
            'case_id' => $record->getKey(),
            'user_id' => Auth::id(),
            'action' => $action,
            'role' => Auth::user()?->role ?? 'SYSTEM',
            'description' => count($exhibits).' photograph(s) included in exhibit.',
            'metadata' => [
                'files' => array_map(fn (array $exhibit) => [
                    'media_id' => $exhibit['media']->getKey(),
                    'path' => $exhibit['path'],
                    'sha256' => $exhibit['hash'],
                    'matches_upload_hash' => $exhibit['verified'],
                ], $exhibits),
            ],
            'ip_address' => request()->ip(),
        ]);
    }

    protected function getExhibitPhotoOptions(CaseModel $record): array
    {
        $options = [];
        $disk = EvidenceStorage::disk();

        foreach ($record->media()->whereNull('removed_at')->orderBy('id')->get() as $media) {
            foreach ($media->getFilePaths() as $index => $path) {
                if (! EvidenceStorage::isAcceptablePath($path) || ! $disk->exists($path)) {
                    continue;
                }

                if (! str_starts_with((string) ($disk->mimeType($path) ?: ''), 'image/')) {
                    continue;
                }

                $title = e(self::validUtf8($media->title));
                $filename = e(self::validUtf8(basename($path)));
                $imageUrl = e(EvidenceStorage::urlFor($media, $index));

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
        $disk = EvidenceStorage::disk();

        foreach ($record->media as $media) {
            if ($media->isRemoved()) {
                continue;
            }

            foreach ($media->getFilePaths() as $index => $path) {
                if (! isset($selectedPhotos[$media->getKey().':'.$index])) {
                    continue;
                }

                if (! EvidenceStorage::isAcceptablePath($path) || ! $disk->exists($path)) {
                    continue;
                }

                $mime = $disk->mimeType($path) ?: 'application/octet-stream';
                if (! str_starts_with($mime, 'image/')) {
                    continue;
                }

                $absolutePath = $disk->path($path);
                $hash = hash_file('sha256', $absolutePath);
                $recorded = $media->hashFor($path);

                $exhibits[] = [
                    'media' => $media,
                    'path' => $path,
                    'image' => 'data:'.$mime.';base64,'.base64_encode(file_get_contents($absolutePath)),
                    'hash' => $hash,
                    'recorded_hash' => $recorded,
                    // true = matches the hash recorded at upload, false = file has changed, null = no hash on record
                    'verified' => $recorded === null ? null : hash_equals($recorded, $hash),
                    'qr_code' => $qrCode,
                ];
            }
        }

        return $exhibits;
    }
}
