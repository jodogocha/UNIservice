<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Gestión de Tickets
            ['nombre' => 'Crear Tickets', 'slug' => 'tickets.create'],
            ['nombre' => 'Ver Tickets', 'slug' => 'tickets.view'],
            ['nombre' => 'Ver Todos los Tickets', 'slug' => 'tickets.view-all'],
            ['nombre' => 'Editar Tickets', 'slug' => 'tickets.edit'],
            ['nombre' => 'Eliminar Tickets', 'slug' => 'tickets.delete'],
            ['nombre' => 'Marcar Ticket como Listo', 'slug' => 'tickets.mark-ready'],
            ['nombre' => 'Cerrar Ticket', 'slug' => 'tickets.close'],
            
            // Gestión de Usuarios
            ['nombre' => 'Crear Usuarios', 'slug' => 'users.create'],
            ['nombre' => 'Ver Usuarios', 'slug' => 'users.view'],
            ['nombre' => 'Editar Usuarios', 'slug' => 'users.edit'],
            ['nombre' => 'Eliminar Usuarios', 'slug' => 'users.delete'],
            
            // Auditoría
            ['nombre' => 'Ver Auditoría', 'slug' => 'audit.view'],
            
            // Reportes
            ['nombre' => 'Ver Reportes', 'slug' => 'reports.view'],
            ['nombre' => 'Generar Reportes', 'slug' => 'reports.generate'],
            
            // Configuración
            ['nombre' => 'Gestionar Configuración', 'slug' => 'config.manage'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Asignar permisos a roles
        $adminRole = Role::where('slug', 'admin')->first();
        $encargadoRole = Role::where('slug', 'encargado-lab')->first();
        $funcionarioRole = Role::where('slug', 'funcionario')->first();

        // Admin tiene todos los permisos
        $adminRole->permissions()->attach(Permission::all());

        // Encargado de laboratorio
        $encargadoRole->permissions()->attach(
            Permission::whereIn('slug', [
                'tickets.view-all',
                'tickets.mark-ready',
                'reports.view',
                'reports.generate',
            ])->get()
        );

        // Funcionario
        $funcionarioRole->permissions()->attach(
            Permission::whereIn('slug', [
                'tickets.create',
                'tickets.view',
                'tickets.close',
            ])->get()
        );
    }
}