<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnidadAcademica;

class UnidadAcademicaSeeder extends Seeder
{
    public function run(): void
    {
        // Módulos por defecto activos
        $modulosPorDefecto = ['tickets', 'usuarios', 'dependencias', 'reportes'];

        // Buscar o crear la Facultad de Humanidades
        $humanidades = UnidadAcademica::where('codigo', 'FHCSyCG')->first();
        
        if ($humanidades) {
            // Si existe, actualizar
            $humanidades->update([
                'logo' => 'images/logos/humanidades.png',
                'modulos_activos' => $modulosPorDefecto,
                'configuracion' => [
                    'tickets' => [
                        'auto_asignar' => false,
                        'require_approval' => false,
                    ],
                    'notificaciones' => [
                        'email' => true,
                    ],
                ],
            ]);
            $this->command->info('✓ Facultad de Humanidades actualizada');
        } else {
            // Si no existe, crear
            UnidadAcademica::create([
                'nombre' => 'Facultad de Humanidades, Ciencias Sociales y Cultura Guaraní',
                'codigo' => 'FHCSyCG',
                'descripcion' => 'Facultad de Humanidades',
                'logo' => 'images/logos/humanidades.png',
                'activo' => true,
                'modulos_activos' => $modulosPorDefecto,
                'configuracion' => [
                    'tickets' => [
                        'auto_asignar' => false,
                        'require_approval' => false,
                    ],
                    'notificaciones' => [
                        'email' => true,
                    ],
                ],
            ]);
            $this->command->info('✓ Facultad de Humanidades creada');
        }

        // Buscar o crear la Facultad de Ingeniería
        $ingenieria = UnidadAcademica::where('codigo', 'FIUNI')->first();
        
        if ($ingenieria) {
            // Si existe, actualizar
            $ingenieria->update([
                'logo' => 'images/logos/fiuni.png',
                'modulos_activos' => [...$modulosPorDefecto, 'inventario', 'mantenimientos'],
                'configuracion' => [
                    'tickets' => [
                        'auto_asignar' => true,
                        'require_approval' => true,
                    ],
                    'notificaciones' => [
                        'email' => true,
                    ],
                ],
            ]);
            $this->command->info('✓ Facultad de Ingeniería actualizada');
        } else {
            // Si no existe, crear
            UnidadAcademica::create([
                'nombre' => 'Facultad de Ingeniería',
                'codigo' => 'FIUNI',
                'descripcion' => 'Facultad de Ingeniería',
                'logo' => 'images/logos/fiuni.png',
                'activo' => true,
                'modulos_activos' => [...$modulosPorDefecto, 'inventario', 'mantenimientos'],
                'configuracion' => [
                    'tickets' => [
                        'auto_asignar' => true,
                        'require_approval' => true,
                    ],
                    'notificaciones' => [
                        'email' => true,
                    ],
                ],
            ]);
            $this->command->info('✓ Facultad de Ingeniería creada');
        }
    }
}