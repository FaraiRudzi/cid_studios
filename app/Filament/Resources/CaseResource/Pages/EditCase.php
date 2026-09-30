<?php

namespace App\Filament\Resources\CaseResource\Pages;

use App\Exceptions\EvidenceProtectionException;
use App\Filament\Resources\CaseResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EditCase extends EditRecord
{
    protected static string $resource = CaseResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Save Changes'),
            $this->getCancelFormAction()->label('Back to Case'),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return DB::transaction(function () use ($record, $data): Model {
                // Whitelist: a photographer can never change assignment, scene reference or creator here.
                $attributes = Arr::only($data, [
                    'reference_number', 'station_id', 'case_type', 'circumstances', 'cause_of_death', 'status',
                ]);

                $record = parent::handleRecordUpdate($record, $attributes);

                if (array_key_exists('people', $data)) {
                    CaseResource::syncCasePeople($record, $data['people'] ?? []);
                }

                if (! empty($data['media'])) {
                    CaseResource::syncCaseMedia($record, $data['media']);
                }

                return $record;
            });
        } catch (EvidenceProtectionException|AuthorizationException $e) {
            // The transaction has been rolled back, so nothing was partly saved.
            Notification::make()
                ->title('Changes were not saved')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->halt();

            throw $e;
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['people'] = $this->record->people->map(fn ($person): array => [
            'id' => $person->getKey(),
            'role' => $person->pivot?->role,
            'first_name' => $person->first_name,
            'surname' => $person->surname,
            'id_number' => $person->id_number,
            'gender' => $person->gender,
            'email' => $person->email,
            'phone_number' => $person->phone_number,
            'address' => $person->address,
            'notes' => $person->pivot?->notes,
        ])->all();

        // Existing media is never loaded into the form: it is append-only and is shown on the case page.
        $data['media'] = [];

        return $data;
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->record->loadMissing(['people', 'media', 'station', 'photographer']);
    }
}
