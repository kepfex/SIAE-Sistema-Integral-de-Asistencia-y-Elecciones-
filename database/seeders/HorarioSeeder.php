<?php

namespace Database\Seeders;

use App\Models\Horario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HorarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Horario::truncate(); // Limpia la tabla antes de insertar
        
        Horario::create([
            'turno' => 'mañana',
            'hora_inicio' => '07:00:00',
            'hora_tolerancia' => '08:00:00',
            'hora_maxima' => '09:00:00',
        ]);
    }
}
