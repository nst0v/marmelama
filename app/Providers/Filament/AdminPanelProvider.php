<?php

namespace App\Providers\Filament;

use App\Filament\Resources\BreedingCats\BreedingCatResource;
use App\Filament\Resources\Kittens\KittenResource;
use App\Filament\Resources\Litters\LitterResource;
use App\Filament\Resources\Reviews\ReviewResource;
use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Filament\Resources\Slides\SlideResource;
use App\Filament\Widgets\AdminQuickActions;
use App\Filament\Widgets\AdminWelcome;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('МарМелАма')
            ->favicon(asset('favicon.svg'))
            ->colors([
                'primary' => Color::hex('#8B5E3C'),
            ])
            ->resources([
                KittenResource::class,
                LitterResource::class,
                SlideResource::class,
                ReviewResource::class,
                BreedingCatResource::class,
                SiteSettingResource::class,
            ])
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                AdminWelcome::class,
                AdminQuickActions::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
