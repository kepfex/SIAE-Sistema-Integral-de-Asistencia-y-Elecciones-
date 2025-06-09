<?php

use App\Helpers\BrowsershotHelper;
use App\Http\Controllers\Api\RegistroAsistenciaController;
use App\Http\Controllers\PdfController;
use App\Livewire\Asistencia\RegistroAsistencia;
use Illuminate\Http\Request;
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

    // return view('pdf/carnetqr');
    // $pdf = Browsershot::html('<h1>Hola Mundo <br> Soy Kevin Espinoza - DEV</h1>')
    //     ->setOption('args', ['--disable-web-security'])
    //     ->ignoreHttpsErrors()
    //     ->noSandbox()
    //     ->setCustomTempPath('/home/www-data/browsershot-html')
    //     ->addChromiumArguments([
    //         'lang' => "es-PE",
    //         'hide-scrollbars',
    //         'enable-font-antialiasing',
    //         'force-device-scale-factor' => 1,
    //         'font-render-hinting' => 'none',
    //         'user-data-dir' => '/home/www-data/user-data',
    //         'disk-cache-dir' => '/home/www-data/user-data/Default/Cache',
    //     ])
    //     ->setChromePath('/home/www-data/.cache/puppeteer/chrome/linux-136.0.7103.94/chrome-linux64/chrome')
    //     ->newHeadless()
    //     ->showBackground()
    //     ->pdf();

    $pdf = BrowsershotHelper::fromHtml("<h1>Hola Mundo <br> Soy Kevin Espinoza - DEV</h1>")
        ->pdf();

    return response($pdf, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="example.pdf"'
    ]);
});


Route::post('/seleccionar-anio-escolar', function (Request $request) {
    session(['anio_escolar_id' => $request->anio_escolar_id]);
    return back();
})->name('anio-escolar.seleccionar');

Route::get('/pdf/generarte/carnet-qr/{records}', [PdfController::class, 'carnetQr'])->name('pdf.carnetqr');

Route::get('/asistencia', RegistroAsistencia::class)->name('asistencia');
