<?php

namespace App\Filament\User\Resources\ProjectResource\Pages;

use App\Filament\User\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ManageProjects extends ManageRecords
{
    protected static string $resource = ProjectResource::class;
}
