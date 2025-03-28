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
        $anioEscolar = ['2025'];
        foreach ($anioEscolar as $anio) {
            DB::table('anio_escolars')->insert(['nombre' => $anio, 'created_at' => now(), 'updated_at' => now()]);
        }
    }
}
