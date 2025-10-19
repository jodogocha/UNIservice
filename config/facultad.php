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
    'codigo' => env('FACULTAD_CODIGO', 'FHCSyCG'),
    
    // Configuración de logos por facultad
    'logos' => [
        'FHCSyCG' => [
            'nombre' => 'Facultad de Humanidades, Ciencias Sociales y Cultura Guaraní',
            'nombre_corto' => 'Facultad de Humanidades',
            'logo' => 'images/logos/humanidades.png',
            'logo_alt' => 'Logo Facultad de Humanidades',
        ],
        'FIUNI' => [
            'nombre' => 'Facultad de Ingeniería',
            'nombre_corto' => 'Facultad de Ingeniería',
            'logo' => 'images/logos/fiuni.png',
            'logo_alt' => 'Logo Facultad de Ingeniería',
        ],
    ],

];