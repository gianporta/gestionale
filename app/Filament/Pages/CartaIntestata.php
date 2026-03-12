<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class CartaIntestata extends Page
{
    protected static string $view = 'filament.print.carta-intestata';
    protected static bool $shouldRegisterNavigation = false;
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
