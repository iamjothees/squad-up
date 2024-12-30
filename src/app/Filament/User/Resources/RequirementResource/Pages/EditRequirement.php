<?php

namespace App\Filament\User\Resources\RequirementResource\Pages;

use App\Filament\User\Resources\RequirementResource;
use App\Models\User;
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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['referal_partner_code'] = User::where('id', $data['referer_id'])->first()?->referal_partner_code;
    
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['referer_id'] = User::where('referal_partner_code', $data['referal_partner_code'])->first()?->id;
        unset($data['referal_partner_code']);
    
        return $data;
    }
}
