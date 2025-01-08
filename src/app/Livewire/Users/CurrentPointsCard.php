<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Services\UserService;
use Filament\Notifications\Notification;
use Livewire\Component;

class CurrentPointsCard extends Component
{

    public User $user;

    public int $points = 0;


    public function mount(){
        $this->points = $this->user->current_points;
        cache([ 'manualRefreshCount' => 0 ]);
    }

    public function render()
    {
        return view('livewire.users.current-points-card');
    }

    public function refreshPoints(){
        cache([
            'manualRefreshCount' => cache('manualRefreshCount') + 1
        ], now()->addMinutes(3));

        if ( cache('manualRefreshCount') > 5 ){
            Notification::make()->title('Too many refreshes! Waiting for 3 minutes.')->warning()->send();
            return;
        }

        $points = app(UserService::class)->getCurrentPoints(user: $this->user);
        if ( $points === $this->points ) return;

        $this->user->current_points = $points;
        $this->user->push();
        $this->points = $points;
    }
}
