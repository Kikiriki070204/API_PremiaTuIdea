<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Laptop Lenovo Ideapad 3 IP3 14IML05',
                'puntos' => 900,
            ],
            [
                'nombre' => 'Refrigerador 14 Pies Mabe con Despachador Silver',
                'puntos' => 590,
            ],
            [
                'nombre' => 'Laptop Lenovo Ideapad AMD A6',
                'puntos' => 550,
            ],
            [
                'nombre' => 'Tablet Samsung 32 GB Galaxy Tab A7 Gris Oscuro',
                'puntos' => 450,
            ],
            [
                'nombre' => 'Lavadora Winia 19 kg Blanca',
                'puntos' => 450,
            ],
            [
                'nombre' => 'Smartphone Samsung Galaxy A21s',
                'puntos' => 400,
            ],
            [
                'nombre' => 'Estufa Acros 20 Pulgadas Acero Inoxidable',
                'puntos' => 300,
            ],
            [
                'nombre' => 'Maquina de Coser con 27 puntadas Semi Profesional Brother XL 2800',
                'puntos' => 289,
            ],
            [
                'nombre' => 'Smartphone Samsung Galaxy M11',
                'puntos' => 250,
            ],
            [
                'nombre' => 'TV Philips 32 Pulgadas HD Smart TV LED 32PFL4765/F8 ',
                'puntos' => 250,
            ],
            [
                'nombre' => 'Tablet Samsung Galaxy Negra 8 Pulgadas 2019',
                'puntos' => 250,
            ],
            [
                'nombre' => 'Ventilador De Torre Mytek 83 cm Control Remoto 3364',
                'puntos' => 150,
            ],
            [
                'nombre' => 'Cafetera Digital Hamilton Beach 12 Tazas Negra',
                'puntos' => 100,
            ],
            [
                'nombre' => 'Plancha Alaciadora Conair Infiniti PRO',
                'puntos' => 100,
            ],
            [
                'nombre' => 'Memoria USB SANDISK 16GB Cruzer Blade 2.0',
                'puntos' => 50,
            ],
            [
                'nombre' => 'Audífonos Redmi Airdots Negro Xiaomi Bluetooth',
                'puntos' => 50,
            ],
            [
                'nombre' => 'Sandwichera Cuisinart 2 Rebanadas',
                'puntos' => 50,
            ],
            [
                'nombre' => 'Gorra & Termo BorgWarner',
                'puntos' => 50,
            ],
            [
                'nombre' => 'Playera, Libreta & Paquete de plumas BorgWarner',
                'puntos' => 50,
            ],
            [
                'nombre' => 'Licuadora T-Fal Infinyforce 10',
                'puntos' => 100,
            ],
            [
                'nombre' => 'Batidora de inmersion Oster',
                'puntos' => 75,
            ],
            [
                'nombre' => 'Batidora de pedestal black and decker',
                'puntos' => 100,
            ],
            [
                'nombre' => 'Tostadora Taurus T2P Rouge',
                'puntos' => 50,
            ],
            [
                'nombre' => 'BATIDORA DE MANO 7 VELOCIDADES RCA',
                'puntos' => 50,
            ],
            [
                'nombre' => 'Freidora de aire gourmia 2.2 qt compact',
                'puntos' => 100,
            ],
            [
                'nombre' => 'Jarra con 4 vasos Crisa',
                'puntos' => 50,
            ],
            [
                'nombre' => 'Vajilla 12 piezas Crisa',
                'puntos' => 60,
            ],
            [
                'nombre' => 'Alexa',
                'puntos' => 85,
            ],
            [
                'nombre' => 'Audífonos inalámbricos redmi buds 3 lite',
                'puntos' => 60,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
