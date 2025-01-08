<?php

namespace App\Filament\User\Resources\RequirementResource\Pages;

use App\DTOs\RequirementDTO;
use App\Filament\User\Resources\RequirementResource;
use App\Services\RequirementService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditRequirement extends EditRecord
{
    protected static string $resource = RequirementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return app(RequirementService::class)->mutateFormDataBeforeFill(data: $data, requirement: $this->record);
    }


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $data['id'] = $record->id;
        $data['owner_id'] = $record->owner_id;
        
        $requirementDTO = app(RequirementService::class)->updateRequirement(RequirementDTO::fromFilamentData($data));
        return $requirementDTO->toModel();
    }
}
