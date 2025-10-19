<?php

namespace App\Helpers;

class FacultadHelper
{
    /**
     * Obtener la configuración de la facultad actual
     */
    public static function actual()
    {
        $codigo = config('facultad.codigo', 'FHCSyCG');
        $logos = config('facultad.logos', []);
        
        return $logos[$codigo] ?? $logos['FHCSyCG'] ?? [
            'nombre' => 'Universidad Nacional de Itapúa',
            'nombre_corto' => 'UNI',
            'logo' => 'images/logos/uni.png',
            'logo_alt' => 'Logo UNI',
        ];
    }

    /**
     * Obtener el logo de la facultad actual
     */
    public static function logo()
    {
        return self::actual()['logo'];
    }

    /**
     * Obtener el nombre de la facultad actual
     */
    public static function nombre()
    {
        return self::actual()['nombre'];
    }

    /**
     * Obtener el nombre corto de la facultad actual
     */
    public static function nombreCorto()
    {
        return self::actual()['nombre_corto'];
    }

    /**
     * Obtener todas las facultades configuradas
     */
    public static function todas()
    {
        return config('facultad.logos', []);
    }
}