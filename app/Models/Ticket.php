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

    // Relaciones
    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function asignadoA()
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function unidadAcademica()
    {
        return $this->belongsTo(UnidadAcademica::class);
    }

    // Scopes para filtros
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEnProceso($query)
    {
        return $query->where('estado', 'en_proceso');
    }

    public function scopeListos($query)
    {
        return $query->where('estado', 'listo');
    }

    public function scopeFinalizados($query)
    {
        return $query->where('estado', 'finalizado');
    }

    public function scopeMisTickets($query, $userId)
    {
        return $query->where('solicitante_id', $userId);
    }

    // Métodos auxiliares
    public function esSolicitante($userId)
    {
        return $this->solicitante_id == $userId;
    }

    public function puedeSerFinalizado()
    {
        return $this->estado === 'listo';
    }

    public function puedeSerMarcadoComoListo()
    {
        return in_array($this->estado, ['pendiente', 'en_proceso']);
    }

    // Generar código único de ticket
    public static function generarCodigo()
    {
        $year = date('Y');
        $lastTicket = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $numero = $lastTicket ? intval(substr($lastTicket->codigo, -4)) + 1 : 1;
        
        return sprintf('TK-%s-%04d', $year, $numero);
    }

    // Obtener badge de estado
    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'pendiente' => 'badge-warning',
            'en_proceso' => 'badge-info',
            'listo' => 'badge-primary',
            'finalizado' => 'badge-success',
            'cancelado' => 'badge-danger',
        ];

        return $badges[$this->estado] ?? 'badge-secondary';
    }

    // Obtener badge de prioridad
    public function getPrioridadBadgeAttribute()
    {
        $badges = [
            'baja' => 'badge-secondary',
            'media' => 'badge-info',
            'alta' => 'badge-warning',
            'urgente' => 'badge-danger',
        ];

        return $badges[$this->prioridad] ?? 'badge-secondary';
    }

    // Traducciones
    public static function tiposServicio()
    {
        return [
            'mantenimiento' => 'Mantenimiento',
            'asesoramiento' => 'Asesoramiento',
            'reparacion' => 'Reparación',
            'configuracion' => 'Configuración',
            'otro' => 'Otro',
        ];
    }

    public static function prioridades()
    {
        return [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];
    }

    public static function estados()
    {
        return [
            'pendiente' => 'Pendiente',
            'en_proceso' => 'En Proceso',
            'listo' => 'Listo',
            'finalizado' => 'Finalizado',
            'cancelado' => 'Cancelado',
        ];
    }
}