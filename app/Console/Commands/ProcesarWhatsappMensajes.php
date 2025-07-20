<?php

namespace App\Console\Commands;

use App\Models\WhatsappMensaje;
use App\Services\WhatsappService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;

class ProcesarWhatsappMensajes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:procesar-cola';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa la cola de mensajes de WhatsApp pendientes.';

    /**
     * Execute the console command.
     */
    public function handle(WhatsappService $whatsapp)
    {
        $mensajes = WhatsappMensaje::where('estado', 'pendiente')->limit(10)->get();

        foreach ($mensajes as $mensaje) {
            if (!$this->internetDisponible()) {
                $this->warn('🚫 No hay conexión a internet. Inténtalo más tarde.');
                $this->error("Sin conexión a internet. Reintentando más tarde.");
                return 1;
            }

            if (!$this->esNumeroValido($mensaje->telefono)) {
                $mensaje->estado = 'fallido';
                $mensaje->save();
                $this->warn("Número inválido: {$mensaje->telefono}");
                continue;
            }

            $enviado = $whatsapp->enviarMensajeAsistencia($mensaje->toArray());

            if ($enviado) {
                $mensaje->estado = 'enviado';
                $mensaje->enviado_en = now();
                $mensaje->save();
                $this->info("✅ Mensaje enviado a: {$mensaje->telefono}");
            } else {
                $mensaje->estado = 'fallido';
                $mensaje->save();
                $this->error("❌ Falló el envío a: {$mensaje->telefono}");
            }
            sleep(3); // espera de 3 segundos
        }

        return 0;
    }
    private function internetDisponible(): bool
    {
        try {
            $response = Http::timeout(2)->get('https://google.com');

            return $response->successful();
        } catch (ConnectionException $e) {
            // Puedes loguear el error si deseas
            Log::warning('Sin conexión a Internet: ' . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            // Otro tipo de excepción (más raro)
            Log::error('Error desconocido al verificar Internet: ' . $e->getMessage());
            return false;
        }
    }

    private function esNumeroValido(string $numero): bool
    {
        return preg_match('/^9\d{8}$/', $numero); // básico para Perú (9 dígitos que empiezan en 9)
    }
}
