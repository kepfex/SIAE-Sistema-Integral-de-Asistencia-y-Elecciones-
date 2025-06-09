<?php

namespace App\Listeners;

use App\Models\AnioEscolar;
use Illuminate\Auth\Events\Authenticated;

class StoreAnioEscolarInSession
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Authenticated $event): void
    {
        if (!session()->has('anio_escolar_id')) {
            $ultimo = AnioEscolar::where('estado', 'activo')
                ->orderByDesc('nombre')
                ->first();

            if ($ultimo) {
                session(['anio_escolar_id' => $ultimo->id]);
            }
        }
    }
}
