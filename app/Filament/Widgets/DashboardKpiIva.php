<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;

class DashboardKpiIva extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $year = date('Y');

        $data = DB::table('documenti')
            ->selectRaw("
                QUARTER(data_pagamento) as trimestre,
                SUM(
                    CASE
                        WHEN pagato IS NULL OR pagato = '' THEN iva
                        ELSE iva * (pagato / netto_a_pagare)
                    END
                ) as totale
            ")
            ->whereYear('data_pagamento', $year)
            ->where('tipo_documento', Invoice::TYPE_DOC)
            ->groupBy('trimestre')
            ->pluck('totale', 'trimestre')
            ->toArray();

        return [
            Stat::make('IVA Q1', number_format($data[1] ?? 0, 2, ',', '.') . ' €'),
            Stat::make('IVA Q2', number_format($data[2] ?? 0, 2, ',', '.') . ' €'),
            Stat::make('IVA Q3', number_format($data[3] ?? 0, 2, ',', '.') . ' €'),
            Stat::make('IVA Q4', number_format($data[4] ?? 0, 2, ',', '.') . ' €'),
        ];
    }
}
