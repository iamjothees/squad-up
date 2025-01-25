<?php

namespace App\Filament\Resources\EnquiryResource\Pages;

use App\Enums\EnquiryStatus;
use App\Filament\Resources\EnquiryResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ManageEnquiries extends ManageRecords
{
    protected static string $resource = EnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'pending' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', EnquiryStatus::PENDING)->latest()),
            'responded' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', EnquiryStatus::RESPONDED)),
            'cancelled' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', EnquiryStatus::CANCELLED)),
        ];
    }
}
