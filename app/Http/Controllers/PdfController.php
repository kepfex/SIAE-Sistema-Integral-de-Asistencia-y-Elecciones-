<?php

namespace App\Http\Controllers;

use App\Helpers\BrowsershotHelper;
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

        // USANDO SPATIE BROWSERSHOT
        // Renderiza el contenido HTML de Blade a string
        $html = View::make('pdf.carnetqr', compact('matriculas'))->render();

        $pdfContent = BrowsershotHelper::fromHtml($html)
            ->format('A4')
            ->margins(20, 5, 20, 5)
            ->pdf();

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="carnets.pdf"');
    }
}
