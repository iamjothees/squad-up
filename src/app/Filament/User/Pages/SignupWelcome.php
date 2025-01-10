<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;

class SignupWelcome extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.user.pages.signup-welcome';

    protected static ?string $title = '';

    public function getLayout(): string
    {
        return 'filament-panels::components.layout.base'; // Switch to a layout without the menu
    }
}
