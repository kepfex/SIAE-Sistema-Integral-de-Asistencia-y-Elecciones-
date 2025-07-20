<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{
    protected WhatsappService $whatsapp;

    public function __construct(WhatsappService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    public function enviar(Request $request)
    {
        $validated = $request->validate([
            'telefono' => 'required|string',
            'estudiante' => 'required|string',
            'genero' => 'required|string',
            'grado' => 'required|string',
            'seccion' => 'required|string',
            'tipo' => 'required|string',
            'fecha' => 'required|string',
            'hora' => 'required|string',
        ]);

        \App\Models\WhatsappMensaje::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Mensaje agregado a la cola.',
            'estudiante' => $validated,
        ]);
        

        // try {
        //     $enviado = $this->whatsapp->enviarMensajeAsistencia($validated);

        //     if ($enviado) {
        //         return response()->json([
        //             'success' => true,
        //             'message' => 'Mensaje enviado correctamente.'
        //         ]);
        //     }

        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Error al enviar el mensaje de WhatsApp.',
        //     ], 500);
        // } catch (\Throwable $e) {
        //     Log::error('Excepción al enviar WhatsApp', ['error' => $e->getMessage()]);
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Ocurrió un error inesperado.',
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }
}
