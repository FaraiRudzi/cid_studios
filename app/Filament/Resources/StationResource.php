<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StationResource\Pages\CreateStation;
use App\Filament\Resources\StationResource\Pages\EditStation;
use App\Filament\Resources\StationResource\Pages\ListStations;
use App\Models\Station;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class StationResource extends Resource
{
    protected static ?string $model = Station::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-map-pin';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('province')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('cases_count')
                    ->label('Cases')
                    ->getStateUsing(fn (Station $record) => $record->cases()->count())
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                EditAction::make()->visible(fn () => auth()->user()?->isAdmin()),
                DeleteAction::make()->visible(fn () => auth()->user()?->isAdmin()),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->visible(fn () => auth()->user()?->isAdmin()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStations::route('/'),
            'create' => CreateStation::route('/create'),
            'edit' => EditStation::route('/{record}/edit'),
        ];
    }
}
