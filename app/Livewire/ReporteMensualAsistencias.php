<?php

namespace App\Livewire;

use App\Models\Grado;
use App\Models\Matricula;
use App\Models\Seccion;
use Livewire\Component;
use Illuminate\Support\Carbon;

class ReporteMensualAsistencias extends Component
{

    public $anioEscolarId;
    public $gradoId;
    public $seccionId;
    public $mes;
    public $diasDelMes = [];
    public $tabla = [];

    public function mount()
    {
        $this->anioEscolarId = session('anio_escolar_id');
        $this->mes = now()->month;
    }

    public function updated($field)
    {
        if (in_array($field, ['gradoId', 'seccionId', 'mes'])) {
            $this->generarReporte();
        }
    }

    public function generarReporte()
    {
        if (!$this->anioEscolarId || !$this->gradoId || !$this->seccionId || !$this->mes) return;
        
        $anio = now()->year;
        $mes = $this->mes;
        
        $this->diasDelMes = $this->generarDiasDelMes($anio, $mes);
        
        $matriculas = Matricula::with(['alumno', 'asistencias' => function ($query) use ($anio, $mes) {
            $query->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes);
        }])
        ->where('anio_escolar_id', $this->anioEscolarId)
        ->where('grado_id', $this->gradoId)
        ->where('seccion_id', $this->seccionId)
        ->get();

        $this->tabla = $matriculas->map(function ($matricula) {
            $datos = [
                'alumno' => $matricula->alumno->nombres . ' ' . $matricula->alumno->apellido_paterno . ' ' . $matricula->alumno->apellido_materno,
                'asistencias' => [],
            ];

            foreach ($this->diasDelMes as $dia) {
                $registro = $matricula->asistencias->first(function ($asistencia) use ($dia) {
                    return \Illuminate\Support\Carbon::parse($asistencia['fecha'])->toDateString() === $dia['fecha'];
                });
                $datos['asistencias'][$dia['numero']] = $registro->estado ?? '';
            }

            return $datos;
        });
    }

    public function generarDiasDelMes($anio, $mes): array
    {
        $fechaInicio = Carbon::createFromDate($anio, $mes, 1);
        $dias = [];

        while ($fechaInicio->month === $mes) {
            $dias[] = [
                'numero' => $fechaInicio->day,
                'nombre_corto' => $fechaInicio->translatedFormat('D'),
                'fecha' => $fechaInicio->format('Y-m-d'),
            ];
            $fechaInicio->addDay();
        }

        return $dias;
    }

    public function render()
    {
        return view('livewire.reporte-mensual-asistencias', [
            'grados' => Grado::all(),
            'secciones' => Seccion::all(),
        ]);
    }
}
