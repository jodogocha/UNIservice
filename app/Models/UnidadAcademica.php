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
        'logo', // Agregar este campo
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

    /**
     * Obtener la ruta del logo o una por defecto
     */
    public function getLogoPathAttribute()
    {
        return $this->logo ?? 'images/logos/default.png';
    }

    /**
     * Obtener la URL completa del logo
     */
    public function getLogoUrlAttribute()
    {
        return asset($this->logo_path);
    }
}