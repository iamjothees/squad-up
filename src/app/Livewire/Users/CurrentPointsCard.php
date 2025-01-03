<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Service\UserService;
use Livewire\Component;

class CurrentPointsCard extends Component
{

    public User $user;

    public int $points = 0;


    public function mount(UserService $userService){
        $this->points = $userService->getCurrentPoints(user: $this->user);
    }

    public function render()
    {
        return view('livewire.users.current-points-card');
    }
}
