<?php

namespace App\Http\Responses;

use App\Filament\User\Pages\SignupWelcome;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class RegisterResponse extends \Filament\Http\Responses\Auth\RegistrationResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        return redirect()->to(SignupWelcome::getUrl());
    }
}
