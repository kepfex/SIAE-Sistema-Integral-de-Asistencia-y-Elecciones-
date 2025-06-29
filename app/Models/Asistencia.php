<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'fecha',
        'hora',
        'estado',
        'matricula_id',
        'anio_escolar_id',
        'grado_id',
        'seccion_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relación con Matrícula (Cada asistencia pertenece a una matrícula)
    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    // Relación con Año Escolar (Cada asistencia pertenece a un año escolar)
    public function anioEscolar()
    {
        return $this->belongsTo(AnioEscolar::class, 'anio_escolar_id');
    }

    // Relación con Grado (Cada asistencia pertenece a un grado)
    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    // Relación con Sección (Cada asistencia pertenece a una sección)
    public function seccion()
    {
        return $this->belongsTo(Seccion::class);
    }

    // Método para obtener el nombre completo del estado
    public function getEstadoNombreAttribute()
    {
        return match ($this->estado) {
            'P' => 'Puntual',
            'F' => 'Faltó',
            'T' => 'Tardanza',
            'J' => 'Falta Justificada',
            'U' => 'Tardanza Justificada',
            default => 'Desconocido',
        };
    }
}
