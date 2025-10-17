<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Rol: Administrador (todos los permisos)
        $admin = Role::where('slug', 'admin')->first();
        if ($admin) {
            $this->command->info('Asignando permisos al rol Administrador...');
            $allPermissions = Permission::all()->pluck('id')->toArray();
            $admin->permissions()->sync($allPermissions);
            $this->command->info('✓ Permisos asignados a Administrador: ' . count($allPermissions));
        }

        // Rol: Encargado de Laboratorio
        $encargado = Role::where('slug', 'encargado-lab')->first();
        if ($encargado) {
            $this->command->info('Asignando permisos al rol Encargado de Laboratorio...');
            $permissions = Permission::whereIn('slug', [
                'tickets.view',
                'tickets.view-all',
                'tickets.create',
                'tickets.edit',
                'tickets.mark-ready',
                'tickets.close',
                'reports.view',
                'reports.export',
            ])->pluck('id')->toArray();
            $encargado->permissions()->sync($permissions);
            $this->command->info('✓ Permisos asignados a Encargado: ' . count($permissions));
        }

        // Rol: Funcionario
        $funcionario = Role::where('slug', 'funcionario')->first();
        if ($funcionario) {
            $this->command->info('Asignando permisos al rol Funcionario...');
            $permissions = Permission::whereIn('slug', [
                'tickets.view',
                'tickets.create',
                'tickets.close',
            ])->pluck('id')->toArray();
            $funcionario->permissions()->sync($permissions);
            $this->command->info('✓ Permisos asignados a Funcionario: ' . count($permissions));
        }

        $this->command->info('Asignación de permisos completada correctamente');
    }
}