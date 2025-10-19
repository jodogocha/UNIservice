<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadAcademica extends Model
{
    use HasFactory;

    protected $table = 'unidades_academicas';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'logo',
        'activo',
        'modulos_activos',
        'configuracion',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'modulos_activos' => 'array',
        'configuracion' => 'array',
    ];

    /**
     * Módulos disponibles en el sistema
     */
    public static function modulosDisponibles()
    {
        return [
            'tickets' => [
                'nombre' => 'Gestión de Tickets',
                'descripcion' => 'Sistema de tickets de servicio y soporte técnico',
                'icono' => 'fas fa-ticket-alt',
                'activo_por_defecto' => true,
            ],
            'usuarios' => [
                'nombre' => 'Gestión de Usuarios',
                'descripcion' => 'Administración de usuarios y permisos',
                'icono' => 'fas fa-users',
                'activo_por_defecto' => true,
            ],
            'dependencias' => [
                'nombre' => 'Gestión de Dependencias',
                'descripcion' => 'Administración de departamentos y áreas',
                'icono' => 'fas fa-building',
                'activo_por_defecto' => true,
            ],
            'reportes' => [
                'nombre' => 'Reportes y Estadísticas',
                'descripcion' => 'Generación de reportes y análisis de datos',
                'icono' => 'fas fa-chart-bar',
                'activo_por_defecto' => true,
            ],
            'auditoria' => [
                'nombre' => 'Auditoría del Sistema',
                'descripcion' => 'Registro de actividades y cambios en el sistema',
                'icono' => 'fas fa-history',
                'activo_por_defecto' => false,
            ],
            'inventario' => [
                'nombre' => 'Inventario de Equipos',
                'descripcion' => 'Control de equipos informáticos y hardware',
                'icono' => 'fas fa-laptop',
                'activo_por_defecto' => false,
            ],
            'prestamos' => [
                'nombre' => 'Préstamo de Equipos',
                'descripcion' => 'Gestión de préstamo de equipos a usuarios',
                'icono' => 'fas fa-handshake',
                'activo_por_defecto' => false,
            ],
            'mantenimientos' => [
                'nombre' => 'Mantenimientos Programados',
                'descripcion' => 'Calendario de mantenimientos preventivos',
                'icono' => 'fas fa-calendar-check',
                'activo_por_defecto' => false,
            ],
        ];
    }

    /**
     * Verificar si un módulo está activo
     */
    public function tieneModuloActivo($modulo)
    {
        if (!$this->modulos_activos) {
            return false;
        }
        
        return in_array($modulo, $this->modulos_activos);
    }

    /**
     * Obtener configuración de un módulo específico
     */
    public function getConfiguracionModulo($modulo)
    {
        if (!$this->configuracion || !isset($this->configuracion[$modulo])) {
            return null;
        }
        
        return $this->configuracion[$modulo];
    }

    /**
     * Relación con Dependencias
     */
    public function dependencias()
    {
        return $this->hasMany(Dependencia::class, 'unidad_academica_id');
    }

    /**
     * Relación con Usuarios
     */
    public function users()
    {
        return $this->hasMany(User::class, 'unidad_academica_id');
    }

    /**
     * Obtener la ruta del logo o una por defecto
     */
    public function getLogoPathAttribute()
    {
        return $this->logo ?? 'images/logos/default.png';
    }
}