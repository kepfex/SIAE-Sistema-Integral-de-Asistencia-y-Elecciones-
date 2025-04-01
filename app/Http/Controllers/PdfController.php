<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;

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

        // Pasar los datos a la vista del PDF
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.carnetqr', compact('matriculas'));

        return $pdf->stream('carnets_reporte.pdf'); // Mostrar el PDF en el navegador
        // return view('pdf.carnetqr', compact('matriculas'));
    }
}
