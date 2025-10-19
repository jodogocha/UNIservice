<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    */

    'title' => 'UNIService',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    */

    'logo' => '<b>UNI</b>Service',
    'logo_img' => 'images/logos/uni.png',
    'logo_img_class' => 'brand-image',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Logo de la UNI',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    */

    'auth_logo' => [
        'enabled' => true,
        'img' => [
            'path' => 'images/logos/uni.png',
            'alt' => 'Logo de la UNI',
            'class' => '',
            'width' => 150,
            'height' => 150,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'images/logos/uni.png',
            'alt' => 'Cargando...',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => true,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    */

    'classes_body' => '',
    'classes_brand' => 'navbar-primary navbar-dark',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => false,
    'password_reset_url' => 'password.request',
    'password_email_url' => 'password.email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    */

    'menu' => [
        // Navbar items
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        [
            'type' => 'darkmode-widget',
            'topnav_right' => true,
        ],

        // Sidebar items
        [
            'text' => 'Dashboard',
            'route' => 'home',
            'icon' => 'fas fa-fw fa-tachometer-alt',
            'icon_color' => 'cyan',
        ],

        ['header' => 'TICKETS'],

        // Menú de Tickets con validación de módulo
        [
            'text' => 'Gestión de Tickets',
            'icon' => 'fas fa-fw fa-ticket-alt',
            'icon_color' => 'yellow',
            'active' => ['tickets*'],
            'modulo' => 'tickets', // ← Validación de módulo
            'submenu' => [
                [
                    'text' => 'Mis Tickets',
                    'route' => 'tickets.mis-tickets',
                    'icon' => 'fas fa-fw fa-user-clock',
                    'can' => 'tickets.view',
                ],
                [
                    'text' => 'Todos los Tickets',
                    'route' => 'tickets.index',
                    'icon' => 'fas fa-fw fa-list-alt',
                    'can' => 'tickets.view-all',
                ],
                [
                    'text' => 'Nuevo Ticket',
                    'route' => 'tickets.create',
                    'icon' => 'fas fa-fw fa-plus-circle',
                    'icon_color' => 'green',
                    'can' => 'tickets.create',
                ],
            ],
        ],

        ['header' => 'ADMINISTRACIÓN', 'can' => 'users.view'],

        // Menú de Usuarios con validación de módulo
        [
            'text' => 'Gestión de Usuarios',
            'icon' => 'fas fa-fw fa-users',
            'icon_color' => 'blue',
            'active' => ['usuarios*'],
            'can' => 'users.view',
            'modulo' => 'usuarios', // ← Validación de módulo
            'submenu' => [
                [
                    'text' => 'Todos los Usuarios',
                    'route' => 'usuarios.index',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Crear Usuario',
                    'route' => 'usuarios.create',
                    'icon' => 'fas fa-fw fa-user-plus',
                    'icon_color' => 'green',
                    'can' => 'users.create',
                ],
            ],
        ],

        // Menú de Roles
        [
            'text' => 'Gestión de Roles',
            'icon' => 'fas fa-fw fa-user-tag',
            'icon_color' => 'purple',
            'active' => ['roles*'],
            'can' => 'users.view',
            'modulo' => 'usuarios', // ← Validación de módulo
            'submenu' => [
                [
                    'text' => 'Todos los Roles',
                    'route' => 'roles.index',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Crear Rol',
                    'route' => 'roles.create',
                    'icon' => 'fas fa-fw fa-plus',
                    'icon_color' => 'green',
                    'can' => 'users.create',
                ],
            ],
        ],

        // Menú de Unidades Académicas
        [
            'text' => 'Unidades Académicas',
            'icon' => 'fas fa-fw fa-university',
            'icon_color' => 'indigo',
            'active' => ['unidades-academicas*'],
            'can' => 'users.view',
            'modulo' => 'dependencias', // ← Validación de módulo
            'submenu' => [
                [
                    'text' => 'Todas las Unidades',
                    'route' => 'unidades-academicas.index',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Crear Unidad',
                    'route' => 'unidades-academicas.create',
                    'icon' => 'fas fa-fw fa-plus',
                    'icon_color' => 'green',
                    'can' => 'users.create',
                ],
            ],
        ],

        // Menú de Dependencias
        [
            'text' => 'Dependencias',
            'icon' => 'fas fa-fw fa-building',
            'icon_color' => 'teal',
            'active' => ['dependencias*'],
            'can' => 'users.view',
            'modulo' => 'dependencias', // ← Validación de módulo
            'submenu' => [
                [
                    'text' => 'Todas las Dependencias',
                    'route' => 'dependencias.index',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Crear Dependencia',
                    'route' => 'dependencias.create',
                    'icon' => 'fas fa-fw fa-plus',
                    'icon_color' => 'green',
                    'can' => 'users.create',
                ],
            ],
        ],

        // Auditoría
        [
            'text' => 'Auditoría',
            'route' => 'audit.index',
            'icon' => 'fas fa-fw fa-history',
            'icon_color' => 'orange',
            'active' => ['audit*'],
            'can' => 'audit.view',
            'modulo' => 'auditoria', // ← Validación de módulo
        ],

        // Configuración
        [
            'text' => 'Configuración',
            'icon' => 'fas fa-fw fa-cog',
            'icon_color' => 'gray',
            'active' => ['configuracion*'],
            'can' => 'config.manage',
            'submenu' => [
                [
                    'text' => 'General',
                    'route' => 'configuracion.index',
                    'icon' => 'fas fa-sliders-h',
                ],
            ],
        ],

        ['header' => 'REPORTES', 'can' => 'reports.view'],

        // Menú de Reportes con validación de módulo
        [
            'text' => 'Reportes',
            'icon' => 'fas fa-chart-bar',
            'icon_color' => 'red',
            'active' => ['reportes*'],
            'can' => 'reports.view',
            'modulo' => 'reportes', // ← Validación de módulo
            'submenu' => [
                [
                    'text' => 'Centro de Reportes',
                    'route' => 'reportes.index',
                    'icon' => 'fas fa-home',
                ],
                [
                    'text' => 'Trabajos por Usuario',
                    'route' => 'reportes.trabajos-usuario',
                    'icon' => 'fas fa-user-check',
                ],
                [
                    'text' => 'Por Dependencia',
                    'route' => 'reportes.solicitudes-dependencia',
                    'icon' => 'fas fa-building',
                ],
                [
                    'text' => 'Ranking Dependencias',
                    'route' => 'reportes.ranking-dependencias',
                    'icon' => 'fas fa-trophy',
                    'icon_color' => 'yellow',
                ],
                [
                    'text' => 'Ranking Usuarios',
                    'route' => 'reportes.ranking-usuarios',
                    'icon' => 'fas fa-medal',
                    'icon_color' => 'orange',
                ],
                [
                    'text' => 'Por Horario',
                    'route' => 'reportes.servicios-horario',
                    'icon' => 'fas fa-clock',
                ],
                [
                    'text' => 'Trabajos Asignados',
                    'route' => 'reportes.trabajos-asignados',
                    'icon' => 'fas fa-tasks',
                ],
                [
                    'text' => 'Totales Mensuales',
                    'route' => 'reportes.totales-mensuales',
                    'icon' => 'fas fa-chart-line',
                ],
                [
                    'text' => 'Totales Anuales',
                    'route' => 'reportes.totales-anuales',
                    'icon' => 'fas fa-chart-area',
                ],
            ],
        ],

        // Módulos Futuros (Inventario, Préstamos, Uso del Laboratorio)
        ['header' => 'LABORATORIO'],

        [
            'text' => 'Inventario',
            'icon' => 'fas fa-fw fa-laptop',
            'icon_color' => 'green',
            'active' => ['inventario*'],
            'can' => 'users.view',
            'modulo' => 'inventario', // ← Validación de módulo
            'url' => '#',
        ],

        [
            'text' => 'Préstamos',
            'icon' => 'fas fa-fw fa-handshake',
            'icon_color' => 'blue',
            'active' => ['prestamos*'],
            'can' => 'users.view',
            'modulo' => 'prestamos', // ← Validación de módulo
            'url' => '#',
        ],

        [
            'text' => 'Uso del Laboratorio',
            'icon' => 'fas fa-fw fa-calendar-check',
            'icon_color' => 'purple',
            'active' => ['usos*'],
            'can' => 'users.view',
            'modulo' => 'usos', // ← Validación de módulo
            'url' => '#',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
        App\Menu\Filters\ModuloActiveFilter::class, // ← Filtro personalizado
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
        ],
        'Pace' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    */

    'livewire' => false,
];