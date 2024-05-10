<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Estado_Actividad;

class EsatdoActividadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            ['nombre' => 'Revision'],
            ['nombre' => 'Aceptada'],
            ['nombre' => 'Implementada'],
            ['nombre' => 'Rechazada'],
        ];

        foreach ($estados as $estado) {
            Estado_Actividad::create($estado);
        }
    }
}
