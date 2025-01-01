<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $view = 'filament.user.pages.dashboard';

    public function closeBanner(string $bannerId): void{
        session()->put('closed_banners', [...session()->get('closed_banners', []), $bannerId]);
    }
}
