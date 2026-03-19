<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;

class DashboardKpiFatturato extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $year = date('Y');

        $data = DB::table('documenti')
            ->selectRaw("QUARTER(data_documento) as trimestre, SUM(netto_a_pagare) as totale")
            ->whereYear('data_documento', $year)
            ->where('tipo_documento', Invoice::TYPE_DOC)
            ->groupBy('trimestre')
            ->pluck('totale', 'trimestre')
            ->toArray();

        return [
            Stat::make('Fatturato Q1', number_format($data[1] ?? 0, 2, ',', '.') . ' €'),
            Stat::make('Fatturato Q2', number_format($data[2] ?? 0, 2, ',', '.') . ' €'),
            Stat::make('Fatturato Q3', number_format($data[3] ?? 0, 2, ',', '.') . ' €'),
            Stat::make('Fatturato Q4', number_format($data[4] ?? 0, 2, ',', '.') . ' €'),
        ];
    }
}
