<?php

namespace App\Filament\Resources\RequirementResource\Pages;

use App\DTOs\RequirementDTO;
use App\Filament\Resources\RequirementResource;
use App\Models\User;
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


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $data['id'] = $record->id;

        $requirementDTO = app(RequirementService::class)->updateRequirement(RequirementDTO::fromFilamentData($data));
        return $requirementDTO->toModel();
    }
}
