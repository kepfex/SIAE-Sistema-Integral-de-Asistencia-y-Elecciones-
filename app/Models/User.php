<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $with = [
        'roles',
        'permissions'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function asignacionesAuxiliar()
    {
        return $this->hasMany(AuxiliarGradoSeccionAnio::class, 'user_id');
    }

    // Determinar si el usuario tiene el rol dado.
    public function hasRole(string $rol): bool
    {
        return $this->roles->contains('nombre', $rol);
    }

    //Determinar si el usuario tiene el permiso dado.
    public function hasPermission(string $permiso): bool
    {
        return $this->permissions->contains('nombre', $permiso);
    }

    // Determinar si el usuario tiene alguno de los roles dados
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles->whereIn('nombre', $roles)->isNotEmpty();
    }

    // Determinar si el usuario tiene alguno de los roles.
    public function hasRoles(): bool
    {
        return $this->roles->isNotEmpty();
    }
}
