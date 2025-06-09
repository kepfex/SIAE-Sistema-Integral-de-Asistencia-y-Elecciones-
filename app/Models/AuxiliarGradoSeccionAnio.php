<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuxiliarGradoSeccionAnio extends Model
{
    use HasFactory;

    protected $table = 'auxiliar_grado_seccion_anio';

    protected $fillable = [
        'user_id',
        'grado_id',
        'seccion_id',
        'anio_escolar_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    public function seccion()
    {
        return $this->belongsTo(Seccion::class);
    }

    public function secciones()
    {
        return $this->belongsToMany(Seccion::class, 'auxiliar_grado_seccion_anio_seccion', 'auxiliar_grado_seccion_anio_id', 'seccion_id');
    }

    public function anioEscolar()
    {
        return $this->belongsTo(AnioEscolar::class, 'anio_escolar_id');
    }
}
