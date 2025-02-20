<?php

namespace App\Filament\User\Resources\RequirementResource\Pages;

use App\Filament\User\Resources\RequirementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class ReferedRequirements extends ListRecords
{
    protected static string $resource = RequirementResource::class;

    public function getBreadcrumb(): ?string
    {
        return null;
    }

    public function getTitle(): string | Htmlable
    {
        return 'Refered';
    }

    protected function getTableQuery(): ?Builder
    {
        return RequirementResource::getEloquentQuery()->whereHas('reference', fn ($q) => $q->where('referer_id', auth()->id()));
    }
}
