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
                'nombre' => 'Laptop Laptop Lenovo V14',
                'valor' => 890,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/f9f1302e-6f2f-4627-905c-7780c3e78300.d69774e893ad4066a377f4f6f261a373.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Refrigerador 14 Pies Mabe con Despachador Silver',
                'valor' => 560,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00075763837443l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Laptop Lenovo Ideapad AMD A6',
                'valor' => 550,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/8d512ef6-dfc9-450b-a357-ad23720df9d3.17b678ee3a96c4d079269c64c73da03c.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Tablet Samsung 32 GB Galaxy Tab A7 Gris Oscuro',
                'valor' => 450,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00880609079792-1l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Lavadora Winia 19 kg Blanca',
                'valor' => 450,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00750174462067l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Smartphone Samsung Galaxy A21s',
                'valor' => 400,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/47b12c3e-e455-4154-b68b-71c91422e739.ff6459a386a5230d0c6812dc8ba4010e.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Estufa Acros 20 Pulgadas Acero Inoxidable',
                'valor' => 300,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/26f4f211-e85f-4ed8-bffe-502c598b8a28_1.9cfd422655c7917155f1a184ebb4d1f5.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Maquina de Coser con 27 puntadas Semi Profesional Brother XL 2800',
                'valor' => 289,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/65bd962b-56ee-4669-9c98-c925fbe09b65.1e0a3364ca32dd74f6287b22b22f9494.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Smartphone Samsung Galaxy M11',
                'valor' => 250,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/f59b5d84-5aa1-499d-904d-76df2b1a94af.9484577ff48681762dc7f51773f8d781.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'TV Philips 32 Pulgadas HD Smart TV LED 32PFL4765/F8',
                'valor' => 250,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/37605ad1-245c-4880-90d4-a0a4c4ca7fa0.717d0820a4640fe5c7e7a7b378dc080c.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Tablet Samsung Galaxy Negra 8 Pulgadas 2019',
                'valor' => 250,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00880164398899-1l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Ventilador De Torre Mytek 83 cm Control Remoto 3364',
                'valor' => 150,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/dee0e2c9-c92f-4c05-9a48-11cb9980ef2d_1.69af4bdb76344ff8db16b6d961bb0869.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Cafetera Digital Hamilton Beach 12 Tazas Negra',
                'valor' => 100,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00004009448465l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Plancha Alaciadora Conair Infiniti PRO',
                'valor' => 100,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00007410833298-1l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Memoria USB SANDISK 16GB Cruzer Blade 2.0',
                'valor' => 50,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/236b060e-0179-4eb8-bac2-328712340cf9_1.2607df9e3b136c6554f17086d92740e8.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Audífonos Redmi Airdots Negro Xiaomi Bluetooth',
                'valor' => 50,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/ff623be2-031f-459b-aec3-a187c13f9956.7e7aff287114e04bb947ecc3a99eb78d.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Sandwichera Cuisinart 2 Rebanadas',
                'valor' => 50,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00008627900181l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Gorra & Termo BorgWarner',
                'valor' => 50,
                'url' => null,
            ],
            [
                'nombre' => 'Playera, Libreta & Paquete de plumas BorgWarner',
                'valor' => 50,
                'url' => null,
            ],
            [
                'nombre' => 'Licuadora T-Fal Infinyforce 10',
                'valor' => 100,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/b481134a-987a-414c-8acb-0506525076fa.659bedd82e60f689e5490fe99be2a8d8.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Batidora de inmersion Oster',
                'valor' => 75,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00003426447900-2l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Batidora de pedestal black and decker',
                'valor' => 100,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00005087582515l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Tostadora Taurus T2P Rouge',
                'valor' => 50,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00750229785164-1l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'BATIDORA DE MANO 7 VELOCIDADES RCA',
                'valor' => 50,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00750038310453-3l.jpg?odnHeight=768&odnWidth=768&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Freidora de aire gourmia 2.2 qt compact',
                'valor' => 100,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00081000286386l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Jarra con 4 vasos Crisa',
                'valor' => 50,
                'url' => 'https://res.cloudinary.com/walmart-labs/image/upload/c_pad,h_500,w_500,q_auto:best/v1715897092093/gr/images/product-images/img_large/00007891721078L.jpg',
            ],
            [
                'nombre' => 'Vajilla 12 piezas Crisa',
                'valor' => 60,
                'url' => 'https://i5.walmartimages.com.mx/gr/images/product-images/img_large/00007891713117L.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Alexa',
                'valor' => 85,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/44ecadd7-f089-462f-939b-4e8b0bed4c18.6ec8716c4986d13cb9b10f87c703ecae.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Audífonos inalámbricos redmi buds 3 lite',
                'valor' => 60,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/073bc1c1-b075-4e06-8d50-ea90b05a85f1.03d395e993bf9012d2d89da12d4b1e88.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
