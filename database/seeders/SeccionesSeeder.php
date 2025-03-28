<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeccionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $secciones = ['A', 'B', 'C', 'D', 'E', 'F'];
        foreach ($secciones as $seccion) {
            DB::table('seccions')->insert(['nombre' => $seccion, 'created_at' => now(), 'updated_at' => now()]);
        }
    }
}
