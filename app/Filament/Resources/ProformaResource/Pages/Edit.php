<?php

namespace App\Filament\Resources\ProformaResource\Pages;

use App\Filament\Resources\ProformaResource;
use App\Models\Customer;
use App\Models\Invoice;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = ProformaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),

            Action::make('back')
                ->label('Indietro')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => static::getResource()::getUrl('index')),

            Action::make('stampa')
                ->label('Stampa PDF')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn () => route('proforma.print', $this->record))
                ->openUrlInNewTab(),

            Action::make('crea_fattura')
                ->label('Crea fattura')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {

                    $proforma = $this->record;

                    $invoice = new Invoice();

                    $data = collect($proforma->toArray())
                        ->except([
                            'id',
                            'numero_documento',
                            'progressivo_sdi',
                            'created_at',
                            'updated_at',
                        ])
                        ->toArray();

                    $invoice->fill($data);

                    $invoice->numero_documento = Invoice::getNextNumeroDocumento();
                    $invoice->progressivo_sdi = Invoice::getNextProgressivoSdi();
                    $invoice->tipo_documento = Invoice::TYPE_DOC;

                    $invoice->pagato = 0;
                    $invoice->data_pagamento = null;

                    $invoice->save();

                    // segna proforma come fatturata
                    $proforma->fatturato = 1;
                    $proforma->save();

                    return redirect()->route('filament.admin.resources.invoices.edit', $invoice);
                }),

            Action::make('new')
                ->label('Nuovo')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->url(fn () => static::getResource()::getUrl('create')),

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
