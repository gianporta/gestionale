<?php

namespace App\Filament\Pages\Clienti;

use App\Models\Hours;
use App\Models\Packages;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Stime extends Page
{
    protected static ?string $slug = 'clienti/stime';
    protected static string $view = 'filament.pages.clienti.stime';
    protected static bool $isLazy = false;
    protected static bool $shouldRegisterNavigation = false;
    public function getTitle(): string
    {
        return 'Stime';
    }
    protected function getClienteId()
    {
        return auth()->user()->cliente_id;
    }
    protected function getViewData(): array
    {
        return [
        ];
    }

}
