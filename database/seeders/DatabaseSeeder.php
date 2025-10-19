<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UnidadAcademicaSeeder::class,
            DependenciaSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            TicketSeeder::class, // ← NUEVO
        ]);

        $this->command->info('');
        $this->command->info('✅ Base de datos poblada exitosamente');
        $this->command->info('📧 Usuarios creados: 17 (1 Admin, 1 Encargado, 15 Funcionarios)');
        $this->command->info('🎫 Tickets creados: 50');
        $this->command->info('🔑 Contraseña para todos: password');
    }
}