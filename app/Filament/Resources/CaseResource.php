<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CaseResource\Pages;
use App\Models\CaseLog;
use App\Models\CaseModel;
use App\Models\Media;
use App\Models\Person;
use App\Models\User;
use App\Services\EvidenceStorage;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CaseResource extends Resource
{
    protected static ?string $model = CaseModel::class;

    protected static ?string $navigationLabel = 'Cases';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-folder-open';

    public static function getModelLabel(): string
    {
        return 'Case';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Cases';
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()?->role, ['ADMIN', 'PHOTOGRAPHER'], true);
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->role === 'ADMIN';
    }

    public static function canView(Model $record): bool
    {
        return Auth::user()?->canAccessCase($record) ?? false;
    }

    /** Only the assigned photographer, and only while the case is not closed or archived. */
    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();

        return $user !== null
            && $user->isPhotographer()
            && $user->canAccessCase($record)
            && ! $record->isLocked();
    }

    /** Cases are never deleted; they are closed or archived. */
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['people', 'media', 'station', 'photographer']);

        if (Auth::user()?->role === 'PHOTOGRAPHER') {
            $query->where('photographer_id', Auth::id());
        }

        return $query;
    }

    /** Statuses selectable in the form. Closing/archiving is an admin action on the case page. */
    protected static function statusOptions(): array
    {
        return array_combine(CaseModel::PHOTOGRAPHER_STATUSES, CaseModel::PHOTOGRAPHER_STATUSES);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('scene_reference_number')
                ->label('Scene Reference')
                ->placeholder('Auto-generated')
                ->disabled()
                ->dehydrated(false),

            Forms\Components\TextInput::make('reference_number')
                ->label('Station Reference (CR Number)')
                ->required()
                ->maxLength(100),

            Forms\Components\Select::make('station_id')
                ->relationship('station', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Select::make('case_type')
                ->options([
                    'Sudden Death' => 'Sudden Death',
                    'Murder' => 'Murder',
                    'ID Parade' => 'ID Parade',
                    'Indications' => 'Indications',
                    'Other' => 'Other',
                ])
                ->required(),

            Forms\Components\Select::make('photographer_id')
                ->label('Assigned Photographer')
                ->options(fn (): array => User::query()
                    ->where('role', 'PHOTOGRAPHER')
                    ->where('is_active', true)
                    ->get()
                    ->pluck('name', 'id')
                    ->all())
                ->required()
                ->visible(fn () => Auth::user()?->role === 'ADMIN'),

            Forms\Components\Select::make('status')
                ->options(fn (): array => static::statusOptions())
                ->default('OPEN')
                ->required(),

            Forms\Components\Textarea::make('circumstances')
                ->rows(4),

            Forms\Components\TextInput::make('cause_of_death')
                ->maxLength(255),

            Forms\Components\Repeater::make('people')
                ->label('Case participants')
                ->addActionLabel('Add person')
                ->itemLabel(fn (array $state): ?string => (
                    ($state['role'] ?? null)
                        ? ucfirst($state['role']).' • '.trim((($state['first_name'] ?? '').' '.($state['surname'] ?? '')))
                        : 'New person'
                ))
                ->collapsed()
                ->schema([
                    Forms\Components\Select::make('role')
                        ->options([
                            'informant' => 'Informant',
                            'deceased' => 'Deceased',
                            'accused' => 'Accused',
                            'complainant' => 'Complainant',
                            'witness' => 'Witness',
                            'other' => 'Other',
                        ])
                        ->required()
                        ->placeholder('Select role'),

                    Forms\Components\TextInput::make('first_name')->required(),

                    Forms\Components\TextInput::make('surname')->required(),

                    Forms\Components\TextInput::make('id_number')
                        ->label('ID Number')
                        ->live()
                        ->inputMode('text')
                        ->rule('regex:/^\d{2}-\d{6,7}[A-Z]\d{2}$/i')
                        ->helperText('Format: 18-118656Q18'),

                    Forms\Components\Select::make('gender')
                        ->options([
                            'MALE' => 'MALE',
                            'FEMALE' => 'FEMALE',
                            'UNKNOWN' => 'UNKNOWN',
                        ])
                        ->placeholder('Select gender'),

                    Forms\Components\TextInput::make('phone_number')
                        ->live()
                        ->tel()
                        ->inputMode('tel')
                        ->rule('regex:/^(?:\+263|00263|0)(?:7[1-8]|7[7-9])\d{7}$/'),

                    Forms\Components\Textarea::make('address')->rows(2),

                    Forms\Components\Textarea::make('notes'),
                ])->defaultItems(0),

            // Evidence is append-only. This repeater only ever ADDS new items; media that is
            // already saved is shown on the case page and cannot be edited or removed here.
            Forms\Components\Repeater::make('media')
                ->label('Add new media (saved media is permanent and is shown on the case page)')
                ->visible(fn () => Auth::user()?->role === 'PHOTOGRAPHER')
                ->addActionLabel('Add media')
                ->reorderable(false)
                ->defaultItems(0)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g. Scene photos'),

                    Forms\Components\FileUpload::make('file_path')
                        ->label('Files')
                        ->disk(EvidenceStorage::DISK)
                        ->directory(EvidenceStorage::DIRECTORY)
                        ->visibility('private')
                        ->acceptedFileTypes(EvidenceStorage::ACCEPTED_TYPES)
                        ->multiple()
                        ->required()
                        ->maxSize(512000) // kilobytes: 500 MB per file
                        ->maxFiles(50)
                        ->maxParallelUploads(10)
                        ->previewable(true)
                        ->removeUploadedFileButtonPosition('center bottom'),
                ]),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Case Overview')
                    ->schema([
                        TextEntry::make('scene_reference_number')->label('Scene Reference'),
                        TextEntry::make('reference_number')->label('CR Number'),
                        TextEntry::make('station.name')->label('Station'),
                        TextEntry::make('case_type'),
                        TextEntry::make('photographer.name')->label('Photographer'),
                        TextEntry::make('status')->badge(),
                    ])
                    ->columns(3),

                Section::make('Circumstances')
                    ->schema([
                        TextEntry::make('circumstances')->columnSpanFull()->placeholder('No circumstances recorded.'),
                        TextEntry::make('cause_of_death')->label('Cause of death'),
                    ])
                    ->columns(2),

                Section::make('Persons Involved')
                    ->schema([
                        ViewEntry::make('people')
                            ->label('')
                            ->state(fn (CaseModel $record) => $record->people()->get())
                            ->view('filament.case-people-grid')
                            ->columnSpanFull(),
                    ]),

                Section::make('Case Media')
                    ->schema([
                        ViewEntry::make('media')
                            ->label('')
                            ->state(fn (CaseModel $record) => $record->media()->orderBy('id')->get())
                            ->view('filament.case-media-grid')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('scene_reference_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('reference_number')->label('CR Number')->searchable(),
                Tables\Columns\TextColumn::make('case_type')->badge(),
                Tables\Columns\TextColumn::make('station.name')->searchable(),
                Tables\Columns\TextColumn::make('photographer.name')->label('Photographer'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->actions([
                ViewAction::make()->visible(fn (CaseModel $record) => static::canView($record)),
                EditAction::make()->visible(fn (CaseModel $record) => static::canEdit($record)),
            ]);
    }

    /**
     * Links people to a case. Person rows are shared between cases, so a Person row is
     * never modified from here: identical details reuse the row, any difference creates
     * a new one. Adding, removing and changing participants is written to the audit log.
     */
    public static function syncCasePeople(CaseModel $case, array $people): void
    {
        $before = $case->people()->get()
            ->mapWithKeys(fn (Person $person) => [$person->getKey() => [
                'role' => (string) $person->pivot->role,
                'notes' => $person->pivot->notes,
            ]])
            ->all();

        $validRoles = ['informant', 'deceased', 'accused', 'complainant', 'witness', 'other'];
        $payload = [];

        foreach ($people as $personData) {
            if (! is_array($personData)) {
                continue;
            }

            $firstName = trim((string) ($personData['first_name'] ?? ''));
            $surname = trim((string) ($personData['surname'] ?? ''));

            if ($firstName === '' && $surname === '') {
                continue;
            }

            $person = Person::query()->firstOrCreate([
                'first_name' => $firstName,
                'surname' => $surname,
                'id_number' => static::blankToNull($personData['id_number'] ?? null),
                'gender' => static::blankToNull($personData['gender'] ?? null) ?? 'UNKNOWN',
                'email' => static::blankToNull($personData['email'] ?? null),
                'phone_number' => static::blankToNull($personData['phone_number'] ?? null),
                'address' => static::blankToNull($personData['address'] ?? null),
            ]);

            $role = strtolower((string) ($personData['role'] ?? 'other'));

            $payload[$person->getKey()] = [
                'role' => in_array($role, $validRoles, true) ? $role : 'other',
                'notes' => static::blankToNull($personData['notes'] ?? null),
            ];
        }

        $case->people()->sync($payload);

        $after = $payload;
        $added = array_diff_key($after, $before);
        $removed = array_diff_key($before, $after);
        $changed = array_filter(
            array_intersect_key($after, $before),
            fn (array $row, int|string $id) => $row !== $before[$id],
            ARRAY_FILTER_USE_BOTH,
        );

        if ($added === [] && $removed === [] && $changed === []) {
            return;
        }

        // Person IDs and roles only: identities stay in the people table, not in the immutable log.
        CaseLog::create([
            'case_id' => $case->getKey(),
            'user_id' => Auth::id(),
            'action' => 'PEOPLE_UPDATED',
            'role' => Auth::user()?->role ?? 'SYSTEM',
            'description' => sprintf(
                'Case participants updated: %d added, %d removed, %d changed.',
                count($added), count($removed), count($changed),
            ),
            'metadata' => [
                'added' => array_map(fn (array $row) => ['role' => $row['role']], $added),
                'removed' => array_map(fn (array $row) => ['role' => $row['role']], $removed),
                'changed' => array_map(fn (array $row, $id) => [
                    'from_role' => $before[$id]['role'],
                    'to_role' => $row['role'],
                    'notes_changed' => $row['notes'] !== $before[$id]['notes'],
                ], $changed, array_keys($changed)),
            ],
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Append-only. Adds NEW files to the case, hashes them, and logs each upload.
     * It never deletes or rewrites existing media, and it ignores anything that is not
     * an existing file inside the evidence store that no case has already claimed.
     */
    public static function syncCaseMedia(CaseModel $case, array $media): void
    {
        if ($media === []) {
            return;
        }

        $user = Auth::user();

        if ($user === null || ! static::canEdit($case)) {
            throw new AuthorizationException('You cannot add media to this case.');
        }

        $disk = EvidenceStorage::disk();
        $known = $case->media()->get()->flatMap(fn (Media $existing) => $existing->getFilePaths())->all();

        foreach ($media as $item) {
            if (! is_array($item)) {
                continue;
            }

            $paths = collect(Arr::flatten(Arr::wrap($item['file_path'] ?? [])))
                ->map(fn ($path) => is_string($path) ? trim($path) : null)
                ->filter(fn ($path) => EvidenceStorage::isAcceptablePath($path))
                ->unique()
                ->reject(fn (string $path) => in_array($path, $known, true)
                    || ! $disk->exists($path)
                    || static::isClaimedByAnyCase($path))
                ->values();

            if ($paths->isEmpty()) {
                continue;
            }

            $hashes = [];
            $size = 0;
            $mimes = [];

            foreach ($paths as $path) {
                $hashes[$path] = EvidenceStorage::sha256($path);
                $size += (int) $disk->size($path);
                $mimes[] = $disk->mimeType($path) ?: 'application/octet-stream';
            }

            $mimes = array_values(array_unique($mimes));
            $title = mb_substr(static::blankToNull($item['title'] ?? null) ?? 'Untitled media', 0, 255);

            $record = $case->media()->create([
                'uploaded_by' => $user->getKey(), // always the authenticated user, never a form value
                'title' => $title,
                'category' => 'General Scene',
                'file_path' => $paths->all(),
                'file_hashes' => $hashes,
                'file_type' => count($mimes) === 1 ? $mimes[0] : 'mixed',
                'file_size' => $size,
            ]);

            $known = array_merge($known, $paths->all());

            CaseLog::create([
                'case_id' => $case->getKey(),
                'user_id' => $user->getKey(),
                'action' => 'MEDIA_UPLOADED',
                'role' => $user->role,
                'description' => sprintf(
                    'Media uploaded by %s: %s (%d file%s).',
                    $user->name, $record->title, $paths->count(), $paths->count() === 1 ? '' : 's',
                ),
                'metadata' => [
                    'media_id' => $record->getKey(),
                    'title' => $record->title,
                    'files' => collect($hashes)
                        ->map(fn ($hash, $path) => ['path' => $path, 'sha256' => $hash])
                        ->values()
                        ->all(),
                ],
                'ip_address' => request()->ip(),
            ]);
        }
    }

    protected static function isClaimedByAnyCase(string $path): bool
    {
        // LIKE narrows the candidates; the exact comparison is done in PHP.
        return Media::query()
            ->where('file_path', 'like', '%'.basename($path).'%')
            ->get(['id', 'file_path'])
            ->contains(fn (Media $candidate) => in_array($path, $candidate->getFilePaths(), true));
    }

    protected static function blankToNull(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCases::route('/'),
            'create' => Pages\CreateCase::route('/create'),
            'view' => Pages\ViewCase::route('/{record}'),
            'edit' => Pages\EditCase::route('/{record}/edit'),
        ];
    }
}
