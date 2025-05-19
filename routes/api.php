<?php
use App\Http\Controllers\Api\RegistroAsistenciaController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::post('/asistencia/registrar', [RegistroAsistenciaController::class, 'registrar']);

    Route::get('/prueba-api', function () {
        return response()->json(['ok' => true]);
    });
});