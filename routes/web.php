<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
Route::get('/', function () {
    return redirect('/admin');
});
Route::get('/carta-intestata', function () {
    return view('print.carta-intestata');
})->name('print.carta-intestata');


Route::get('/invoice/{invoice}/print', [InvoiceController::class, 'print'])->name('invoice.print');
Route::get('/invoice/{invoice}/xml', [InvoiceController::class, 'xml'])->name('invoice.xml');
