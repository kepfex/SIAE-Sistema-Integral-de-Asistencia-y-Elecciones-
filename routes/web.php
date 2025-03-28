<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pdf/generarte/carnet-qr/{records}', [PdfController::class, 'carnetQr'])->name('pdf.carnetqr');
