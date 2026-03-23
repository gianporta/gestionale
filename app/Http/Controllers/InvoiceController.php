<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    public function print(Invoice $invoice)
    {
        return view('invoice.print', compact('invoice'));
    }

    public function xml(Invoice $invoice)
    {
        $xml = view('invoice.xml', compact('invoice'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'text/xml');
    }
}
