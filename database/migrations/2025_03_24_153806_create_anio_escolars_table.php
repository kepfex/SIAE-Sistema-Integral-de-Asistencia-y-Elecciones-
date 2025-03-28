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
        Schema::create('anio_escolars', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo'); // Estado del año escolar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anio_escolars');
    }
};
