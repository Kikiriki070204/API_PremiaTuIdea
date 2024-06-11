<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Campos;

class CamposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $campos = [
            [
                'nombre' => 'Seguridad',
                'is_active' => true,
                'monetario' => 1,
            ],
            [
                'nombre' => 'Personal',
                'is_active' => true,
                'monetario' => 1,
            ],
            [
                'nombre' => 'Quality Calidad',
                'is_active' => true,
                'monetario' => 1,
            ],
            [
                'nombre' => 'Volumen',
                'is_active' => true,
                'monetario' => 1,
            ],
            [
                'nombre' => 'Scrap',
                'is_active' => true,
                'monetario' => 3,
            ],
            [
                'nombre' => 'Productividad',
                'is_active' => true,
                'monetario' => 3,
            ],
            [
                'nombre' => 'Ergonomia',
                'is_active' => true,
                'monetario' => 1,
            ],
            [
                'nombre' => 'Soles',
                'is_active' => true,
                'monetario' => 3,
            ],
            [
                'nombre' => 'Improvements',
                'is_active' => true,
                'monetario' => 1,
            ],
            [
                'nombre' => 'Costo',
                'is_active' => true,
                'monetario' => 2,
            ],
        ];

        foreach ($campos as $campo) {
            Campos::create($campo);
        }
    }
}
