<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Carta Intestata</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: white;
        }

        .page{
            width:210mm;
            height:297mm;
            margin:auto;
            position:relative;
            padding:20mm;
            padding-top: 5mm;
            box-sizing:border-box;
        }

        .header img {
            width: 300px;
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
            line-height: 18px;
        }

        .footer-line {
            border-top: 1px solid #ddd;
            margin-bottom: 10px;
        }

        @media print {

            .fi-sidebar,
            .fi-topbar,
            .fi-header,
            .fi-breadcrumbs {
                display: none !important;
            }

            .fi-main {
                margin: 0 !important;
                padding: 0 !important;
            }

            body {
                background: white !important;
            }

        }

        @page {
            size: A4;
            margin: 0;
        }

    </style>

</head>

<body>

<div class="page">

    <div class="header">
        <img src="{{ asset('images/logo-doc.svg') }}">
        <hr>
    </div>

    <div class="footer">

        <div class="footer-line"></div>
        <div>
            | www.gianlucaporta.it | P.IVA 09492130969 | C.F. PRTGLC88R23G113A | Cel. 340/6077327 |<br>
            | E-mail gporta@gianlucaporta.it | Pec. gianlucaporta@pec.it |
        </div>
    </div>

</div>

<script>
    window.onload = function () {
        window.print();
    }
</script>

</body>
</html>
