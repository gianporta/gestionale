@extends('documents.layout')

@section('title', 'Fattura ' . $invoice->numero_documento)

@section('content')

@php
$user = auth()->user();
@endphp

{{-- HEADER DATI --}}
<table style="width:100%; border-collapse: collapse;margin-top: 40px">
    <tr>

        {{-- SINISTRA --}}
        <td style="width:33%; vertical-align:top;">
            <div style="font-weight:bold;">Informazioni Fattura</div>
            <span style="font-weight:bold;">Numero:</span> {{ \Carbon\Carbon::parse($invoice->data_documento)->format('Y') . '-' . $invoice->numero_documento }}<br>
            <span style="font-weight:bold;">Data:</span> {{ \Carbon\Carbon::parse($invoice->data_documento)->format('d/m/Y') }}<br><br>

            <div style="font-weight:bold;">Informazioni Pagamento</div>
            <span style="font-weight:bold;">Banca:</span> {{ $invoice->banca }}<br>
            <span style="font-weight:bold;">Iban:</span> {{ $invoice->iban }}<br>
            <span style="font-weight:bold;">Intestatario:</span> {{ $invoice->intestatario_conto }}
        </td>

        {{-- CENTRO --}}
        <td style="width:33%; vertical-align:top;">
            <span style="font-weight:bold;">Rag. Soc.</span> {{ $user->name }}<br>
            <span style="font-weight:bold;">P.IVA:</span> {{ $user->p_iva }}<br>
            <span style="font-weight:bold;">C.F.:</span> {{ $user->cf }}<br>
            <span style="font-weight:bold;">Indirizzo:</span> {{ $user->indirizzo }}
        </td>

        {{-- DESTRA --}}
        <td style="width:33%; vertical-align:top; text-align:right;">
            <div style="font-weight:bold;">Indirizzo di fatturazione</div>
            <span style="font-weight:bold;">Spett.le</span> {{ $invoice->cliente_ragione_sociale }}<br>
            <span style="font-weight:bold;">Partita Iva:</span> {{ $invoice->cliente_partita_iva }}<br>
            <span style="font-weight:bold;">Codice Fiscale:</span> {{ $invoice->cliente_codice_fiscale }}<br>
            <span style="font-weight:bold;">Indirizzo:</span> {{ $invoice->cliente_indirizzo }}<br>
            {{ $invoice->cliente_cap }} - {{ $invoice->cliente_citta }} ({{ $invoice->cliente_provincia }})
        </td>

    </tr>
</table>

{{-- RIGHE --}}
<table style="width:100%; border-collapse: collapse; margin-top:30px;">
    <thead>
    <tr>
        <th style="text-align:left; border-bottom:2px solid #ddd; padding:8px;">Lavorazione</th>
        <th style="text-align:left; border-bottom:2px solid #ddd; padding:8px;">Ore</th>
        <th style="text-align:left; border-bottom:2px solid #ddd; padding:8px;">Costo</th>
        <th style="text-align:right; border-bottom:2px solid #ddd; padding:8px;">Imponibile</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->content as $row)
    <tr>
        <td style="padding:8px; border-bottom:1px solid #eee;">
            {{ \App\Models\Job::find($row['descrizione'])?->descrizione ?? $row['descrizione'] }}
        </td>
        <td style="padding:8px; border-bottom:1px solid #eee;">
            {{ $row['ore'] }}
        </td>
        <td style="padding:8px; border-bottom:1px solid #eee;">
            € {{ number_format($row['costo'], 2, ',', '.') }}
        </td>
        <td style="text-align:right; padding:8px; border-bottom:1px solid #eee;">
            € {{ number_format($row['imponibile'], 2, ',', '.') }}
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
{{-- NOTE / SCADENZA --}}
<div style="margin-top:250px; width:60%; float:left; font-size:13px;">

    @if($invoice->data_scadenza)
    <div style="margin-bottom:10px;">
        La fattura è da saldare entro il
        <b>{{ \Carbon\Carbon::parse($invoice->data_scadenza)->format('d/m/Y') }}</b>
    </div>
    @endif

    @if($invoice->frase_in_calce)
    <div>
        {{ $invoice->frase_in_calce }}
    </div>
    @endif

</div>
{{-- TOTALI --}}
<table style="width:40%; margin-left:auto; margin-top:40px; border-collapse:collapse;">
    <tr style="border-bottom:1px solid #ccc;height:30px;">
        <td style="font-weight:bold;">IMPONIBILE</td>
        <td style="text-align:right;">€ {{ number_format($invoice->imponibile, 2, ',', '.') }}</td>
    </tr>
    <tr style="border-bottom:1px solid #ccc;height:30px;">
        <td style="font-weight:bold;">Rivalsa Inps</td>
        <td style="text-align:right;">€ {{ number_format($invoice->contributo_inps, 2, ',', '.') }}</td>
    </tr>
    <tr style="border-bottom:1px solid #ccc;height:30px;">
        <td style="font-weight:bold;">Iva</td>
        <td style="text-align:right;">€ {{ number_format($invoice->iva, 2, ',', '.') }}</td>
    </tr>
    <tr style="border-bottom:1px solid #ccc;height:30px;">
        <td style="font-weight:bold;">Ritenuta d'acconto</td>
        <td style="text-align:right;">€ {{ number_format($invoice->ritenuta_di_acconto, 2, ',', '.') }}</td>
    </tr>
    <tr style="border-top:2px solid #000;height:30px;">
        <td style="font-weight:bold;">NETTO A PAGARE</td>
        <td style="text-align:right; font-weight:bold;">€ {{ number_format($invoice->netto_a_pagare, 2, ',', '.') }}</td>
    </tr>
</table>

@endsection

@section('scripts')
<script>
    window.onload = function () {
        window.print();
    }
</script>
@endsection
