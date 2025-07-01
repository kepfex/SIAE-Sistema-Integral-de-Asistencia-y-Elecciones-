<?php

namespace App\Console\Commands;

use App\Models\AnioEscolar;
use App\Models\Asistencia;
use App\Models\Matricula;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RegistrarFaltasDiarias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asistencia:marcar-ausentes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registra como ausentes (F) a los alumnos sin asistencia en el día actual.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando el proceso para marcar ausencias...');

        $hoy = Carbon::today();

        if ($hoy->isSaturday() || $hoy->isSunday()) {
            $this->info('Hoy es fin de semana. No se procesan asistencias.');
            return;
        }
        $anioEscolarId = session('anio_escolar_id') ?? AnioEscolar::where('estado', 'activo')->latest('nombre')->value('id');

        if (!$anioEscolarId) {
            $this->error("No se encontró un año escolar activo.");
            return Command::FAILURE;
        }

        $matriculas = Matricula::with('alumno')
            ->where('anio_escolar_id', $anioEscolarId)
            ->whereHas('grado', fn($q) => $q->where('nombre', 'SEGUNDO'))
            ->whereHas('seccion', fn($q) => $q->where('nombre', 'A'))
            ->get();

        $faltasRegistradas = 0;

        foreach ($matriculas as $matricula) {
            $yaTieneAsistencia = Asistencia::where('matricula_id', $matricula->id)
                ->where('fecha', $hoy)
                ->exists();

            if (!$yaTieneAsistencia) {
                Asistencia::create([
                    'fecha' => $hoy,
                    'hora' => now()->format('H:i:s'),
                    'estado' => 'F',
                    'matricula_id' => $matricula->id,
                    'anio_escolar_id' => $anioEscolarId,
                    'grado_id' => $matricula->grado_id,
                    'seccion_id' => $matricula->seccion_id,
                ]);
                $faltasRegistradas++;
            }
        }

        $this->info("Faltas registradas: {$faltasRegistradas}");
        return Command::SUCCESS;
    
    }
}
