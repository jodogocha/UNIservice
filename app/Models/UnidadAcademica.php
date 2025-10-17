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
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación con Dependencias
     */
    public function dependencias()
    {
        return $this->hasMany(Dependencia::class, 'unidad_academica_id');
    }

    /**
     * Relación con Usuarios a través de dependencias
     */
    public function users()
    {
        return $this->hasManyThrough(User::class, Dependencia::class, 'unidad_academica_id', 'dependencia_id');
    }
}