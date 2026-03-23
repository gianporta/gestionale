<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: white;
            font-size: 13px;
        }

        .page {
            width: 210mm;
            height: 297mm;
            margin: auto;
            position: relative;
            padding: 20mm;
            padding-top: 5mm;
            box-sizing: border-box;
        }

        .header img {
            width: 280px;
        }

        .header hr {
            margin-top: 10px;
            border: 0;
            border-top: 1px solid #eee;
        }

        .footer {
            position: absolute;
            bottom: 10mm;
            left: 20mm;
            right: 20mm;
            text-align: center;
            font-size: 12px;
        }

        .footer-line {
            border-top: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .row {
            width: 100%;
            margin-top: 20px;
        }

        .row table {
            width: 100%;
            border-collapse: collapse;
        }

        .row td {
            width: 33%;
            vertical-align: top;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .table th {
            text-align: left;
            border-bottom: 1px solid #ddd;
            padding: 8px;
        }

        .table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .totals {
            width: 40%;
            margin-left: auto;
            margin-top: 20px;
        }

        .totals td {
            padding: 6px;
        }

        .totals tr td:last-child {
            text-align: right;
        }

        .highlight {
            background: #f5f5f5;
            padding: 10px;
            margin-top: 15px;
        }

        @page {
            size: A4;
            margin: 0;
        }
    </style>
</head>

<body>

@php
$user = auth()->user();
@endphp

<div class="page">

    {{-- HEADER --}}
    <div class="header">
        <img src="{{ asset('images/logo-doc.svg') }}">
        <hr>
    </div>

    {{-- CONTENUTO --}}
    @yield('content')

    {{-- FOOTER --}}
    <div class="footer">
        <div class="footer-line"></div>
        <div>
            | {{ $user->site }} | P.IVA {{ $user->p_iva }} | C.F. {{ $user->cf }} |<br>
            | {{ $user->email }} |
        </div>
    </div>

</div>

@yield('scripts')

</body>
</html>
