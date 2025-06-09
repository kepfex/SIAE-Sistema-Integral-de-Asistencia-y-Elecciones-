<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'turno',
        'hora_inicio',
        'hora_tolerancia',
        'hora_maxima',
    ];
}
