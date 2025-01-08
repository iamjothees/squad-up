<?php

namespace App\Filament\User\Resources\RequirementResource\Pages;

use App\DTOs\RequirementDTO;
use App\Filament\User\Resources\RequirementResource;
use App\Models\Requirement;
use App\Services\RequirementService;
use Filament\Resources\Pages\CreateRecord;

class CreateRequirement extends CreateRecord
{
    protected static string $resource = RequirementResource::class;

    protected function handleRecordCreation(array $data): Requirement
    {
        $data['owner_id'] = auth()->id();
        
        $requirementDTO = app(RequirementService::class)->createRequirement(RequirementDTO::fromArray($data));
        return $requirementDTO->toModel();
    }

}
