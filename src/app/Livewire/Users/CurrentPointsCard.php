<?php

namespace App\Livewire\Users;

use App\DTOs\UserDTO;
use App\Models\User;
use App\Services\UserService;
use Filament\Notifications\Notification;
use Livewire\Component;

class CurrentPointsCard extends Component
{

    public User $user;

    public int $points = 0;
    public string $description = "Can be withdrawn at any time";


    public function mount(){
        if (!$this->user->hasEarnedPoints()) $this->description = "Start withdrawing points by earning some!";
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

        $points = app(UserService::class)->getCurrentPoints(userDTO: UserDTO::fromModel($this->user));
        if ( $points === $this->points ) return;

        $this->user->current_points = $points;
        $this->user->push();

        $this->redirect(route('filament.user.pages.wallet'), true);

        // $this->description = ($this->user->refresh()->hasEarnedPoints()) ? "Can be withdrawn at any time" : "Start withdrawing points by earning some!";
        // $this->points = $points;
    }
}
