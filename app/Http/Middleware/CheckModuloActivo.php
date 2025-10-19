<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuloActivo
{
    /**
     * Módulos y sus rutas asociadas
     */
    protected $modulosRutas = [
        'tickets' => ['tickets.*'],
        'usuarios' => ['usuarios.*'],
        'dependencias' => ['dependencias.*', 'unidades-academicas.*'],
        'reportes' => ['reportes.*'],
        'auditoria' => ['audit.*'],
        'inventario' => ['inventario.*'],
        'prestamos' => ['prestamos.*'],
        'mantenimientos' => ['mantenimientos.*'],
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $modulo = null): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Admin siempre tiene acceso
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        $unidadAcademica = $user->unidadAcademica;
        
        if (!$unidadAcademica) {
            abort(403, 'Tu usuario no está asociado a ninguna unidad académica.');
        }

        // Si se especificó un módulo, verificar ese
        if ($modulo) {
            if (!$unidadAcademica->tieneModuloActivo($modulo)) {
                abort(403, 'Este módulo no está disponible para tu unidad académica.');
            }
            return $next($request);
        }

        // Si no se especificó módulo, verificar por ruta
        $rutaActual = $request->route()->getName();
        
        foreach ($this->modulosRutas as $mod => $rutas) {
            foreach ($rutas as $patron) {
                if (fnmatch($patron, $rutaActual)) {
                    if (!$unidadAcademica->tieneModuloActivo($mod)) {
                        abort(403, "El módulo '{$mod}' no está disponible para tu unidad académica.");
                    }
                    return $next($request);
                }
            }
        }

        return $next($request);
    }
}