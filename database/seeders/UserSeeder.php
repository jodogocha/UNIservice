<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Dependencia;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $dtics = Dependencia::where('codigo', 'DTICS')->first();

        // Usuario Administrador
        $admin = User::create([
            'name' => 'José',
            'apellido' => 'Administrador',
            'documento' => '2232980',
            'email' => 'jgomez@uni.edu.py',
            'password' => Hash::make('password'),
            'dependencia_id' => $dtics->id,
            'unidad_academica_id' => $dtics->unidad_academica_id,
            'activo' => true,
        ]);
        $admin->roles()->attach(Role::where('slug', 'admin')->first());

        // Usuario Encargado de Laboratorio
        $encargado = User::create([
            'name' => 'Javier',
            'apellido' => 'Nuñez',
            'documento' => '2345678',
            'email' => 'tecnico@uni.edu.py',
            'password' => Hash::make('password'),
            'dependencia_id' => $dtics->id,
            'unidad_academica_id' => $dtics->unidad_academica_id,
            'activo' => true,
        ]);
        $encargado->roles()->attach(Role::where('slug', 'encargado-lab')->first());

        // Usuario Funcionario
        $funcionario = User::create([
            'name' => 'Mauro',
            'apellido' => 'Ledesma',
            'documento' => '3456789',
            'email' => 'mledesma@uni.edu.py',
            'password' => Hash::make('password'),
            'dependencia_id' => Dependencia::where('codigo', 'DA')->first()->id,
            'unidad_academica_id' => $dtics->unidad_academica_id,
            'activo' => true,
        ]);
        $funcionario->roles()->attach(Role::where('slug', 'funcionario')->first());
    }
}