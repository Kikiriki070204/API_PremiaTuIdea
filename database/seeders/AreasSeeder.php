<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            'Administración',
            'Recursos Humanos',
            'Desarrollo',
            'Ventas',
        ];

        foreach ($areas as $area) {
            Area::create([
                'nombre' => $area
            ]);
        }
    }
}