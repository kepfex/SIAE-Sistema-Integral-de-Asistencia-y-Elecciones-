<?php

use App\Http\Controllers\Api\RegistroAsistenciaController;
use App\Http\Controllers\Api\WhatsappController;
use Illuminate\Support\Facades\Route;

Route::post('/asistencia/registrar', [RegistroAsistenciaController::class, 'registrar']);

Route::post('/enviar-whatsapp', [WhatsappController::class, 'enviar']);
