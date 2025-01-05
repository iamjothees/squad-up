<?php

namespace App\Observers;

use App\DTOs\UserDTO;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Context;

class UserObserver
{
    public function __construct(protected UserService $userService)
    {
        
    }

    public function creating(User $user): void
    {
        Context::add('referal_partner_code_provided', (bool) $user->referal_partner_code);
        $user->referal_partner_code = $user->referal_partner_code ?: str()->uuid();
    }

    public function created(User $user): void
    {
        $this->handleReferalPartnerCode($user);

        $this->userService->creditSignupBonusPoints(userDTO: UserDTO::fromModel($user));
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }


    private function handleReferalPartnerCode(User $user): void{
        if (Context::get('referal_partner_code_provided')) return;
        $user->referal_partner_code = $this->userService->generateReferalPartnerCode(user: $user);
        $user->saveQuietly();
    }
}
