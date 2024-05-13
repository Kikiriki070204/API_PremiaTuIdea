<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = [
            [
                'nombre' => 'Kevin Burciaga',
                'ibm' => 100711,
                'departamento_id' => 1,
                'area_id' => 1,
            ],
            [
                'nombre' => 'Kiara Barrientos',
                'ibm' => 11233,
                'departamento_id' => 2,
                'area_id' => 2,
            ],
        ];

        foreach ($usuarios as $usuario) {
            Usuario::create($usuario);
        }
    }
}
