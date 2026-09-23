<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CaseResource\Pages;
use App\Models\CaseLog;
use App\Models\CaseModel;
use App\Models\Person;
use App\Models\User;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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

    public static function canEdit(Model $record): bool
    {
        return Auth::user()?->role === 'PHOTOGRAPHER' && $record->photographer_id === Auth::id();
    }

    public static function canView(Model $record): bool
    {
        return Auth::user()?->role === 'ADMIN' || (Auth::user()?->role === 'PHOTOGRAPHER' && $record->photographer_id === Auth::id());
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['people', 'media', 'station', 'photographer']);

        if (Auth::user()?->role === 'PHOTOGRAPHER') {
            $query->where('photographer_id', Auth::id());
        }

        return $query;
    }

    // public static function form(Schema $schema): Schema
    // {
    //     return $schema->components([
    //         FormSection::make('Case Details')->schema([
    //             Forms\Components\TextInput::make('scene_reference_number')
    //                 ->label('Scene Reference')
    //                 ->placeholder('Auto-generated')
    //                 ->disabled(),
    //             Forms\Components\TextInput::make('reference_number')
    //                 ->label('Station Reference (CR Number)')
    //                 ->required(),
    //             Forms\Components\Select::make('station_id')
    //                 ->relationship('station', 'name')
    //                 ->searchable()
    //                 ->preload()
    //                 ->required(),
    //             Forms\Components\Select::make('case_type')
    //                 ->options([
    //                     'Sudden Death' => 'Sudden Death',
    //                     'Murder' => 'Murder',
    //                     'ID Parade' => 'ID Parade',
    //                     'Indications' => 'Indications',
    //                     'Other' => 'Other',
    //                 ])
    //                 ->required(),
    //             Forms\Components\Select::make('photographer_id')
    //                 ->label('Assigned Photographer')
    //                 ->options(User::where('role', 'PHOTOGRAPHER')->get()->pluck('name', 'id'))
    //                 ->required()
    //                 ->visible(fn () => Auth::user()?->role === 'ADMIN'),
    //             Forms\Components\Select::make('status')
    //                 ->options(['OPEN' => 'OPEN', 'PENDING_REVIEW' => 'PENDING_REVIEW', 'CLOSED' => 'CLOSED'])
    //                 ->default('OPEN')
    //                 ->required(),
    //         ])->columns(2),

    //         FormSection::make('Brief Circumstances')->schema([
    //             Forms\Components\Textarea::make('circumstances')->rows(4),
    //             Forms\Components\TextInput::make('cause_of_death'),
    //         ])->columns(2),

    //         FormSection::make('People Involved')->schema([
    //             Forms\Components\Repeater::make('people')
    //                 ->label('Case participants')
    //                 ->addActionLabel('Add person')
    //                 ->itemLabel(fn (array $state): ?string => (
    //                     ($state['role'] ?? null)
    //                         ? ucfirst($state['role']).' • '.trim((($state['first_name'] ?? '').' '.($state['surname'] ?? '')))
    //                         : 'New person'
    //                 ))
    //                 ->collapsed()
    //                 ->schema([
    //                     Forms\Components\Select::make('role')
    //                         ->options([
    //                             'informant' => 'Informant',
    //                             'deceased' => 'Deceased',
    //                             'accused' => 'Accused',
    //                             'complainant' => 'Complainant',
    //                             'witness' => 'Witness',
    //                             'other' => 'Other',
    //                         ])
    //                         ->required()
    //                         ->placeholder('Select role'),
    //                     Forms\Components\TextInput::make('first_name')->required(),
    //                     Forms\Components\TextInput::make('surname')->required(),
    //                     Forms\Components\TextInput::make('id_number')
    //                         ->label('ID Number')
    //                         ->live()
    //                         ->inputMode('text')
    //                         ->rule('regex:/^\d{2}-\d{6,7}[A-Z]\d{2}$/i')
    //                         ->helperText('Format: 18-118656Q18'),
    //                     Forms\Components\Select::make('gender')
    //                         ->options(['MALE' => 'MALE', 'FEMALE' => 'FEMALE', 'UNKNOWN' => 'UNKNOWN'])
    //                         ->placeholder('Select gender'),
    //                     Forms\Components\TextInput::make('phone_number')
    //                         ->live()
    //                         ->tel()
    //                         ->inputMode('tel')
    //                         ->rule('regex:/^(?:\+263|00263|0)(?:7[1-8]|7[7-9])\d{7}$/'),
    //                     Forms\Components\Textarea::make('address')->rows(2)->columnSpanFull(),
    //                     Forms\Components\Textarea::make('notes')->columnSpanFull(),
    //                 ])->columns(3)->defaultItems(0),
    //         ]),

    //         Section::make('Case Media')
    //             ->visible(fn (?CaseModel $record = null) => Auth::user()?->role === 'PHOTOGRAPHER' || ($record !== null && Auth::user()?->role === 'ADMIN'))
    //             ->schema([
    //                 Forms\Components\Repeater::make('media')
    //                     ->addable(fn () => Auth::user()?->role === 'PHOTOGRAPHER')
    //                     ->deletable(fn () => Auth::user()?->role === 'PHOTOGRAPHER')
    //                     ->reorderable(fn () => Auth::user()?->role === 'PHOTOGRAPHER')
    //                     ->disabled(fn () => Auth::user()?->role === 'ADMIN')
    //                     ->schema([
    //                         Forms\Components\TextInput::make('title')
    //                             ->required()
    //                             ->placeholder('e.g. Scene photos')
    //                             ->disabled(fn () => Auth::user()?->role === 'ADMIN'),
    //                         Forms\Components\FileUpload::make('file_path')
    //                             ->disk('public')
    //                             ->directory('case-media')
    //                             ->acceptedFileTypes(['image/*', 'video/*'])
    //                             ->multiple()
    //                             ->maxSize(524288000)
    //                             ->maxFiles(50)
    //                             ->maxParallelUploads(10)
    //                             ->previewable(true)
    //                             ->removeUploadedFileButtonPosition('center bottom')
    //                             ->deletable(fn () => Auth::user()?->role === 'PHOTOGRAPHER')
    //                             ->disabled(fn () => Auth::user()?->role === 'ADMIN'),
    //                         Forms\Components\Hidden::make('uploaded_by')->default(Auth::id()),
    //                     ]),
    //             ]),
    //     ])->columns(2);
    // }

    public static function form(Schema $schema): Schema 
{ 
    return $schema->components([ 
        Forms\Components\TextInput::make('scene_reference_number') 
            ->label('Scene Reference') 
            ->placeholder('Auto-generated') 
            ->disabled(), 

        Forms\Components\TextInput::make('reference_number') 
            ->label('Station Reference (CR Number)') 
            ->required(), 

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
            ->options(User::where('role', 'PHOTOGRAPHER')->get()->pluck('name', 'id')) 
            ->required() 
            ->visible(fn () => Auth::user()?->role === 'ADMIN'), 

        Forms\Components\Select::make('status') 
            ->options([
                'OPEN' => 'OPEN', 
                'PENDING_REVIEW' => 'PENDING_REVIEW', 
                'CLOSED' => 'CLOSED'
            ]) 
            ->default('OPEN') 
            ->required(), 

        Forms\Components\Textarea::make('circumstances')
            ->rows(4), 

        Forms\Components\TextInput::make('cause_of_death'), 

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
                        'UNKNOWN' => 'UNKNOWN'
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

        Forms\Components\Repeater::make('media') 
            ->visible(fn (?CaseModel $record = null) => Auth::user()?->role === 'PHOTOGRAPHER' || ($record !== null && Auth::user()?->role === 'ADMIN'))
            ->addable(fn () => Auth::user()?->role === 'PHOTOGRAPHER') 
            ->deletable(fn () => Auth::user()?->role === 'PHOTOGRAPHER') 
            ->reorderable(fn () => Auth::user()?->role === 'PHOTOGRAPHER') 
            ->disabled(fn () => Auth::user()?->role === 'ADMIN') 
            ->schema([ 
                Forms\Components\TextInput::make('title') 
                    ->required() 
                    ->placeholder('e.g. Scene photos') 
                    ->disabled(fn () => Auth::user()?->role === 'ADMIN'), 

                Forms\Components\FileUpload::make('file_path') 
                    ->disk('public') 
                    ->directory('case-media') 
                    ->acceptedFileTypes(['image/*', 'video/*']) 
                    ->multiple() 
                    ->maxSize(524288000) 
                    ->maxFiles(50) 
                    ->maxParallelUploads(10) 
                    ->previewable(true) 
                    ->removeUploadedFileButtonPosition('center bottom') 
                    ->deletable(fn () => Auth::user()?->role === 'PHOTOGRAPHER') 
                    ->disabled(fn () => Auth::user()?->role === 'ADMIN'), 

                Forms\Components\Hidden::make('uploaded_by')->default(Auth::id()), 
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
                            ->state(fn (CaseModel $record) => $record->media()->get())
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
                ViewAction::make()->visible(fn (CaseModel $record) => Auth::user()?->role === 'ADMIN' || $record->photographer_id === Auth::id()),
                EditAction::make()->visible(fn (CaseModel $record) => Auth::user()?->role === 'PHOTOGRAPHER' && $record->photographer_id === Auth::id()),
            ]);
    }

    public static function syncCasePeople(CaseModel $case, array $people): void
    {
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

            $person = Person::query()
                ->firstOrNew([
                    'first_name' => $firstName,
                    'surname' => $surname,
                    'id_number' => $personData['id_number'] ?? null,
                    'phone_number' => $personData['phone_number'] ?? null,
                    'address' => $personData['address'] ?? null,
                ]);

            if (! $person->exists) {
                $person->fill([
                    'first_name' => $firstName,
                    'surname' => $surname,
                    'id_number' => $personData['id_number'] ?? null,
                    'gender' => $personData['gender'] ?? 'UNKNOWN',
                    'email' => $personData['email'] ?? null,
                    'phone_number' => $personData['phone_number'] ?? null,
                    'address' => $personData['address'] ?? null,
                ]);
                $person->save();
            } else {
                $person->fill([
                    'email' => $personData['email'] ?? $person->email,
                    'phone_number' => $personData['phone_number'] ?? $person->phone_number,
                    'address' => $personData['address'] ?? $person->address,
                    'gender' => $personData['gender'] ?? $person->gender,
                ]);
                $person->save();
            }

            $payload[$person->getKey()] = [
                'role' => strtolower((string) ($personData['role'] ?? 'other')),
                'notes' => $personData['notes'] ?? null,
            ];
        }

        $case->people()->sync($payload);
    }

    public static function syncCaseMedia(CaseModel $case, array $media): void
    {
        $case->media()->delete();

        foreach ($media as $mediaItem) {
            if (! is_array($mediaItem)) {
                continue;
            }

            $filePaths = $mediaItem['file_path'] ?? [];
            $normalized = is_array($filePaths) ? $filePaths : [$filePaths];
            $normalized = array_values(array_filter(array_map(function ($path) {
                if (! is_string($path)) {
                    return null;
                }

                $trimmed = trim($path);

                return $trimmed !== '' ? $trimmed : null;
            }, $normalized), fn ($path) => $path !== null));

            if (empty($normalized)) {
                continue;
            }

            $mediaRecord = $case->media()->create([
                'uploaded_by' => $mediaItem['uploaded_by'] ?? Auth::id(),
                'title' => $mediaItem['title'] ?? 'Untitled media',
                'category' => $mediaItem['category'] ?? 'General Scene',
                'file_path' => $normalized,
                'file_type' => $mediaItem['file_type'] ?? null,
                'file_size' => $mediaItem['file_size'] ?? null,
                'description' => $mediaItem['description'] ?? null,
            ]);

            $uploader = $mediaRecord->uploader;

            CaseLog::create([
                'case_id' => $case->getKey(),
                'user_id' => $mediaRecord->uploaded_by,
                'action' => 'MEDIA_UPLOADED',
                'role' => $uploader?->role ?? 'SYSTEM',
                'description' => "Media uploaded by photographer: {$uploader?->name}.",
                'metadata' => [
                    'media_id' => $mediaRecord->getKey(),
                    'title' => $mediaRecord->title,
                    'file_paths' => $mediaRecord->getFilePaths(),
                ],
                'ip_address' => request()->ip(),
            ]);
        }
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
