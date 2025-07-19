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
        Schema::create('whatsapp_mensajes', function (Blueprint $table) {
            $table->id();
            $table->string('telefono');
            $table->string('estudiante');
            $table->string('genero');
            $table->string('grado');
            $table->string('seccion');
            $table->string('tipo'); // Entrada o Salida
            $table->string('fecha');
            $table->string('hora');
            $table->enum('estado', ['pendiente', 'enviado', 'fallido'])->default('pendiente');
            $table->timestamp('enviado_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_mensajes');
    }
};
