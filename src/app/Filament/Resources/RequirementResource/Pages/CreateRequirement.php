<?php

namespace App\Filament\Resources\RequirementResource\Pages;

use App\DTOs\RequirementDTO;
use App\Filament\Resources\RequirementResource;
use App\Models\Requirement;
use App\Services\RequirementService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRequirement extends CreateRecord
{
    protected static string $resource = RequirementResource::class;

    protected function handleRecordCreation(array $data): Requirement
    {   
        $requirementDTO = app(RequirementService::class)->createRequirement(RequirementDTO::fromFilamentData($data));
        return $requirementDTO->toModel();
    }
    
}
