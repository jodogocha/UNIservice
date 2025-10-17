<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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