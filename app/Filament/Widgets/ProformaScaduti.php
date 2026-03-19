<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use App\Models\Proforma;

class ProformaScaduti extends TableWidget
{
    protected function getTableQuery(): Builder
    {
        return Proforma::query()
            ->selectRaw("
                documenti.cliente,
                customers.ragione_sociale,
                SUM(
                    CASE
                        WHEN stato_documentos.nome = 'parzialmente pagato' THEN (documenti.netto_a_pagare - COALESCE(documenti.pagato, 0))
                        ELSE documenti.netto_a_pagare
                    END
                ) as totale,
                MIN(documenti.id) as min_id
            ")
            ->join('customers', 'customers.id', '=', 'documenti.cliente')
            ->join('stato_documentos', 'stato_documentos.id', '=', 'documenti.stato_documento')
            ->whereIn('stato_documentos.nome', ['da pagare', 'parzialmente pagato'])
            ->groupBy('documenti.cliente', 'customers.ragione_sociale')
            ->reorder('min_id');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('ragione_sociale')->label('Cliente'),
            TextColumn::make('totale')->money('EUR')->label('Da incassare'),
        ];
    }

    public function getTableRecordKey($record): string
    {
        return (string) $record->cliente;
    }
}
