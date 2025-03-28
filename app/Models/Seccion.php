<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seccion extends Model
{
    use HasFactory;
    
    protected $table = 'seccions';

    protected $fillable = ['nombre'];

    // Una sección tiene muchas matrículas
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'seccion_id');
    }

}
