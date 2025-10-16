<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'unidad_academica_id',
        'nombre',
        'codigo',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function unidadAcademica()
    {
        return $this->belongsTo(UnidadAcademica::class);
    }

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
}