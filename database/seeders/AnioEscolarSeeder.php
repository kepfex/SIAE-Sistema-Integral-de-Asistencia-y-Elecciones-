<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnioEscolarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anioActual = now()->year;

        DB::table('anio_escolars')->insert([
            'nombre' => (string) $anioActual,
            'estado' => 'activo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
