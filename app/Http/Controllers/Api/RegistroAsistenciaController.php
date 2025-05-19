<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\AnioEscolar;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegistroAsistenciaController extends Controller
{
    public function registrar(Request $request)
    {
        $codigoQR = $request->input('qrAlumno');

        Log::info("Intentando registrar asistencia con código: $codigoQR");

        if (!$codigoQR) {
            return response()->json([
                'success' => false,
                'message' => 'Código QR no proporcionado.'
            ], 400);
        }

        $alumno = Alumno::where('codigo_qr', $codigoQR)->first();

        if (!$alumno) {
            return response()->json([
                'success' => false,
                'message' => 'Código QR no corresponde a ningún estudiante.'
            ], 404);
        }

        // Obtener el último año escolar registrado
        $ultimoAnioEscolar = AnioEscolar::latest('nombre')->first();

        if ($ultimoAnioEscolar->estado !== "activo") {
            return response()->json([
                'success' => false,
                'message' => 'El año escolar ' . $ultimoAnioEscolar->nombre . ' está CERRADO o no existe.'
            ], 404);
        }

        // Validar que el alumno esta matriculado en el año actual
        $matricula = $alumno->matriculas()
            ->where('anio_escolar_id', $ultimoAnioEscolar->id)
            ->with(['grado', 'seccion'])
            ->first();
        if (!$matricula) {
            return response()->json([
                'success' => false,
                'message' => 'El estudiante no está matriculado en el año escolar actual.'
            ], 404);
        }

        // Validar si ya tiene asistencia hoy
        $yaRegistrada = Asistencia::where('matricula_id', $matricula->id)
            ->where('fecha', now()->toDateString())
            ->exists();

        if ($yaRegistrada) {
            return response()->json([
                'success' => false,
                'message' => 'Ya se registró tu asistencia para hoy.'
            ], 409);
        }

        // Registrar la asistencia
        $asistencia = Asistencia::create([
            'fecha' => now()->toDateString(),
            'hora' => now()->format('H:i:s'),
            'estado' => 'A', // Asistió
            'matricula_id' => $matricula->id,
            'anio_escolar_id' => $ultimoAnioEscolar->id,
            'grado_id' => $matricula->grado_id,
            'seccion_id' => $matricula->seccion_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => "¡Entrada Registrada Correctamente!",
            'alumno' => [
                'nombres' => $alumno->nombres,
                'apellido_paterno' => $alumno->apellido_paterno,
                'apellido_materno' => $alumno->apellido_materno,
                'foto' => $alumno->imagen_url,
                'grado' => $matricula->grado->nombre,
                'seccion' => $matricula->seccion->nombre,
            ]
        ], 200);
    }
}
