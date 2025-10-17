<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Permisos de Tickets
            ['nombre' => 'Ver tickets', 'slug' => 'tickets.view', 'descripcion' => 'Ver sus propios tickets'],
            ['nombre' => 'Ver todos los tickets', 'slug' => 'tickets.view-all', 'descripcion' => 'Ver todos los tickets del sistema'],
            ['nombre' => 'Crear tickets', 'slug' => 'tickets.create', 'descripcion' => 'Crear nuevos tickets'],
            ['nombre' => 'Editar tickets', 'slug' => 'tickets.edit', 'descripcion' => 'Editar tickets'],
            ['nombre' => 'Eliminar tickets', 'slug' => 'tickets.delete', 'descripcion' => 'Eliminar tickets'],
            ['nombre' => 'Marcar ticket listo', 'slug' => 'tickets.mark-ready', 'descripcion' => 'Marcar tickets como listos'],
            ['nombre' => 'Cerrar tickets', 'slug' => 'tickets.close', 'descripcion' => 'Cerrar y finalizar tickets'],
            
            // Permisos de Usuarios
            ['nombre' => 'Ver usuarios', 'slug' => 'users.view', 'descripcion' => 'Ver listado de usuarios'],
            ['nombre' => 'Crear usuarios', 'slug' => 'users.create', 'descripcion' => 'Crear nuevos usuarios'],
            ['nombre' => 'Editar usuarios', 'slug' => 'users.edit', 'descripcion' => 'Editar usuarios existentes'],
            ['nombre' => 'Eliminar usuarios', 'slug' => 'users.delete', 'descripcion' => 'Eliminar usuarios del sistema'],
            
            // Permisos de Reportes
            ['nombre' => 'Ver reportes', 'slug' => 'reports.view', 'descripcion' => 'Acceder a reportes y estadísticas'],
            ['nombre' => 'Exportar reportes', 'slug' => 'reports.export', 'descripcion' => 'Exportar reportes a PDF/Excel'],
            
            // Permisos de Auditoría
            ['nombre' => 'Ver auditoría', 'slug' => 'audit.view', 'descripcion' => 'Ver logs de auditoría del sistema'],
            
            // Permisos de Configuración
            ['nombre' => 'Gestionar configuración', 'slug' => 'config.manage', 'descripcion' => 'Administrar configuración del sistema'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                [
                    'nombre' => $permission['nombre'],
                    'descripcion' => $permission['descripcion']
                ]
            );
        }

        $this->command->info('Permisos creados/actualizados correctamente');
    }
}