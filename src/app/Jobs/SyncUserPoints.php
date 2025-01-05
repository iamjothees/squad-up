<?php

namespace App\Jobs;

use App\DTOs\UserDTO;
use App\Services\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;

class SyncUserPoints implements ShouldQueue
{
    use Queueable;

    public function __construct( private Collection $userDTOs )
    {
        //
    }

    public function handle(): void
    {
        $userService = app(UserService::class);
        $this->userDTOs->each(fn(UserDTO $userDTO) => $userService->syncPoints(userDTO: $userDTO));
    }
}
