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
        $response = Http::withToken($this->token)
            ->post("https://graph.facebook.com/{$this->version}/{$this->phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $data['telefono'],
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
                            ]
                        ]
                    ]
                ]
            ]);

        if ($response->successful()) {
            return true;
        }

        Log::error('Error al enviar mensaje WhatsApp', ['response' => $response->json()]);
        return false;
    }
}
