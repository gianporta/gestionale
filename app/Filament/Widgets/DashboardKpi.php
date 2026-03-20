<?php

namespace App\Filament\Widgets;

use App\Models\Proforma;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;

class DashboardKpi extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected function getStats(): array
    {
        $year = date('Y');

        $fatturato = DB::table('documenti')
            ->whereYear('data_documento', $year)
            ->where('tipo_documento', Invoice::TYPE_DOC)
            ->sum('netto_a_pagare');

        $incassato = DB::table('documenti')
            ->whereYear('data_pagamento', $year)
            ->where('tipo_documento', Invoice::TYPE_DOC)
            ->selectRaw("
                SUM(
                    CASE
                        WHEN pagato IS NULL OR pagato = '' THEN netto_a_pagare
                        ELSE pagato
                    END
                ) as totale
            ")
            ->value('totale');

        $daIncassare = Proforma::query()
            ->where(function ($q) {
                $q->whereNull('data_pagamento')
                    ->orWhere('data_pagamento', '');
            })
            ->sum('netto_a_pagare');
        return [
            Stat::make('Fatturato anno', number_format($fatturato, 2, ',', '.') . ' €'),
            Stat::make('Incassato anno', number_format($incassato, 2, ',', '.') . ' €'),
            Stat::make('Da incassare', number_format($daIncassare, 2, ',', '.') . ' €'),
        ];
    }
}
