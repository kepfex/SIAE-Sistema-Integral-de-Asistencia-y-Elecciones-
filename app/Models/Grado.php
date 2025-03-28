<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grado extends Model
{
    use HasFactory;

    protected $table = 'grados';

    protected $fillable = ['nombre'];

    // Accessor para mostrar el nombre en mayúsculas
    public function getNombreAttribute($value)
    {
        return strtoupper($value);
    }

    // Un grado tiene muchas matrículas
    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'grado_id');
    }
}
