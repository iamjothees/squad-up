<?php

namespace App\Filament\User\Pages;

use App\DTOs\UserDTO;
use App\Services\PointService;
use App\Settings\GeneralSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;

class Wallet extends Page
{
    protected static ?string $navigationIcon = 'icon-wallet';

    protected static string $view = 'filament.user.pages.wallet';

    protected static ?string $title = 'Wallet';

    protected static ?int $navigationSort = 1;


    public function requestToRedeemAction(): Action
    {
        $pointService = app(PointService::class);
        $settings = app(GeneralSettings::class);
        return Action::make('requestToRedeem')
            ->form([
                TextInput::make('points')
                        ->numeric()
                        ->minValue($settings->least_redeemable_point)
                        ->default(auth()->user()->current_points)
            ])
            ->modalWidth('sm')
            ->extraAttributes(['class' => 'w-full'])
            ->action(
                fn (array $data) => $pointService->requestForRedeem(userDTO: UserDTO::fromModel(auth()->user()), points: $data['points'] ));
    }

    
}
