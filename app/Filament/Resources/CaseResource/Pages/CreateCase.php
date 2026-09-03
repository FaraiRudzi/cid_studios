<?php

namespace App\Filament\Resources\CaseResource\Pages;

use App\Filament\Resources\CaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCase extends CreateRecord
{
    protected static string $resource = CaseResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        CaseResource::syncCasePeople($this->record, $this->data['people'] ?? []);
        CaseResource::syncCaseMedia($this->record, $this->data['media'] ?? []);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Save Case'),
            $this->getCancelFormAction()->label('Back to Cases'),
        ];
    }
}
