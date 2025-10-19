<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class ModuloHelper
{
    /**
     * Verificar si un módulo está activo para el usuario actual
     */
    public static function isActive(string $modulo): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Admin siempre tiene acceso
        if ($user->hasRole('admin')) {
            return true;
        }

        $unidadAcademica = $user->unidadAcademica;
        
        if (!$unidadAcademica) {
            return false;
        }

        return $unidadAcademica->tieneModuloActivo($modulo);
    }
}