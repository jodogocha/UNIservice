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
        $humanidades = \App\Models\UnidadAcademica::where('codigo', 'FHCSyCG')->first();
        $dtics = Dependencia::where('codigo', 'DTICS')->first();
        $rolFuncionario = Role::where('slug', 'funcionario')->first();
        $rolAdmin = Role::where('slug', 'admin')->first();
        $rolEncargado = Role::where('slug', 'encargado-lab')->first();

        // Usuario Administrador
        $admin = User::create([
            'name' => 'José',
            'apellido' => 'Gómez',
            'documento' => '2232980',
            'telefono' => '0981234567',
            'email' => 'jgomez@uni.edu.py',
            'password' => Hash::make('password'),
            'dependencia_id' => $dtics->id,
            'unidad_academica_id' => $humanidades->id,
            'activo' => true,
        ]);
        $admin->roles()->attach($rolAdmin);
        $this->command->info('✓ Usuario Administrador creado');

        // Usuario Encargado de Laboratorio
        $encargado = User::create([
            'name' => 'Javier',
            'apellido' => 'Núñez',
            'documento' => '2345678',
            'telefono' => '0982345678',
            'email' => 'tecnico@uni.edu.py',
            'password' => Hash::make('password'),
            'dependencia_id' => $dtics->id,
            'unidad_academica_id' => $humanidades->id,
            'activo' => true,
        ]);
        $encargado->roles()->attach($rolEncargado);
        $this->command->info('✓ Usuario Encargado de Laboratorio creado');

        // 15 Usuarios Funcionarios
        $funcionarios = [
            ['name' => 'María', 'apellido' => 'González', 'documento' => '3456789', 'email' => 'mgonzalez@uni.edu.py', 'dependencia' => 'DA', 'telefono' => '0983456789'],
            ['name' => 'Pedro', 'apellido' => 'Martínez', 'documento' => '4567890', 'email' => 'pmartinez@uni.edu.py', 'dependencia' => 'DEC', 'telefono' => '0984567890'],
            ['name' => 'Ana', 'apellido' => 'López', 'documento' => '5678901', 'email' => 'alopez@uni.edu.py', 'dependencia' => 'SG', 'telefono' => '0985678901'],
            ['name' => 'Carlos', 'apellido' => 'Rodríguez', 'documento' => '6789012', 'email' => 'crodriguez@uni.edu.py', 'dependencia' => 'DIE', 'telefono' => '0986789012'],
            ['name' => 'Laura', 'apellido' => 'Fernández', 'documento' => '7890123', 'email' => 'lfernandez@uni.edu.py', 'dependencia' => 'DP', 'telefono' => '0987890123'],
            ['name' => 'Roberto', 'apellido' => 'Sánchez', 'documento' => '8901234', 'email' => 'rsanchez@uni.edu.py', 'dependencia' => 'DDC', 'telefono' => '0988901234'],
            ['name' => 'Sofía', 'apellido' => 'Ramírez', 'documento' => '9012345', 'email' => 'sramirez@uni.edu.py', 'dependencia' => 'DAC', 'telefono' => '0989012345'],
            ['name' => 'Diego', 'apellido' => 'Torres', 'documento' => '1234567', 'email' => 'dtorres@uni.edu.py', 'dependencia' => 'DM', 'telefono' => '0981234568'],
            ['name' => 'Gabriela', 'apellido' => 'Flores', 'documento' => '2345671', 'email' => 'gflores@uni.edu.py', 'dependencia' => 'DADM', 'telefono' => '0982345671'],
            ['name' => 'Miguel', 'apellido' => 'Castro', 'documento' => '3456712', 'email' => 'mcastro@uni.edu.py', 'dependencia' => 'DRH', 'telefono' => '0983456712'],
            ['name' => 'Valentina', 'apellido' => 'Morales', 'documento' => '4567123', 'email' => 'vmorales@uni.edu.py', 'dependencia' => 'DPAT', 'telefono' => '0984567123'],
            ['name' => 'Andrés', 'apellido' => 'Jiménez', 'documento' => '5671234', 'email' => 'ajimenez@uni.edu.py', 'dependencia' => 'DA', 'telefono' => '0985671234'],
            ['name' => 'Carolina', 'apellido' => 'Vargas', 'documento' => '6712345', 'email' => 'cvargas@uni.edu.py', 'dependencia' => 'DEC', 'telefono' => '0986712345'],
            ['name' => 'Fernando', 'apellido' => 'Ruiz', 'documento' => '7123456', 'email' => 'fruiz@uni.edu.py', 'dependencia' => 'SG', 'telefono' => '0987123456'],
            ['name' => 'Patricia', 'apellido' => 'Herrera', 'documento' => '8234567', 'email' => 'pherrera@uni.edu.py', 'dependencia' => 'DIE', 'telefono' => '0988234567'],
        ];

        foreach ($funcionarios as $func) {
            $dependencia = Dependencia::where('codigo', $func['dependencia'])->first();
            
            $usuario = User::create([
                'name' => $func['name'],
                'apellido' => $func['apellido'],
                'documento' => $func['documento'],
                'telefono' => $func['telefono'],
                'email' => $func['email'],
                'password' => Hash::make('password'),
                'dependencia_id' => $dependencia->id,
                'unidad_academica_id' => $humanidades->id,
                'activo' => true,
            ]);
            
            $usuario->roles()->attach($rolFuncionario);
            $this->command->info("✓ Funcionario creado: {$func['name']} {$func['apellido']}");
        }

        $this->command->info('✓ Total: 17 usuarios creados (1 Admin, 1 Encargado, 15 Funcionarios)');
    }
}