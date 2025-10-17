<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración de la Facultad
    |--------------------------------------------------------------------------
    |
    | Aquí se configuran los datos específicos de cada facultad para
    | hacer el sistema adaptable a diferentes unidades académicas.
    |
    */

    // Facultad actual en uso
    'codigo' => env('FACULTAD_CODIGO', 'FHCSCG'),
    
    // Configuración de logos por facultad
    'logos' => [
        'FHCSCG' => [
            'nombre' => 'Facultad de Humanidades, Ciencias Sociales y Cultura Guaraní',
            'nombre_corto' => 'Facultad de Humanidades',
            'logo' => 'images/logos/humanidades.png',
            'logo_alt' => 'Logo Facultad de Humanidades',
        ],
        // Aquí se pueden agregar otras facultades en el futuro
        'FING' => [
            'nombre' => 'Facultad de Ingeniería',
            'nombre_corto' => 'Facultad de Ingeniería',
            'logo' => 'images/logos/ingenieria.png',
            'logo_alt' => 'Logo Facultad de Ingeniería',
        ],
    ],

    // Obtener la configuración de la facultad actual
    'actual' => function() {
        $codigo = config('facultad.codigo', 'FHCSCG');
        return config("facultad.logos.{$codigo}", config('facultad.logos.FHCSCG'));
    },

];