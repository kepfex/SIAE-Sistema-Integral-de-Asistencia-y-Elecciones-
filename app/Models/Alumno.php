<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;
    protected $fillable = [
        'dni',
        'nombres', 
        'apellido_paterno',
        'apellido_materno',
        'genero',
        'celular',
        'codigo_qr',
        'image_url',
    ];


    // Un alumno puede tener muchas matrículas
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'alumno_id');
    }
}
