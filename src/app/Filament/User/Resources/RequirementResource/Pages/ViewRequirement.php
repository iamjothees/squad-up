<?php

namespace App\Filament\User\Resources\RequirementResource\Pages;

use App\Filament\User\Resources\RequirementResource;
use App\Models\User;
use App\Services\RequirementService;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ViewRecord;

class ViewRequirement extends ViewRecord
{
    protected static string $resource = RequirementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return app(RequirementService::class)->mutateFormDataBeforeFill(data: $data, requirement: $this->record);
    }
}
