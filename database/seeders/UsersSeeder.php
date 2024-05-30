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
                'nombre' => 'Deysi Perez Peña',
                'ibm' => 91750,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Fernando Jared Soria Davila',
                'ibm' => 121833,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'San Juanita Nava Rodriguez',
                'ibm' => 3740521,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Laura Paola Camacho Palacios',
                'ibm' => 37405849,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Gabriela Alejandra Ramirez Moreno',
                'ibm' => 92469,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Avimael Aispuro V',
                'ibm' => 37405599,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Esmeralda Janett Valenzuela Becerra',
                'ibm' => 37404746,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Anabel Salaises  Escobar',
                'ibm' => 96356,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],

            [
                'nombre' => 'Maria Teresa Chavez Trujillo',
                'ibm' => 37405303,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Norma Nayelli Sandoval Vazquez',
                'ibm' => 111237,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Cesar Humberto Valenciana Hernadez',
                'ibm' => 37407111,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Carmen Mayela Macias Aguilar',
                'ibm' => 37401357,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Cecilia Isabel Tijerina Montoya',
                'ibm' => 37401403,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Ma Soledad Aguilar Aguilar',
                'ibm' => 37402851,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => 12,
            ],
            /*[
                'nombre' => 'Juan Vidaña',
                'ibm' => null,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => 12,
            ],
            [
                'nombre' => 'Vanesa Leal',
                'ibm' => null,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => 12,
            ],
            [
                'nombre' => 'Adrian Vallejo',
                'ibm' => null,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => 12,
            ],
            [
                'nombre' => 'Ricardo Rios',
                'ibm' => null,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => 12,
            ],
            [
                'nombre' => 'Esmeralda Robles',
                'ibm' => null,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => 12,
            ],*/
            [
                'nombre' => 'Juan Antonio Gaspar Martinez',
                'ibm' => 37407258,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Elizabeth Janeth Tonche',
                'ibm' => 37402000,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Martha Gallardo Morillon',
                'ibm' => 37407067,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Omar Daniel Espinoza Rodriguez',
                'ibm' => 88281,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
            [
                'nombre' => 'Bibian Fernanda de la Rosa Salazar ',
                'ibm' => 37433715,
                'departamento_id' => 2,
                'area_id' => 1,
                'locacion_id' => null,
            ],
        ];

        foreach ($usuarios as $usuario) {
            Usuario::create($usuario);
        }
    }
}
