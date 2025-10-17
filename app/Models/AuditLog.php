<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'module',
        'record_id',
        'ip_address',
        'user_agent',
        'description',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener el badge de color según la acción
     */
    public function getActionBadgeAttribute()
    {
        return match($this->action) {
            'create' => 'badge-success',
            'update' => 'badge-info',
            'delete' => 'badge-danger',
            'login' => 'badge-primary',
            'logout' => 'badge-secondary',
            'assign' => 'badge-warning',
            'mark-ready' => 'badge-success',
            'finalize' => 'badge-success',
            'cancel' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    /**
     * Obtener el nombre legible de la acción
     */
    public function getActionNameAttribute()
    {
        return match($this->action) {
            'create' => 'Crear',
            'update' => 'Actualizar',
            'delete' => 'Eliminar',
            'login' => 'Inicio de Sesión',
            'logout' => 'Cierre de Sesión',
            'assign' => 'Asignar',
            'mark-ready' => 'Marcar Listo',
            'finalize' => 'Finalizar',
            'cancel' => 'Cancelar',
            default => ucfirst($this->action),
        };
    }

    /**
     * Registrar una acción en la auditoría
     */
    public static function log($action, $module, $description, $recordId = null, $oldValues = null, $newValues = null)
    {
        $user = auth()->user();
        
        return self::create([
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? $user->nombre_completo : 'Sistema',
            'action' => $action,
            'module' => $module,
            'record_id' => $recordId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}