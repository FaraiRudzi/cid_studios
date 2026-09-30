<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->role === 'ADMIN';
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->role === 'ADMIN';
    }

    /** Users are deactivated, never deleted: audit entries and uploads must keep their author. */
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('first_name')
                ->required()
                ->maxLength(100),
            Forms\Components\TextInput::make('surname')
                ->required()
                ->maxLength(100),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('password')
                ->password()
                ->revealable()
                ->required(fn (string $context): bool => $context === 'create')
                ->minLength(12)
                ->maxLength(255)
                ->dehydrateStateUsing(fn (?string $state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn (?string $state): bool => filled($state)),
            Forms\Components\TextInput::make('phone_number')
                ->live()
                ->tel()
                ->rule('regex:/^(?:\+263|00263|0)7[1-8]\d{7}$/')
                ->helperText('Use a Zimbabwe number, for example 0771234567.')
                ->maxLength(50),
            Forms\Components\TextInput::make('force_number')
                ->maxLength(50)
                ->unique(ignoreRecord: true),
            Forms\Components\Select::make('role')
                ->options([
                    'ADMIN' => 'ADMIN',
                    'PHOTOGRAPHER' => 'PHOTOGRAPHER',
                ])
                ->required(),
            Forms\Components\Toggle::make('is_active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('surname')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('force_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('role')->badge()->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')->options([
                    'ADMIN' => 'ADMIN',
                    'PHOTOGRAPHER' => 'PHOTOGRAPHER',
                ]),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                EditAction::make()->visible(fn () => Auth::user()?->role === 'ADMIN'),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->visible(fn () => Auth::user()?->role === 'ADMIN'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
