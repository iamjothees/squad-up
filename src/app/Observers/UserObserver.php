<?php

namespace App\Observers;

use App\Models\User;
use App\Service\UserService;
use Illuminate\Support\Facades\Context;

class UserObserver
{
    public function __construct(protected UserService $userService)
    {
        
    }

    public function creating(User $user): void
    {
        Context::add('referal_partner_code_available', (bool) $user->referal_partner_code);
        $user->referal_partner_code = $user->referal_partner_code ?: str()->uuid();
    }

    public function created(User $user): void
    {
        if (Context::get('referal_partner_code_available')) return;
        $user->referal_partner_code = $this->userService->generateReferalPartnerCode(user: $user);
        $user->saveQuietly();
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
}
