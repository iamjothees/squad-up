<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\User\Pages\Auth\Register;
use App\Filament\User;
use App\Filament\User\Pages\Auth\Login;
use App\Filament\User\Resources\RequirementResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = match (config('app.env')) {
            'production' => $panel->domain('squadup.'.config('app.domain')),
            default => $panel->path('squadup'),
        };
        return $panel
            ->id('user')
            ->login(Login::class)
            ->registration(Register::class)
            ->profile(EditProfile::class, isSimple: false)
            ->discoverResources(in: app_path('Filament/User/Resources'), for: 'App\\Filament\\User\\Resources')
            ->discoverPages(in: app_path('Filament/User/Pages'), for: 'App\\Filament\\User\\Pages')
            ->pages([
                User\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/User/Widgets'), for: 'App\\Filament\\User\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // ->plugin(new LocalLogins())
            ->font('Roboto Mono')
            ->darkMode(true, true)
            ->colors([
                'primary' => '#006600',
            ])
            ->topNavigation()
            ->navigationItems([
                NavigationItem::make('Refered Requirements')
                    ->label('Refered')
                    ->icon('icon-refered-requirements')
                    ->url(fn () => RequirementResource::getUrl('refered'))
                    ->isActiveWhen(fn () => request()->routeIs(RequirementResource::getRouteBaseName() . '.refered'))
                    ->group('Requirements')
                    ->sort(3),

            ])
            ->databaseTransactions()
            ->viteTheme('resources/css/filament/user/theme.css');
    }
}
