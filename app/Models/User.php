<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Importamos Spatie para la gestión de roles y permisos
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    // Limpiamos la duplicidad y quitamos HasApiTokens que causaba el error
    use HasFactory, Notifiable, HasRoles;

    /**
     * Atributos asignables (Mass assignable)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atributos ocultos para la serialización (API/JSON)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión de tipos (Casting)
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * MÉTODO DE AYUDA
     * Verifica rápidamente si el usuario tiene el rol de administrador
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}