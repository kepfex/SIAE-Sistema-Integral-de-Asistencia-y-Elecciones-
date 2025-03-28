<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnioEscolar extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'activo'];

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'anio_escolar_id');
    }
}
