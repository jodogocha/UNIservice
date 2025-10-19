<?php

namespace App\Menu\Filters;

use Illuminate\Support\Facades\Auth;
use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class ModuloActiveFilter implements FilterInterface
{
    /**
     * Transforma un item del menú
     */
    public function transform($item, Builder $builder)
    {
        // Si el item no tiene módulo definido, dejarlo pasar
        if (!isset($item['modulo'])) {
            return $item;
        }

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Si no hay usuario, ocultar el item
        if (!$user) {
            $item['restricted'] = true;
            return $item;
        }

        // Admin siempre tiene acceso a todos los módulos
        if ($user->hasRole('admin')) {
            return $item;
        }

        // Verificar si la unidad académica tiene el módulo activo
        $unidadAcademica = $user->unidadAcademica;

        if (!$unidadAcademica) {
            $item['restricted'] = true;
            return $item;
        }

        // Si el módulo no está activo, restringir el acceso
        if (!$unidadAcademica->tieneModuloActivo($item['modulo'])) {
            $item['restricted'] = true;
        }

        return $item;
    }
}