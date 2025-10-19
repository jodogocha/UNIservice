<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'apellido',
        'email',
        'password',
        'documento',
        'telefono',
        'dependencia_id',
        'unidad_academica_id',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    // Relaciones existentes...
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function unidadAcademica()
    {
        return $this->belongsTo(UnidadAcademica::class);
    }

    // Métodos existentes...
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('slug', $role);
        }
        return $this->roles->contains($role);
    }

    public function hasPermission($permission)
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('slug', $permission)) {
                return true;
            }
        }
        return false;
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->name} {$this->apellido}";
    }

    // ============================================
    // MÉTODOS PARA ADMINLTE
    // ============================================

    /**
     * Descripción del usuario para el menú de AdminLTE
     * Este método es llamado por AdminLTE cuando usermenu_desc está habilitado
     */
    public function adminlte_desc()
    {
        // Obtener el rol principal del usuario
        $rol = $this->roles->first();
        
        if ($rol) {
            return $rol->nombre;
        }
        
        return 'Usuario';
    }

    /**
     * URL de la imagen del usuario para AdminLTE
     * Este método es opcional, solo si usermenu_image está habilitado
     */
    public function adminlte_image()
    {
        // Puedes retornar una URL de Gravatar basada en el email
        return 'https://www.gravatar.com/avatar/' . md5(strtolower($this->email)) . '?s=50&d=mp';
        
        // O una imagen por defecto
        // return asset('images/default-avatar.png');
    }

    /**
     * Título del perfil del usuario para AdminLTE
     * Este método es opcional
     */
    public function adminlte_profile_url()
    {
        // Si tienes una ruta de perfil, retornarla aquí
        // return route('profile.show');
        
        return false; // Por ahora retornar false si no tienes perfil
    }
}