<?php

namespace App\Filament\Widgets;

use App\Models\Proforma;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\Acquisti;

class AndamentoMensile extends ChartWidget
{
    protected static ?string $heading = 'Andamento mensile';
    protected int|string|array $columnSpan = 'full';
    protected function getData(): array
    {
        $year = date('Y');

        $fatturato = DB::table('documenti')
            ->selectRaw('MONTH(data_documento) mese, SUM(netto_a_pagare) totale')
            ->whereYear('data_documento', $year)
            ->where('tipo_documento', Invoice::TYPE_DOC)
            ->groupBy('mese')
            ->pluck('totale', 'mese')
            ->toArray();

        $incassato = DB::table('documenti')
            ->selectRaw("
                MONTH(data_pagamento) mese,
                SUM(
                    CASE
                        WHEN pagato IS NULL OR pagato = '' THEN netto_a_pagare
                        ELSE pagato
                    END
                ) totale
            ")
            ->whereYear('data_pagamento', $year)
            ->where('tipo_documento', Invoice::TYPE_DOC)
            ->groupBy('mese')
            ->pluck('totale', 'mese')
            ->toArray();

        $proforma = Proforma::query()
            ->selectRaw('MONTH(data_documento) mese, SUM(netto_a_pagare) totale')
            ->whereYear('data_documento', $year)
            ->where(function ($q) {
                $q->whereNull('data_pagamento')
                    ->orWhere('data_pagamento', '');
            })
            ->groupBy('mese')
            ->pluck('totale', 'mese')
            ->toArray();

        $speso = DB::table('documenti')
            ->selectRaw('MONTH(data_documento) mese, SUM(netto_a_pagare) totale')
            ->whereYear('data_documento', $year)
            ->where('tipo_documento', Acquisti::TYPE_DOC)
            ->groupBy('mese')
            ->pluck('totale', 'mese')
            ->toArray();

        $mesi = [
            1 => 'Gen', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mag', 6 => 'Giu', 7 => 'Lug', 8 => 'Ago',
            9 => 'Set', 10 => 'Ott', 11 => 'Nov', 12 => 'Dic',
        ];

        $labels = [];
        $fatturatoData = [];
        $incassatoData = [];
        $proformaData = [];
        $spesoData = [];

        foreach ($mesi as $num => $nome) {
            $labels[] = $nome;
            $fatturatoData[] = $fatturato[$num] ?? 0;
            $incassatoData[] = $incassato[$num] ?? 0;
            $proformaData[] = $proforma[$num] ?? 0;
            $spesoData[] = $speso[$num] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Fatturato',
                    'data' => $fatturatoData,
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#3b82f6',
                    'borderWidth' => 0,
                ],
                [
                    'label' => 'Incassato',
                    'data' => $incassatoData,
                    'backgroundColor' => '#22c55e',
                    'borderColor' => '#22c55e',
                    'borderWidth' => 0,
                ],
                [
                    'label' => 'Proforma',
                    'data' => $proformaData,
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#f59e0b',
                    'borderWidth' => 0,
                ],
                [
                    'label' => 'Speso',
                    'data' => $spesoData,
                    'backgroundColor' => '#ef4444',
                    'borderColor' => '#ef4444',
                    'borderWidth' => 0,
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
