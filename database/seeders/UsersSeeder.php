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
            [
                'nombre' => 'Ricardo Partezan Bianchini',
                'ibm' => 35435149,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            [
                'nombre' => 'Gustavo Adolfo Portillo Favela',
                'ibm' => 37200142,
                'departamento_id' => null,
                'area_id' => 2,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            [
                'nombre' => 'Jorge Christian Lopez Dominguez',
                'ibm' => 37200509,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            [
                'nombre' => 'Mayela Del Carmen Flores Flores',
                'ibm' => 37301377,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            [
                'nombre' => 'Genaro Perez Rodriguez',
                'ibm' => 37301416,
                'departamento_id' => null,
                'area_id' => 2,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            [
                'nombre' => 'Jorge Alberto Jimenez Frausto',
                'ibm' => 37301429,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            [
                'nombre' => 'Naaman Villela Estrada',
                'ibm' => 37400095,
                'departamento_id' => null,
                'area_id' => 3,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Carina Acosta Ramirez',
                'ibm' => 37400892,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Wendy Selene Rodriguez Mendoza',
                'ibm' => 37401388,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Javier Ulises Reyes Ruvalcaba',
                'ibm' => 37402792,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Maria Del Rocio Rivera Ortiz',
                'ibm' => 37403157,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Jorge Ramirez Flores',
                'ibm' => 37403236,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Manuela Muñiz Estrada',
                'ibm' => 37403479,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Marcela Lizeth Palomo Castrellon',
                'ibm' => 37403973,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Luis Carlos Oceguera Tovar',
                'ibm' => 37404152,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Abelardo Gallo Ramos',
                'ibm' => 37404206,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Irma Fabiola Garcia Andrade',
                'ibm' => 37200480,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Jorge Luis Barraza Briones',
                'ibm' => 37301379,
                'departamento_id' => null,
                'area_id' => 2,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Susana Margarita Vargas Basurto',
                'ibm' => 37301413,
                'departamento_id' => null,
                'area_id' => 2,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Jorge Gonzalez Perez',
                'ibm' => 37301492,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Alejandro De Haro Botello',
                'ibm' => 37301512,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Irineo Lopez Alvarado',
                'ibm' => 37400043,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Hector Villanueva Limones',
                'ibm' => 37401226,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Jose Luis De La Rosa Salinas',
                'ibm' => 37401497,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'David Velazquez Martinez',
                'ibm' => 37401500,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Carlos Emmanuel Gonzalez Mendoza',
                'ibm' => 37403718,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Beatriz Adriana Reyes Quevedo',
                'ibm' => 37405002,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Ricardo Frias Quintero',
                'ibm' => 37405669,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Manuel Espino Madrid',
                'ibm' => 37405819,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Christian Abelardo Rodriguez Aldaco',
                'ibm' => 37405967,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Julio Cesar Delgado Gonzalez',
                'ibm' => 37405992,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Saul Lopez Castañeda',
                'ibm' => 37406041,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Adriana Mendoza Ayala',
                'ibm' => 37401548,
                'departamento_id' => null,
                'area_id' => 2,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Myriam Jael De La Torre Villarreal',
                'ibm' => 37400006,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],

            
            [
                'nombre' => 'Sandra Cecilia Maciel Muñoz',
                'ibm' => 37406301,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Alma Ruth Castro Oviedo',
                'ibm' => 37401136,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Michel Binion Lozano',
                'ibm' => 37406624,
                'departamento_id' => null,
                'area_id' => 2,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Ruben Alejandro Delgado Gonzalez',
                'ibm' => 37402916,
                'departamento_id' => null,
                'area_id' => 3,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Roque Alexander Ake Ake',
                'ibm' => 37407044,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Angel Eduardo Cervantes Silva',
                'ibm' => 37407089,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Jose Humberto Alanis Luna',
                'ibm' => 37407257,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Job Astorga Garcia',
                'ibm' => 37433105,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Abel Ramirez Diaz',
                'ibm' => 37433194,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Sara Erika Navarro Najera',
                'ibm' => 37433740,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Maria Luisa Diaz Valdes',
                'ibm' => 81400320,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Dulce Gabriela Flores Vazquez',
                'ibm' => 81400532,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Paola Vianey Uvalle Briones',
                'ibm' => 81400660,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Alejandro Gonzalez Antunez',
                'ibm' => 81401155,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Maria Cruz Gonzalez Gonzalez',
                'ibm' => 81401552,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Irma Esther Caldera Morales',
                'ibm' => 37301497,
                'departamento_id' => 1,
                'area_id' => 2,
                'locacion_id' => null,
                'rol_id' => 1,
            ],

            
            [
                'nombre' => 'Maria Valeria Sandoval Hernandez',
                'ibm' => 81401874,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Gumercindo Ibarra Sariñana',
                'ibm' => 37400171,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Cesar Alejandro Martinez Martinez',
                'ibm' => 81402049,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
  
            [
                'nombre' => 'Erik Israel Cornejo Tarango',
                'ibm' => 81402071,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Andrea Martinez Garcia',
                'ibm' => 81402072,
                'departamento_id' => null,
                'area_id' => 3,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Luis Arturo Serrano Hernandez',
                'ibm' => 37403644,
                'departamento_id' => null,
                'area_id' => 3,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Jorge Guerrero De La Torre',
                'ibm' => 13595,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Rogelio Espinoza Castorena',
                'ibm' => 37301509,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Juan Alberto Alvarado Aguirre',
                'ibm' => 37401708,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Jesus Contreras Aguirre',
                'ibm' => 37403435,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Roberto Rodriguez Morales',
                'ibm' => 81400148,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Angel Dorado Saldaña',
                'ibm' => 81400147,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Ruth Gamboa Martinez',
                'ibm' => 37406857,
                'departamento_id' => null,
                'area_id' => 4,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Oscar Gamboa Ortiz',
                'ibm' => 13603,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Raúl Felipe Vargas Martinez',
                'ibm' => 37406326,
                'departamento_id' => null,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
            
            [
                'nombre' => 'Salvador Esteban Ayala Hernandez',
                'ibm' => 37402947,
                'departamento_id' => 1,
                'area_id' => 1,
                'locacion_id' => null,
                'rol_id' => 1,
            ],
        ];

        foreach ($usuarios as $usuario) {
            Usuario::create($usuario);
        }
    }
}