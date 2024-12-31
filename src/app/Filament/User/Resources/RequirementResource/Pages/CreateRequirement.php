<?php

namespace App\Filament\User\Resources\RequirementResource\Pages;

use App\Filament\User\Resources\RequirementResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRequirement extends CreateRecord
{
    protected static string $resource = RequirementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        
        $data['owner_id'] = auth()->id();
        
        $data['referer_id'] = User::where('referal_partner_code', $data['referal_partner_code'])->first()?->id;
        unset($data['referal_partner_code']);
    
        return $data;
    }


}
