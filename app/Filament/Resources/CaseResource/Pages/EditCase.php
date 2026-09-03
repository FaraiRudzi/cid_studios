<?php

namespace App\Filament\Resources\CaseResource\Pages;

use App\Filament\Resources\CaseResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

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
        $record = parent::handleRecordUpdate($record, $data);

        if (array_key_exists('people', $data)) {
            CaseResource::syncCasePeople($record, $data['people']);
        }

        if (array_key_exists('media', $data)) {
            CaseResource::syncCaseMedia($record, $data['media']);
        }

        return $record;
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

        $data['media'] = $this->record->media->map(fn ($media): array => [
            'id' => $media->getKey(),
            'title' => $media->title,
            'category' => $media->category,
            'file_path' => $media->getFilePaths(),
            'file_type' => $media->file_type,
            'file_size' => $media->file_size,
            'description' => $media->description,
            'uploaded_by' => $media->uploaded_by,
        ])->all();

        return $data;
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->record->loadMissing(['people', 'media', 'station', 'photographer']);
    }
}
