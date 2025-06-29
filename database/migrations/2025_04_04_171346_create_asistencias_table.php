<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora');
            $table->enum('estado', ['P', 'F', 'T', 'J', 'U'])->comment('P: Puntual, F: Faltó, T: Tardanza, J: Falta Justificada, U: Tardanza Justificada');
            $table->foreignId('matricula_id')->constrained();
            $table->foreignId('anio_escolar_id')->constrained();
            $table->foreignId('grado_id')->constrained();
            $table->foreignId('seccion_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
