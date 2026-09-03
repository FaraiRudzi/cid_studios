<?php

namespace App\Filament\Resources\StationResource\Pages;

use App\Filament\Resources\StationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStation extends CreateRecord
{
    protected static string $resource = StationResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Save Station'),
            $this->getCancelFormAction(),
        ];
    }
}
