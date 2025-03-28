<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;
    protected $guarded =[];


    // Un alumno puede tener muchas matrículas
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'alumno_id');
    }
}
