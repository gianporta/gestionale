<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;

class IncassatoMensile extends ChartWidget
{
    protected static ?string $heading = 'Incassato mensile';

    protected function getData(): array
    {
        $data = DB::table('documenti')
            ->selectRaw("
                MONTH(data_pagamento) as mese,
                SUM(
                    CASE
                        WHEN pagato = '' THEN netto_a_pagare
                        ELSE pagato
                    END
                ) as totale
            ")
            ->where('tipo_documento', Invoice::TYPE_DOC)
            ->whereNotNull('data_pagamento')
            ->where('data_pagamento', '!=', '')
            ->whereYear('data_pagamento', date('Y'))
            ->groupBy('mese')
            ->orderBy('mese')
            ->pluck('totale', 'mese')
            ->toArray();

        $mesi = [
            1 => 'Gen', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mag', 6 => 'Giu', 7 => 'Lug', 8 => 'Ago',
            9 => 'Set', 10 => 'Ott', 11 => 'Nov', 12 => 'Dic',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Incassato €',
                    'data' => array_map(fn ($m) => $data[$m] ?? 0, array_keys($mesi)),
                ],
            ],
            'labels' => array_values($mesi),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
