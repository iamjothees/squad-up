<?php

namespace App\Filament\Resources\PointRedeemResource\Pages;

use App\Filament\Resources\PointRedeemResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePointRedeems extends ManageRecords
{
    protected static string $resource = PointRedeemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
