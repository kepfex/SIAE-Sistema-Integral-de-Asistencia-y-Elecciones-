<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matricula extends Model
{
    use HasFactory;
    
    protected $fillable = ['alumno_id', 'anio_escolar_id', 'grado_id', 'seccion_id'];

    // Una matrícula pertenece a un solo alumno
    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

     // Una matrícula pertenece a un año escolar
    public function anioEscolar(): BelongsTo
    {
        return $this->belongsTo(AnioEscolar::class);
    }

    // Relación con grados
    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class);
    }

    // Relación con secciones
    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }
}
