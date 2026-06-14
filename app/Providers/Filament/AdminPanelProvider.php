<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->brandName('Sairox')
            ->brandLogo(null)
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
            ])
            ->navigationGroups([
                NavigationGroup::make('Posts')
                    ->collapsible()
                    ->collapsed(),
                NavigationGroup::make('Media')
                    ->collapsible()
                    ->collapsed(),
                NavigationGroup::make('Users')
                    ->collapsible()
                    ->collapsed(),
            ])
            ->renderHook(
                'panels::head.done',
                fn (): string => new HtmlString('
                    <style>
                        /* Tighten the vertical gap between the Group Label and Items */
                        .fi-sidebar-group {
                            margin-top: 0.25rem !important;
                            padding-bottom: 0px !important;
                        }

                        /* Shrink the individual menu item buttons */
                        .fi-sidebar-item-button {
                            padding-top: 0.35rem !important;
                            padding-bottom: 0.35rem !important;
                            gap: 0.6rem !important; /* Tightens the space between Icon and Text */
                        }

                        /* Polish the Group Labels (Posts, Media) */
                        .fi-sidebar-group-label-button {
                            padding-top: 0.5rem !important;
                            padding-bottom: 0.2rem !important;
                        }

                        .fi-sidebar-group-label {
                            font-size: 0.75rem !important;
                            font-weight: 700 !important;
                            text-transform: uppercase;
                            letter-spacing: 0.05em !important;
                            color: #6b7280 !important; /* Slightly darker gray for clarity */
                        }

                        /* Optional: Make the active item stand out more like the demo */
                        .fi-sidebar-item-active {
                            background-color: rgba(245, 158, 11, 0.1) !important;
                        }
                    </style>
                '),
            );

    }
}
