<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'Administrador',
                'slug' => 'admin',
                'descripcion' => 'Administrador del sistema con acceso completo'
            ],
            [
                'nombre' => 'Encargado de Laboratorio',
                'slug' => 'encargado-lab',
                'descripcion' => 'Encargado del laboratorio, gestiona tickets'
            ],
            [
                'nombre' => 'Funcionario',
                'slug' => 'funcionario',
                'descripcion' => 'Funcionario que puede crear y gestionar sus tickets'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}