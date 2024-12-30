<?php

namespace App\Filament\User\Resources\RequirementResource\Pages;

use App\Enum\RequirementStatus;
use App\Filament\User\Resources\RequirementResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListRequirements extends ListRecords
{
    protected static string $resource = RequirementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'yet_to_confirm' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', RequirementStatus::PENDING)),
            'in_progress' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', RequirementStatus::IN_PROGRESS)),
            'rejected' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', RequirementStatus::REJECTED)),
        ];
    }
}
