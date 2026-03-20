<?php
declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Widgets\AndamentoMensile;
use App\Filament\Widgets\CalendarOre;
use App\Filament\Widgets\DashboardKpi;
use App\Filament\Widgets\DashboardKpiFatturato;
use App\Filament\Widgets\DashboardKpiIva;
use App\Filament\Widgets\ProformaScaduti;
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
use App\Filament\Pages\Clienti\Pacchetti;
use App\Filament\Pages\CartaIntestata;
use App\Filament\Pages\ThreeDash;
use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\PacchettiOverview;
use App\Http\Middleware\RedirectDashboard;
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
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->sidebarCollapsed()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
                ThreeDash::class,
                CartaIntestata::class,
                Pacchetti::class,
            ])
            ->widgets([
                AndamentoMensile::class,
                DashboardKpi::class,
                DashboardKpiFatturato::class,
                DashboardKpiIva::class,
                PacchettiOverview::class,
                CalendarOre::class,
                ProformaScaduti::class,
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
                RedirectDashboard::class
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->navigationGroups([
                'Clienti',
                'Anagrafiche',
                'Documenti',
                'Threecommerce',
                'Utility',
                'Repository',
                'Sistema',
            ])
            ->navigationItems([
                NavigationItem::make('Pacchetti')
                    ->icon('heroicon-o-archive-box')
                    ->url('/admin/clienti/pacchetti')
                    ->group('Clienti')
                    ->sort(1)
                    ->visible(fn() => auth()->user()->hasAnyRole(['cliente'])),
                NavigationItem::make('Ore')
                    ->icon('heroicon-o-clock')
                    ->url('/admin/clienti/ore')
                    ->group('Clienti')
                    ->sort(2)
                    ->visible(fn() => auth()->user()->hasAnyRole(['cliente'])),
                NavigationItem::make('Stime')
                    ->icon('heroicon-o-calculator')
                    ->url('/admin/clienti/stime')
                    ->group('Clienti')
                    ->sort(3)
                    ->visible(fn() => auth()->user()->hasAnyRole(['cliente'])),
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
