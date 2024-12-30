<?php

namespace App\Filament\User\Resources\RequirementResource\Pages;

use App\Filament\User\Resources\RequirementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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
}
