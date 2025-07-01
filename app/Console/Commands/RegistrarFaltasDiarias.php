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
        $this->info('Iniciando el proceso optimizado para marcar ausencias generales...');
        Log::info('Scheduler general ejecutado a las ' . now());

        $today = Carbon::today();

        // 1. Validar que sea un día laboral (Lunes a Viernes).
        if ($today->isWeekend()) {
            $this->info('Hoy es fin de semana. No se procesan asistencias.');
            return Command::SUCCESS;
        }

        // 2. Obtener el año escolar activo.
        $activeYear = AnioEscolar::where('estado', 'activo')->latest('nombre')->first(); // 'activo' en lugar de 'estado' según tus modelos

        if (!$activeYear) {
            $this->error("No se encontró un año escolar activo. El proceso se detiene.");
            Log::error('Error en el scheduler: No se encontró un año escolar activo.');
            return Command::FAILURE;
        }
        
        $this->info("Procesando para el año escolar activo: {$activeYear->nombre}");

        // 3. OBTENER EN UNA SOLA CONSULTA todas las matrículas del año activo
        // que NO tienen un registro de asistencia para la fecha de hoy.
        // Esta es la optimización clave que elimina el bucle y las N+1 consultas.
        $absentEnrollments = Matricula::where('anio_escolar_id', $activeYear->id)
            ->whereDoesntHave('asistencias', function ($query) use ($today) {
                $query->whereDate('fecha', $today->toDateString());
            })
            ->get();

        // 4. Si no hay alumnos ausentes, terminar el proceso.
        if ($absentEnrollments->isEmpty()) {
            $this->info('Todos los alumnos ya tienen un registro de asistencia. No hay nada que hacer.');
            return Command::SUCCESS;
        }

        // 5. Preparar un array con todas las faltas que se van a insertar.
        $absencesToInsert = $absentEnrollments->map(function ($enrollment) use ($today, $activeYear) {
            return [
                'fecha' => $today->toDateString(),
                'hora' => '16:15:00', // Hora fija de cierre
                'estado' => 'F',
                'matricula_id' => $enrollment->id,
                'anio_escolar_id' => $activeYear->id,
                'grado_id' => $enrollment->grado_id,
                'seccion_id' => $enrollment->seccion_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->all();

        // 6. INSERTAR TODOS LOS REGISTROS EN UNA SOLA CONSULTA A LA BASE DE DATOS.
        Asistencia::insert($absencesToInsert);

        $count = count($absencesToInsert);
        $this->info("¡Éxito! Se han registrado {$count} alumnos como ausentes para el día " . $today->toDateString());
        Log::info("Faltas automáticas registradas: {$count} estudiantes - " . $today->toDateString());

        return Command::SUCCESS;
    }
}
