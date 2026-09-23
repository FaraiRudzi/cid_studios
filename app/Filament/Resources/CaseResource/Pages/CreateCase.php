<?php

namespace App\Filament\Resources\CaseResource\Pages;

use App\Filament\Resources\CaseResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateCase extends CreateRecord
{
    protected static string $resource = CaseResource::class;

    protected static bool $canCreateAnother = false;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('backToCases')
                ->label('Back to Cases')
                ->url(CaseResource::getUrl('index'))
                ->outlined(),
        ];
    }

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
            $this->getCancelFormAction()->label('Cancel')->outlined(),
        ];
    }
}
