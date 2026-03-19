<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
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
    protected function getRedirectUrl(): ?string
    {
        if (auth()->user()->hasRole('threecommerce'))
            return '/admin/three-dash';

        return null;
    }
}
