<?php
declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
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
            ->colors([
                'primary' => Color::Amber,
                'success' => Color::Green,
                'warning' => Color::Yellow,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
                \App\Filament\Pages\ThreeDash::class,
                \App\Filament\Pages\CartaIntestata::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
            ])
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
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->navigationGroups([
                'Anagrafiche',
                'Documenti',
                'Threecommerce',
                'Utility',
                'Repository',
                'Sistema',
            ])
            ->navigationItems([
                NavigationItem::make('Dash')
                    ->icon('heroicon-o-chart-bar')
                    ->url('/admin/three-dash')
                    ->group('Threecommerce')
                    ->sort(1)
                    ->visible(fn() => auth()->user()->hasAnyRole(['admin', 'threecommerce'])),
                NavigationItem::make('Utility')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->url('/admin/utilities')
                    ->group('Utility')
                    ->sort(4)
                    ->visible(fn() => auth()->user()->hasAnyRole(['admin', 'threecommerce'])),
                NavigationItem::make('Carta intestata')
                    ->icon('heroicon-o-printer')
                    ->url('/admin/carta-intestata')
                    ->openUrlInNewTab()
                    ->group('Utility')
                    ->sort(4)
                    ->visible(fn() => auth()->user()->hasRole('admin'))
            ]);
    }
}
