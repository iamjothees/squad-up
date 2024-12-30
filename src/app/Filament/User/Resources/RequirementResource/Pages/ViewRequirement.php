<?php

namespace App\Filament\User\Resources\RequirementResource\Pages;

use App\Filament\User\Resources\RequirementResource;
use Filament\Actions;
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
}
