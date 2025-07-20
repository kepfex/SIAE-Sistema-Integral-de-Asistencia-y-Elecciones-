<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected string $token;
    protected string $phoneNumberId;
    protected string $version;

    public function __construct()
    {
        $this->token = config('services.whatsapp.token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->version = 'v22.0'; // versión de la API
    }

    public function enviarMensajeAsistencia(array $data): bool
    {
        $numeroFormateado = $this->formatearNumero($data['telefono']);

        if (!$numeroFormateado) {
            Log::warning("Número inválido para WhatsApp: {$data['telefono']}");
            return false;
        }

        $articulo = $data['genero'] === 'Hombre' ? 'el' : 'la';

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $numeroFormateado,
            'type' => 'template',
            'template' => [
                'name' => 'control_de_asistencia_acp',
                'language' => ['code' => 'es_PE'],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $data['estudiante']],
                            ['type' => 'text', 'text' => $data['grado']],
                            ['type' => 'text', 'text' => $data['seccion']],
                            ['type' => 'text', 'text' => $data['tipo']], // Entrada o Salida
                            ['type' => 'text', 'text' => $data['fecha']],
                            ['type' => 'text', 'text' => $data['hora']],
                            ['type' => 'text', 'text' => $articulo],
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withToken($this->token)
                ->post("https://graph.facebook.com/{$this->version}/{$this->phoneNumberId}/messages", $payload);

            Log::info('Mensaje enviado: ', [
                'to' => $payload,
                'response' => $response->json(),
            ]);
            if ($response->successful()) {
                return true;
            }

            // Log::info($payload); return true;
            Log::error('Error al enviar mensaje WhatsApp', [
                'to' => $numeroFormateado,
                'response' => $response->json(),
            ]);
        } catch (\Throwable $e) {
            Log::error("Excepción al enviar mensaje WhatsApp", [
                'to' => $numeroFormateado,
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }

    /**
     * Formatea el número a formato internacional (ej. +51987654321).
     * Solo aplica a números de Perú de 9 dígitos.
     */
    protected function formatearNumero(string $telefono): ?string
    {
        // Elimina todo excepto números
        $numero = preg_replace('/\D/', '', $telefono);

        // Si ya viene con +51 (ej. 51987654321), lo dejamos
        if (str_starts_with($numero, '51') && strlen($numero) === 11) {
            return '+' . $numero;
        }

        // Si es un número peruano de 9 dígitos (sin 51), añadimos +51
        if (strlen($numero) === 9) {
            return '+51' . $numero;
        }

        // Número inválido
        return null;
    }
}
