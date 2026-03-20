<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\Proforma;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class FatturatoTotale extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected function getColumns(): int
    {
        return 1;
    }
    protected function getStats(): array
    {

        $fatturatoTotale = DB::table('documenti')
            ->where('tipo_documento', Invoice::TYPE_DOC)
            ->sum('netto_a_pagare');
        return [
            Stat::make('Fatturato Totale', number_format($fatturatoTotale, 2, ',', '.') . ' €'),
        ];
    }
}
