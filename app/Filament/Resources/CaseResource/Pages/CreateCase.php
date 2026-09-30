<?php

namespace App\Filament\Resources\CaseResource\Pages;

use App\Filament\Resources\CaseResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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

    /**
     * The scene reference number is generated in CaseObserver. If two admins create a case at the
     * same instant they can compute the same number; the unique index rejects one, and we retry
     * so it receives the next number. The case, its people and its audit entry commit together.
     */
    protected function handleRecordCreation(array $data): Model
    {
        $attributes = Arr::except($data, ['scene_reference_number', 'created_by']);
        $attempts = 0;

        while (true) {
            try {
                return DB::transaction(function () use ($attributes, $data): Model {
                    $case = parent::handleRecordCreation($attributes);

                    CaseResource::syncCasePeople($case, $data['people'] ?? []);

                    return $case;
                });
            } catch (UniqueConstraintViolationException $e) {
                if (++$attempts >= 5) {
                    throw $e;
                }
            }
        }
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
