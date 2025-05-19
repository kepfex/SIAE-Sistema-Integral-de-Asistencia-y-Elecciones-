<?php

namespace App\Livewire\Asistencia;

use App\Models\Alumno;
use App\Models\AnioEscolar;
use App\Models\Asistencia;
use App\Models\Matricula;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class RegistroAsistencia extends Component
{

    public $asistencias = [];
    public $ultimoAnioEscolar;
    public $asistenciasDelDia = [];
    protected $listeners = ['asistencia-registrada' => 'actualizarAsistencias'];

    public function mount()
    {
        // Obtener el último año escolar registrado al iniciar el componente
        $this->ultimoAnioEscolar = AnioEscolar::latest('nombre')->first();
        $this->cargarAsistenciasDelDia();
        // Log::info($this->asistenciasDelDia);
    }

    public function cargarAsistenciasDelDia() {
        $hoy = now()->toDateString();

        $this->asistenciasDelDia = Asistencia::with(['matricula.alumno', 'matricula.grado', 'matricula.seccion'])
            ->where('fecha', $hoy)
            ->orderByDesc('hora')
            ->get();
    }

    public function actualizarAsistencias() {
        $this->cargarAsistenciasDelDia();
    }


    // public function registrarAsistencia()
    // {

    //     $fecha = Carbon::now()->format('Y-m-d');
    //     $hora = Carbon::now()->format('H:i:s');

    //  }

    public function render()
    {
        return view('livewire.asistencia.registro-asistencia');
    }
}
