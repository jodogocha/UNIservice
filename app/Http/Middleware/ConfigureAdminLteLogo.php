<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class ConfigureAdminLteLogo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $unidadAcademica = $user->unidadAcademica;
            
            if ($unidadAcademica && $unidadAcademica->logo) {
                // Configurar el logo de AdminLTE dinámicamente
                Config::set('adminlte.logo_img', $unidadAcademica->logo);
                Config::set('adminlte.logo_img_alt', "Logo {$unidadAcademica->nombre}");
                Config::set('adminlte.title_postfix', " | {$unidadAcademica->nombre}");
                
                // Configurar el preloader
                Config::set('adminlte.preloader.img.path', $unidadAcademica->logo);
                Config::set('adminlte.preloader.img.alt', "Cargando {$unidadAcademica->nombre}...");
                
                // Configurar el logo de autenticación (para cuando vuelva a la página de login)
                Config::set('adminlte.auth_logo.img.path', $unidadAcademica->logo);
                Config::set('adminlte.auth_logo.img.alt', "Logo {$unidadAcademica->nombre}");
            }
        }
        
        return $next($request);
    }
}