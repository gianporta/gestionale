<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\FatturatoMensile;
use App\Filament\Widgets\IncassatoMensile;
use App\Filament\Widgets\ProformaMensile;
use App\Filament\Widgets\AcquistiMensile;
class Dashboard extends BaseDashboard
{
    protected static string $view = 'filament.pages.dash';
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
            FatturatoMensile::class,
            IncassatoMensile::class,
            ProformaMensile::class,
            AcquistiMensile::class,
        ];
    }
}
