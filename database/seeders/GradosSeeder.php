<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grados = ['Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto'];
        foreach ($grados as $grado) {
            DB::table('grados')->insert(['nombre' => $grado, 'created_at' => now(), 'updated_at' => now()]);
        }
    }
}
