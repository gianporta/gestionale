<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Job;
use App\Models\CondizioniPagamento;
use App\Models\ModalitaPagamento;

class InvoiceXmlController extends Controller
{
    public function generate(Invoice $invoice)
    {
        $user = auth()->user();

        $versione = 'FPR12';
        $progressivo = $invoice->progressivo_sdi;

        $cliente = Customer::find($invoice->cliente);

        $fatt = new \SimpleXMLElement('<FatturaElettronica/>');
        $fatt->addAttribute('versione', $versione);
        $fatt->addAttribute('xmlns', 'http://ivaservizi.agenziaentrate.gov.it/docs/xsd/fatture/v1.2');

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */
        $header = $fatt->addChild('FatturaElettronicaHeader');
        $header->addAttribute('xmlns', '');

        $datiTrasm = $header->addChild('DatiTrasmissione');

        $idTrasm = $datiTrasm->addChild('IdTrasmittente');
        $idTrasm->addChild('IdPaese', $user->nazione);
        $idTrasm->addChild('IdCodice', $user->cf);

        $datiTrasm->addChild('ProgressivoInvio', (string)$progressivo);
        $datiTrasm->addChild('FormatoTrasmissione', $versione);

        $sdi = '0000000';

        if ($cliente && $cliente->sdi)
            $sdi = $cliente->sdi;

        if ($invoice->cliente_nazione !== 'IT')
            $sdi = 'XXXXXXX';

        $datiTrasm->addChild('CodiceDestinatario', $sdi);

        if ($cliente && $cliente->pec)
            $datiTrasm->addChild('PECDestinatario', $cliente->pec);

        /*
        |--------------------------------------------------------------------------
        | PRESTATORE
        |--------------------------------------------------------------------------
        */
        $prestatore = $header->addChild('CedentePrestatore');

        $anag = $prestatore->addChild('DatiAnagrafici');

        $pi = $anag->addChild('IdFiscaleIVA');
        $pi->addChild('IdPaese', $user->nazione);
        $pi->addChild('IdCodice', substr($user->p_iva, 2));

        $anagDet = $anag->addChild('Anagrafica');
        $anagDet->addChild('Denominazione', $user->ragione_sociale);

        $anag->addChild('RegimeFiscale', 'RF01');

        $sede = $prestatore->addChild('Sede');
        $sede->addChild('Indirizzo', $user->indirizzo);
        $sede->addChild('CAP', $user->cap);
        $sede->addChild('Comune', $user->citta);
        $sede->addChild('Provincia', $user->provincia);
        $sede->addChild('Nazione', $user->nazione);

        $contatti = $prestatore->addChild('Contatti');
        $contatti->addChild('Telefono', $user->telefono);
        $contatti->addChild('Email', $user->email);

        /*
        |--------------------------------------------------------------------------
        | CLIENTE
        |--------------------------------------------------------------------------
        */
        $commitente = $header->addChild('CessionarioCommittente');

        $anag = $commitente->addChild('DatiAnagrafici');

        $pi = $anag->addChild('IdFiscaleIVA');
        $pi->addChild('IdPaese', $invoice->cliente_nazione);
        $pi->addChild('IdCodice', $invoice->cliente_partita_iva);

        $anagDet = $anag->addChild('Anagrafica');
        $anagDet->addChild('Denominazione', $invoice->cliente_ragione_sociale);

        $sede = $commitente->addChild('Sede');
        $sede->addChild('Indirizzo', $invoice->cliente_indirizzo);
        $sede->addChild('CAP', $invoice->cliente_cap);
        $sede->addChild('Comune', $invoice->cliente_citta);
        $sede->addChild('Provincia', $invoice->cliente_provincia);
        $sede->addChild('Nazione', $invoice->cliente_nazione);

        /*
        |--------------------------------------------------------------------------
        | BODY
        |--------------------------------------------------------------------------
        */
        $body = $fatt->addChild('FatturaElettronicaBody');
        $body->addAttribute('xmlns', '');

        $datiGen = $body->addChild('DatiGenerali');
        $datiDoc = $datiGen->addChild('DatiGeneraliDocumento');

        $tipoDocumento = $invoice->getTipoDocumento();

        $datiDoc->addChild('TipoDocumento', $tipoDocumento);
        $datiDoc->addChild('Divisa', $user->valuta_codice);
        $datiDoc->addChild('Data', $invoice->data_documento->format('Y-m-d'));
        $datiDoc->addChild('Numero', $invoice->data_documento->format('Y') . '-' . $invoice->numero_documento);

        /*
        |--------------------------------------------------------------------------
        | RITENUTA
        |--------------------------------------------------------------------------
        */
        $rit = $datiDoc->addChild('DatiRitenuta');
        $rit->addChild('TipoRitenuta', 'RT01');
        $rit->addChild('ImportoRitenuta', number_format($invoice->ritenuta_di_acconto, 2, '.', ''));
        $rit->addChild('AliquotaRitenuta', number_format($user->percentuale_ritenuta_di_acconto, 2, '.', ''));
        $rit->addChild('CausalePagamento', 'A');

        /*
        |--------------------------------------------------------------------------
        | INPS
        |--------------------------------------------------------------------------
        */
        $inps = $datiDoc->addChild('DatiCassaPrevidenziale');
        $inps->addChild('TipoCassa', 'TC22');
        $inps->addChild('AlCassa', number_format($user->percentuale_inps, 2, '.', ''));
        $inps->addChild('ImportoContributoCassa', number_format($invoice->contributo_inps, 2, '.', ''));
        $inps->addChild('AliquotaIVA', number_format($user->percentuale_iva, 2, '.', ''));

        /*
        |--------------------------------------------------------------------------
        | RIGHE
        |--------------------------------------------------------------------------
        */
        $datiServ = $body->addChild('DatiBeniServizi');

        foreach ($invoice->content as $index => $rowData) {
            $row = $datiServ->addChild('DettaglioLinee');

            $job = Job::find($rowData['descrizione']);

            $row->addChild('NumeroLinea', $index + 1);
            $row->addChild('Descrizione', $job?->descrizione ?? 'Attività');
            $row->addChild('Quantita', number_format($rowData['ore'], 2, '.', ''));
            $row->addChild('PrezzoUnitario', number_format($rowData['costo'], 2, '.', ''));
            $row->addChild('PrezzoTotale', number_format($rowData['imponibile'], 2, '.', ''));
            $row->addChild('AliquotaIVA', number_format($user->percentuale_iva, 2, '.', ''));
        }

        /*
        |--------------------------------------------------------------------------
        | RIEPILOGO
        |--------------------------------------------------------------------------
        */
        $datiRiep = $datiServ->addChild('DatiRiepilogo');
        $datiRiep->addChild('AliquotaIVA', number_format($user->percentuale_iva, 2, '.', ''));
        $datiRiep->addChild('ImponibileImporto', number_format($invoice->imponibile + $invoice->contributo_inps, 2, '.', ''));
        $datiRiep->addChild('Imposta', number_format($invoice->iva, 2, '.', ''));

        /*
        |--------------------------------------------------------------------------
        | PAGAMENTO
        |--------------------------------------------------------------------------
        */
        $condizione = CondizioniPagamento::find($invoice->condizioni_pagamento);
        $modalita = ModalitaPagamento::find($invoice->modalita_pagamento);

        $datiPag = $body->addChild('DatiPagamento');

        $datiPag->addChild('CondizioniPagamento', $condizione?->codice ?? 'TP02');

        $detPag = $datiPag->addChild('DettaglioPagamento');

        $detPag->addChild('ModalitaPagamento', $modalita?->codice ?? 'MP05');

        if ($invoice->data_scadenza)
            $detPag->addChild('DataScadenzaPagamento', $invoice->data_scadenza->format('Y-m-d'));

        $detPag->addChild('ImportoPagamento', number_format($invoice->netto_a_pagare, 2, '.', ''));

        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */
        $fileName = $user->p_iva . '_' . $progressivo . '.xml';
        $xmlContent = $fatt->asXML();

        return response($xmlContent, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
