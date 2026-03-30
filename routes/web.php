<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
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
Route::get('/quote/{invoice}/print', [InvoiceController::class, 'print'])->name('quote.print');
Route::get('/quote/{invoice}/xml', [InvoiceXmlController::class, 'generate'])->name('quote.xml');
/*quote*/
