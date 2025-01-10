<?php

namespace App\Livewire\Users;

use App\Models\Point;
use App\Models\PointRedeem;
use App\Models\User;
use App\Settings\GeneralSettings;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Number;
use Livewire\Component;

class Redeems extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public User $user;

    public function mount(){
        
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn () => PointRedeem::query()
                    ->where('owner_id', $this->user->id)
                    ->orderBy('created_at', 'desc')
            )
            ->emptyStateHeading('No withdraws found')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested On')
                    ->dateTime('d-m-Y h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('redeemed_at')
                    ->label('Status')
                    ->state(fn ($record) => $record->redeemed_at ? 'Cashout' : 'Pending'),
                Tables\Columns\TextColumn::make('points')
                    ->numeric()
                    ->sortable()
                    ->alignRight(),
                Tables\Columns\TextColumn::make('points_value')
                    ->label('Withdrawal Amount')
                    ->state(fn ($record, GeneralSettings $generalSettings) => Number::currency($record->points / $generalSettings->point_per_amount))
                    ->alignRight(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }

    public function render()
    {
        return view('livewire.users.redeems');
    }
}
