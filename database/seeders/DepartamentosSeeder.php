<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departamentos = [
            'Administración',
            'Recursos Humanos',
            'Desarrollo',
            'Ventas',
        ];

        foreach ($departamentos as $departamento) {
            Departamento::create([
                'nombre' => $departamento
            ]);
        }
    }
}
