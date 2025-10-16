<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dependencia;
use App\Models\UnidadAcademica;

class DependenciaSeeder extends Seeder
{
    public function run(): void
    {
        $unidad = UnidadAcademica::where('codigo', 'FHCSyCG')->first();

        $dependencias = [
            ['nombre' => 'Dirección Académica', 'codigo' => 'DA'],
            ['nombre' => 'Decanato', 'codigo' => 'DEC'],
            ['nombre' => 'Secretaría General', 'codigo' => 'SG'],
            ['nombre' => 'Departamento de Investigación y Extensión', 'codigo' => 'DIE'],
            ['nombre' => 'Departamento de Posgrado', 'codigo' => 'DP'],
            ['nombre' => 'Departamento de Difusión Cultural', 'codigo' => 'DDC'],
            ['nombre' => 'Departamento de TICs', 'codigo' => 'DTICS'],
            ['nombre' => 'Departamento de Aseguramiento de la Calidad', 'codigo' => 'DAC'],
            ['nombre' => 'Departamento de Mantenimiento', 'codigo' => 'DM'],
            ['nombre' => 'Dirección de Administración', 'codigo' => 'DADM'],
            ['nombre' => 'Departamento de Recursos Humanos', 'codigo' => 'DRH'],
            ['nombre' => 'Departamento de Patrimonio', 'codigo' => 'DPAT'],
        ];

        foreach ($dependencias as $dep) {
            Dependencia::create([
                'unidad_academica_id' => $unidad->id,
                'nombre' => $dep['nombre'],
                'codigo' => $dep['codigo'],
                'activo' => true
            ]);
        }
    }
}