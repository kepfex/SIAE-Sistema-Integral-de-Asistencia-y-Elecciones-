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
        Schema::create('auxiliar_grado_seccion_anio', function (Blueprint $table) {
            $table->id();
            // $table->unique(['user_id', 'grado_id', 'seccion_id', 'anio_escolar_id']);
            $table->foreignId('user_id')->constrained();
            $table->foreignId('grado_id')->constrained();
            $table->foreignId('seccion_id')->constrained();
            $table->foreignId('anio_escolar_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auxiliar_grado_seccion_anio');
    }
};
