<?php

use App\Http\Controllers\Api\RegistroAsistenciaController;
use App\Http\Controllers\PdfController;
use App\Livewire\Asistencia\RegistroAsistencia;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Spatie\Browsershot\Browsershot;

Route::get('/', function () {

    // Browsershot::html("<h1>Laravel pdf Tutorial</h1>")
    //     ->pdf('example.pdf');
    // return new Response($pdf, 200, [
    //     'Content-Type' => 'application/pdf',
    //     'Content-Disposition' => 'attachment; filename="pruebaDescarga.pdf"',
    //     'Content-Length' => strlen($pdf)
    // ]);

    // return new Response($pdf, 200, [
    //     'Content-Type' => 'application/pdf',
    //     'Content-Disposition' => 'inline; filename="pruebaDescarga.pdf"',
    // ]);

    // $pdfContent = Browsershot::html("<h1>Laravel PDF Tutorial</h1>")
    //     ->setOption('args', ['--no-sandbox']) // importante si usas Linux sin GUI
    //     ->format('A4')
    //     ->pdf(); // genera contenido binario del PDF

    // return response($pdfContent)
    //     ->header('Content-Type', 'application/pdf')
    //     ->header('Content-Disposition', 'inline; filename="example.pdf"'); // muestra en el navegador

    return view('pdf/carnetqr');
});

Route::get('/pdf/generarte/carnet-qr/{records}', [PdfController::class, 'carnetQr'])->name('pdf.carnetqr');

Route::get('/asistencia', RegistroAsistencia::class)->name('asistencia');
