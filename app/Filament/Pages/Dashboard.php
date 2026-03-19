<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\AndamentoMensile;
use App\Filament\Widgets\DashboardKpi;
use App\Filament\Widgets\ProformaScaduti;
use App\Filament\Widgets\DashboardKpiFatturato;
use App\Filament\Widgets\DashboardKpiIva;
class Dashboard extends BaseDashboard
{
    protected static bool $isLazy = false;
    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole('admin');
    }
    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole(['admin']);
    }

    public function getTitle(): string
    {
        return 'Dash ';
    }
    protected function getHeaderWidgets(): array
    {
        return [
            AndamentoMensile::class,
            DashboardKpi::class,
            DashboardKpiFatturato::class,
            DashboardKpiIva::class,
            ProformaScaduti::class,
        ];
    }
}
