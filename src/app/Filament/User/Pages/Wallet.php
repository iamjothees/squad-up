<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Wallet extends Page
{
    protected static ?string $navigationIcon = 'icon-wallet';

    protected static string $view = 'filament.user.pages.wallet';

    protected static ?string $title = 'Wallet';

    protected static ?int $navigationSort = 1;

    public function getHeading(): string|Htmlable
{
    return new HtmlString("
        <div class='flex flex-row justify-between align-top'>
            <h1 class='text-2xl font-bold text-gray-900 dark:text-white'>Wallet ABC</h1>
            <livewire:users.current-points-card :user='".auth()->user()."' />
        </div>
    ");
}
    
}
