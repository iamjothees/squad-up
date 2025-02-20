<?php

namespace App\Filament\User\Pages;

use App\DTOs\UserDTO;
use App\Services\PointService;
use App\Settings\PointsSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions\Action as FormsAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Number;

class Wallet extends Page
{
    protected static ?string $navigationIcon = 'icon-wallet';

    protected static string $view = 'filament.user.pages.wallet';

    protected static ?string $title = 'Wallet';

    protected static ?int $navigationSort = 1;

    public $formIsAmount = true;

    public function requestToRedeemAction(): Action // TODO: move to seperate component 
    {
        $pointService = app(PointService::class);
        $pointsSettings = app(PointsSettings::class);
        return Action::make('requestToRedeem')
            ->label('Request for withdrawal')
            ->form([                                // TODO: split points / amount logics in seperate component
                TextInput::make('points')
                        ->label(
                            fn () => $this->formIsAmount ? 'Amount' : 'Points'
                        )
                        ->numeric()
                        ->prefix(fn () => $this->formIsAmount ? '₹' : null)
                        ->prefixIcon(fn () => $this->formIsAmount ? null : 'icon-power')
                        ->minValue(fn () => $this->formIsAmount ? $pointsSettings->least_redeemable_point / $pointsSettings->point_per_amount : $pointsSettings->least_redeemable_point)
                        ->maxValue(fn () => $this->formIsAmount ? auth()->user()->current_points / $pointsSettings->point_per_amount : auth()->user()->current_points)
                        ->default( fn () => $this->formIsAmount ? auth()->user()->current_points / $pointsSettings->point_per_amount : auth()->user()->current_points)
                        ->hintAction(
                            FormsAction::make('points/amount')
                                ->label( 
                                    fn ($state) =>  $this->formIsAmount ? $state * $pointsSettings->point_per_amount : Number::currency($state / $pointsSettings->point_per_amount ?? 0)
                                )
                                ->action(function ($set, $get) use ($pointsSettings) {
                                    $this->formIsAmount = !$this->formIsAmount;
                                    $points = $this->formIsAmount ? auth()->user()->current_points / $pointsSettings->point_per_amount : auth()->user()->current_points;
                                    $set('points', $points);
                                }) 
                        )
                        ->live(onBlur: true)
            ])
            ->modalWidth('sm')
            ->extraAttributes(['class' => 'w-full'])
            ->disabled(fn () => 
                ( !auth()->user()->hasEarnedPoints() )
                || (auth()->user()->current_points < $pointsSettings->least_redeemable_point)
            )
            ->action(
                function (array $data) use ($pointService, $pointsSettings){
                    $pointService->requestForRedeem(
                        userDTO: UserDTO::fromModel(auth()->user()), 
                        points: $this->formIsAmount ? $data['points'] * $pointsSettings->point_per_amount : $data['points'] 
                    );
                    $this->redirect(route('filament.user.pages.wallet'), true);
                    Notification::make()
                        ->title('Request sent successfully')
                        ->success()
                        ->send();
                }
            );
    }

    
}
