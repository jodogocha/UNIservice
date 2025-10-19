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

    // ============================================
    // RELACIONES
    // ============================================

    /**
     * Roles del usuario
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Dependencia del usuario
     */
    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    /**
     * Unidad Académica del usuario
     */
    public function unidadAcademica()
    {
        return $this->belongsTo(UnidadAcademica::class);
    }

    /**
     * Tickets creados por el usuario (como solicitante)
     */
    public function ticketsCreados()
    {
        return $this->hasMany(Ticket::class, 'solicitante_id');
    }

    /**
     * Tickets asignados al usuario (como técnico)
     */
    public function ticketsAsignados()
    {
        return $this->hasMany(Ticket::class, 'asignado_a');
    }

    /**
     * Todos los tickets relacionados con el usuario
     * (creados o asignados)
     */
    public function tickets()
    {
        return $this->ticketsCreados();
    }

    // ============================================
    // MÉTODOS DE PERMISOS Y ROLES
    // ============================================

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('slug', $role);
        }
        return $this->roles->contains($role);
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     */
    public function hasPermission($permission)
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('slug', $permission)) {
                return true;
            }
        }
        return false;
    }

    // ============================================
    // ACCESSORS (ATRIBUTOS CALCULADOS)
    // ============================================

    /**
     * Nombre completo del usuario
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->name} {$this->apellido}";
    }

    /**
     * Obtener el primer rol del usuario
     */
    public function getRolPrincipalAttribute()
    {
        return $this->roles->first();
    }

    // ============================================
    // MÉTODOS PARA ADMINLTE
    // ============================================

    /**
     * Descripción del usuario para el menú de AdminLTE
     */
    public function adminlte_desc()
    {
        $rol = $this->roles->first();
        return $rol ? $rol->nombre : 'Usuario';
    }

    /**
     * URL de la imagen del usuario para AdminLTE
     */
    public function adminlte_image()
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower($this->email)) . '?s=50&d=mp';
    }

    /**
     * Título del perfil del usuario para AdminLTE
     */
    public function adminlte_profile_url()
    {
        return false;
    }

    // ============================================
    // MÉTODOS DE ESTADÍSTICAS
    // ============================================

    /**
     * Contar tickets creados por el usuario
     */
    public function totalTicketsCreados()
    {
        return $this->ticketsCreados()->count();
    }

    /**
     * Contar tickets asignados al usuario
     */
    public function totalTicketsAsignados()
    {
        return $this->ticketsAsignados()->count();
    }

    /**
     * Contar tickets pendientes asignados
     */
    public function ticketsPendientes()
    {
        return $this->ticketsAsignados()
            ->whereIn('estado', ['pendiente', 'en_proceso'])
            ->count();
    }
}