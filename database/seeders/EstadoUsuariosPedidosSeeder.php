<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstadoUsuarioPremios;

class EstadoUsuariosPedidosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            ['nombre' => 'En proceso'],
            ['nombre' => 'Entregado'],
            ['nombre' => 'Cancelado'],
        ];

        foreach ($estados as $estado) {
            EstadoUsuarioPremios::create($estado);
        }
    }
}
