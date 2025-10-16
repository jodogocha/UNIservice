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
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function dependencias()
    {
        return $this->hasMany(Dependencia::class);
    }

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
}