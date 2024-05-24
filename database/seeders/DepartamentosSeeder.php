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
            'Lean',
            'Produccion',
            'Manufactura',
            'Calidad',
            'Compras',
            'Finanzas', 
        ];

        foreach ($departamentos as $departamento) {
            Departamento::create([
                'nombre' => $departamento
            ]);
        }
    }
}
