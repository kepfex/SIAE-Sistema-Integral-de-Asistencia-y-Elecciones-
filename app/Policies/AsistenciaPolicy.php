<?php

namespace App\Policies;

use App\Models\Asistencia;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AsistenciaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Auxiliar']);
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    public function delete(User $user, Asistencia $asistencia): bool
    {
        return $user->hasRole('Admin');
    }
}
