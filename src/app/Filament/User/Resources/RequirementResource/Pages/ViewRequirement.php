<?php

namespace App\Filament\User\Resources\RequirementResource\Pages;

use App\Filament\User\Resources\RequirementResource;
use App\Models\User;
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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['referal_partner_code'] = User::where('id', $data['referer_id'])->first()?->referal_partner_code;
    
        return $data;
    }
}
