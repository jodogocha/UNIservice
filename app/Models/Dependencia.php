<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    use HasFactory;

    protected $table = 'dependencias';

    protected $fillable = [
        'nombre',
        'codigo',
        'unidad_academica_id',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación con Unidad Académica
     */
    public function unidadAcademica()
    {
        return $this->belongsTo(UnidadAcademica::class, 'unidad_academica_id');
    }

    /**
     * Relación con Usuarios
     */
    public function users()
    {
        return $this->hasMany(User::class, 'dependencia_id');
    }
}