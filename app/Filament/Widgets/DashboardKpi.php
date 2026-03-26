<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\Proforma;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class DashboardKpi extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $year = date('Y');

        $fatturato = DB::table('documenti')
            ->whereYear('data_documento', $year)
            ->where('tipo_documento', Invoice::TYPE_DOC)
            ->sum(DB::raw("
            CAST(REPLACE(REPLACE(COALESCE(netto_a_pagare, 0), '.', ''), ',', '.') AS DECIMAL(12,2))
        "));

        $incassato = DB::table('documenti')
            ->where('tipo_documento', Invoice::TYPE_DOC)
            ->whereYear('data_documento', $year)
            ->sum(DB::raw("
            CASE
                WHEN data_pagamento IS NOT NULL AND data_pagamento != '' THEN
                    CASE
                        WHEN pagato IS NOT NULL AND pagato != '' AND CAST(REPLACE(REPLACE(pagato, '.', ''), ',', '.') AS DECIMAL(12,2)) > 0
                            THEN CAST(REPLACE(REPLACE(pagato, '.', ''), ',', '.') AS DECIMAL(12,2))
                        ELSE CAST(REPLACE(REPLACE(COALESCE(netto_a_pagare, 0), '.', ''), ',', '.') AS DECIMAL(12,2))
                    END
                ELSE 0
            END
        "));

        $baseQuery = DB::table('documenti')
            ->whereIn('tipo_documento', [
                Invoice::TYPE_DOC,
                Proforma::TYPE_DOC
            ]);
        $nonPagate = (clone $baseQuery)
            ->where('stato_documento', 1)
            ->sum('netto_a_pagare');
        $parziali = (clone $baseQuery)
            ->where('stato_documento', 2)
            ->selectRaw('SUM(netto_a_pagare) - SUM(pagato) as totale')
            ->value('totale');

        $daIncassare = ($nonPagate ?? 0) + ($parziali ?? 0);

        return [
            Stat::make('Fatturato anno', number_format((float)$fatturato, 2, ',', '.') . ' €'),
            Stat::make('Incassato anno', number_format((float)$incassato, 2, ',', '.') . ' €'),
            Stat::make('Da incassare', number_format((float)$daIncassare, 2, ',', '.') . ' €'),
        ];
    }
}
