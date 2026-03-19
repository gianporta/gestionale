<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;

class FatturatoMensile extends ChartWidget
{
    protected static ?string $heading = 'Fatturato mensile';

    protected function getData(): array
    {
        $data = DB::table('documenti')
        ->selectRaw('MONTH(data_documento) as mese, SUM(netto_a_pagare) as netto_a_pagare')
            ->where('tipo_documento', Invoice::TYPE_DOC)
            ->whereYear('data_documento', date('Y'))
            ->groupBy('mese')
            ->orderBy('mese')
            ->pluck('netto_a_pagare', 'mese')
            ->toArray();

        $mesi = [
            1 => 'Gen', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mag', 6 => 'Giu', 7 => 'Lug', 8 => 'Ago',
            9 => 'Set', 10 => 'Ott', 11 => 'Nov', 12 => 'Dic',
        ];

        $labels = [];
        $values = [];

        foreach ($mesi as $num => $nome) {
            $labels[] = $nome;
            $values[] = $data[$num] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Fatturato €',
                    'data' => $values,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
