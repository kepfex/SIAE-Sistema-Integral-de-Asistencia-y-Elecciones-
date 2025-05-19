<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class PdfController extends Controller
{
    //
    public function carnetQr($records)
    {
        // Convertir "1,3" en [1,3]
        $ids = explode(',', $records);

        // Consultar las matrículas con la información del estudiante, grado y sección
        $matriculas = Matricula::with(['alumno', 'grado', 'seccion'])
            ->whereIn('id', $ids)
            ->get();

        // USANDO DOMPDF
        // // Pasar los datos a la vista del PDF
        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadView('pdf.carnetqr', compact('matriculas'));

        // return $pdf->stream('carnets_reporte.pdf'); // Mostrar el PDF en el navegador
        // // return view('pdf.carnetqr', compact('matriculas'));

        // USANDO SPATIE BROWSERSHOT
        // Renderiza el contenido HTML de Blade a string
        $html = View::make('pdf.carnetqr', compact('matriculas'))->render();

        // Genera el PDF con Browsershot
        $pdfContent = Browsershot::html($html)
            ->setOption('args', ['--no-sandbox']) // necesario si estás en un servidor Linux sin GUI
            ->format('A4')
            ->margins(10, 10, 10, 10)
            ->showBackground()
            ->pdf();

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="carnets.pdf"');
    }
}
