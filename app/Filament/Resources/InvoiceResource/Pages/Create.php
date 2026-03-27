<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Customer;
class Create extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Action::make('save_top')
                ->label('Salva')
                ->color('primary')
                ->action(function () {
                    $this->save();
                }),
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
