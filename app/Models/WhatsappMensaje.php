<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappMensaje extends Model
{
    use HasFactory;
    
    
    protected $fillable = [
        'telefono', 'estudiante', 'genero', 'grado', 'seccion', 'tipo', 'fecha', 'hora', 'estado', 'enviado_en'
    ];
}
