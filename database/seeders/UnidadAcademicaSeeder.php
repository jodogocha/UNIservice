<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnidadAcademica;

class UnidadAcademicaSeeder extends Seeder
{
    public function run(): void
    {
        UnidadAcademica::create([
            'nombre' => 'Facultad de Humanidades, Ciencias Sociales y Cultura Guaraní',
            'codigo' => 'FHCSyCG',
            'descripcion' => 'Facultad de Humanidades de la UNI',
            'activo' => true
        ]);

        // Puedes agregar más facultades si lo deseas
    }
}