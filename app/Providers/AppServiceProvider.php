<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar vista de paginación personalizada
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.simple-custom');

        // Compartir variables de logo en todas las vistas
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $unidadAcademica = $user->unidadAcademica;
                
                if ($unidadAcademica && $unidadAcademica->logo) {
                    $logoPath = $unidadAcademica->logo;
                    $logoAlt = "Logo {$unidadAcademica->nombre}";
                    $nombreUnidad = $unidadAcademica->nombre;
                } else {
                    $logoPath = 'images/logos/humanidades.png';
                    $logoAlt = 'Logo UNI';
                    $nombreUnidad = 'Universidad Nacional de Itapúa';
                }
                
                $view->with([
                    'userLogo' => $logoPath,
                    'userLogoAlt' => $logoAlt,
                    'userUnidadAcademica' => $nombreUnidad,
                ]);
            }
        });
    }
}