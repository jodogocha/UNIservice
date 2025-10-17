<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnidadAcademica;

class UnidadAcademicaSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar o crear la Facultad de Humanidades
        $humanidades = UnidadAcademica::where('codigo', 'FHCSyCG')->first();
        
        if ($humanidades) {
            // Si existe, solo actualizar el logo
            $humanidades->update([
                'logo' => 'images/logos/humanidades.png',
            ]);
            $this->command->info('✓ Logo actualizado para Facultad de Humanidades');
        } else {
            // Si no existe, crear
            UnidadAcademica::create([
                'nombre' => 'Facultad de Humanidades, Ciencias Sociales y Cultura Guaraní',
                'codigo' => 'FHCSyCG',
                'descripcion' => 'Facultad de Humanidades',
                'logo' => 'images/logos/humanidades.png',
                'activo' => true
            ]);
            $this->command->info('✓ Facultad de Humanidades creada');
        }

        // Buscar o crear la Facultad de Ingeniería
        $ingenieria = UnidadAcademica::where('codigo', 'FIUNI')->first();
        
        if ($ingenieria) {
            // Si existe, solo actualizar el logo
            $ingenieria->update([
                'logo' => 'images/logos/fiuni.png',
            ]);
            $this->command->info('✓ Logo actualizado para Facultad de Ingeniería');
        } else {
            // Si no existe, crear
            UnidadAcademica::create([
                'nombre' => 'Facultad de Ingeniería',
                'codigo' => 'FIUNI',
                'descripcion' => 'Facultad de Ingeniería',
                'logo' => 'images/logos/fiuni.png',
                'activo' => true
            ]);
            $this->command->info('✓ Facultad de Ingeniería creada');
        }
    }
}