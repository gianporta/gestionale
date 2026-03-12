<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});
Route::get('/carta-intestata', function () {
    return view('print.carta-intestata');
})->name('print.carta-intestata');
