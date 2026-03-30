<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ProformaController;
use App\Http\Controllers\InvoiceXmlController;
Route::get('/', function () {
    return redirect('/admin');
});
Route::get('/carta-intestata', function () {
    return view('print.carta-intestata');
})->name('print.carta-intestata');

/*invoice*/
Route::get('/invoice/{invoice}/print', [InvoiceController::class, 'print'])->name('invoice.print');
Route::get('/invoice/{invoice}/xml', [InvoiceXmlController::class, 'generate'])->name('invoice.xml');
/*invoice*/
/*quote*/
Route::get('/quote/{quote}/print', [QuoteController::class, 'print'])->name('quote.print');
/*quote*/
/*proforma*/
Route::get('/proforma/{proforma}/print', [ProformaController::class, 'print'])->name('proforma.print');
/*proforma*/
