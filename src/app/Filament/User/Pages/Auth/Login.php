<?php

namespace App\Filament\User\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseAuth;
use Illuminate\Validation\ValidationException;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class Login extends BaseAuth
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getLoginFormComponent(), 
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getLoginFormComponent(): Component
    {
        return 
        Group::make([
            TextInput::make('email')
                ->label('Email address')
                ->requiredIf('phone', null)
                ->autocomplete()
                ->autofocus()
                ->extraInputAttributes(['tabindex' => 1])
                ->hidden(),
            Placeholder::make('or')->hidden(),
            PhoneInput::make('phone')
                ->label('Phone number')
                ->required()
                ->extraInputAttributes(['tabindex' => 1])
        ]);
    } 

    protected function getCredentialsFromFormData(array $data): array
    {
        $login_type = $data['phone'] ? 'phone' : 'email';
 
        return [
            $login_type => $data['email'] ?? $data['phone'],
            'password'  => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => __('filament-panels::pages/auth/login.messages.failed'),
            'data.phone' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}
