<?php

namespace App\Filament\User\Resources\RequirementResource\Pages;

use App\Enums\RequirementStatus;
use App\Filament\User\Resources\RequirementResource;
use App\Services\RequirementService;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class ListRequirements extends ListRecords
{
    protected static string $resource = RequirementResource::class;

    public function getTitle(): string | Htmlable{
        return 'Owned';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return RequirementService::getTabs();
    }

    protected function getTableQuery(): ?Builder
    {
        return RequirementResource::getEloquentQuery()->where('owner_id', auth()->id());
    }
}
