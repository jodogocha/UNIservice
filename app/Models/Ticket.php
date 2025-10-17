<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo',
        'solicitante_id',
        'dependencia_id',
        'unidad_academica_id',
        'asunto',
        'descripcion',
        'tipo_servicio',
        'prioridad',
        'estado',
        'asignado_a',
        'observaciones',
        'solucion',
        'fecha_asignacion',
        'fecha_listo',
        'fecha_finalizado',
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime',
        'fecha_listo' => 'datetime',
        'fecha_finalizado' => 'datetime',
    ];

    /**
     * Boot del modelo para generar código automático
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->codigo)) {
                $ticket->codigo = self::generarCodigo();
            }
        });
    }

    /**
     * Generar código único para el ticket
     */
    public static function generarCodigo()
    {
        $year = date('Y');
        $ultimo = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $numero = $ultimo ? intval(substr($ultimo->codigo, -4)) + 1 : 1;
        
        return sprintf('TK-%s-%04d', $year, $numero);
    }

    /**
     * Relación con el solicitante (usuario)
     */
    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    /**
     * Relación con el usuario asignado
     */
    public function asignado()
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }

    /**
     * Relación con la dependencia
     */
    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    /**
     * Relación con la unidad académica
     */
    public function unidadAcademica()
    {
        return $this->belongsTo(UnidadAcademica::class);
    }

    /**
     * Método estático para obtener los estados
     */
    public static function estados()
    {
        return [
            'pendiente' => 'Pendiente',
            'en_proceso' => 'En Proceso',
            'listo' => 'Listo para Retiro',
            'finalizado' => 'Finalizado',
            'cancelado' => 'Cancelado',
        ];
    }

    /**
     * Método estático para obtener las prioridades
     */
    public static function prioridades()
    {
        return [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];
    }

    /**
     * Método estático para obtener los tipos de servicio
     */
    public static function tiposServicio()
    {
        return [
            'reparacion' => 'Reparación',
            'mantenimiento' => 'Mantenimiento',
            'instalacion' => 'Instalación',
            'consulta' => 'Consulta Técnica',
            'otro' => 'Otro',
        ];
    }

    /**
     * Accessor para obtener el nombre del estado
     */
    public function getEstadoNombreAttribute()
    {
        return self::estados()[$this->estado] ?? 'Desconocido';
    }

    /**
     * Accessor para obtener el nombre de la prioridad
     */
    public function getPrioridadNombreAttribute()
    {
        return self::prioridades()[$this->prioridad] ?? 'Desconocido';
    }

    /**
     * Accessor para obtener el nombre del tipo de servicio
     */
    public function getTipoServicioNombreAttribute()
    {
        return self::tiposServicio()[$this->tipo_servicio] ?? 'Desconocido';
    }

    /**
     * Accessor para obtener la clase CSS del badge según el estado
     */
    public function getEstadoBadgeAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'badge-warning',
            'en_proceso' => 'badge-info',
            'listo' => 'badge-primary',
            'finalizado' => 'badge-success',
            'cancelado' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    /**
     * Accessor para obtener la clase CSS del badge según la prioridad
     */
    public function getPrioridadBadgeAttribute()
    {
        return match($this->prioridad) {
            'baja' => 'badge-secondary',
            'media' => 'badge-info',
            'alta' => 'badge-warning',
            'urgente' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para filtrar tickets pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para filtrar tickets en proceso
     */
    public function scopeEnProceso($query)
    {
        return $query->where('estado', 'en_proceso');
    }

    /**
     * Scope para filtrar tickets listos
     */
    public function scopeListos($query)
    {
        return $query->where('estado', 'listo');
    }

    /**
     * Scope para filtrar tickets finalizados
     */
    public function scopeFinalizados($query)
    {
        return $query->where('estado', 'finalizado');
    }

    /**
     * Scope para filtrar por solicitante
     */
    public function scopePorSolicitante($query, $userId)
    {
        return $query->where('solicitante_id', $userId);
    }

    /**
     * Scope para filtrar por asignado
     */
    public function scopePorAsignado($query, $userId)
    {
        return $query->where('asignado_a', $userId);
    }
}