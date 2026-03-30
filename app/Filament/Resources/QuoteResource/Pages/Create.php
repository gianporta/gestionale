<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Customer;
class Create extends CreateRecord
{
    protected static string $resource = QuoteResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Indietro')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => static::getResource()::getUrl('index')),
            Action::make('save_top')
                ->label('Salva')
                ->color('primary')
                ->submit('create')
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['cliente'])) {
            $cliente = Customer::find($data['cliente']);

            if ($cliente) {
                $data['cliente_ragione_sociale'] = $cliente->ragione_sociale;
                $data['cliente_company_id'] = $cliente->company_id;
                $data['cliente_partita_iva'] = $cliente->partita_iva;
                $data['cliente_codice_fiscale'] = $cliente->codice_fiscale;
                $data['cliente_indirizzo'] = $cliente->indirizzo;
                $data['cliente_cap'] = $cliente->cap;
                $data['cliente_citta'] = $cliente->citta;
                $data['cliente_provincia'] = $cliente->provincia;
                $data['cliente_nazione'] = $cliente->nazione;
            }
        }

        return $data;
    }
}
