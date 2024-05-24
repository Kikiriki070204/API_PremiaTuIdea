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
                'nombre' => 'Laptop Lenovo Ideapad 3 IP3 14IML05"',
                'valor' => 1170,
                'url' => 'https://i5.walmartimages.com.mx/gr/images/product-images/img_large/00019534880271L1.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Refrigerador 14 Pies Mabe con Despachador RME360FDMRQ0 Silver',
                'valor' => 920,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00075763837443l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Laptop Lenovo V14  82C6001ELM',
                'valor' => 660,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/88471f41-e76f-4ca8-b780-24f006c2451b.4e23e4ee3a1c8343bf5cf409d0f55084.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Tablet Samsung 32 GB Galaxy Tab A7 Gris Oscuro',
                'valor' => 470,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/e47b6262-8a4d-40a1-b8db-d8461df5f2bc.a1397a5f81e46429c8add566ee95e6b2.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Lavadora Winia 19 kg Blanca',
                'valor' => 750,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00750174462067l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Samsung Galaxy A52  NEGRO',
                'valor' => 420,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/51f3447f-c392-4bce-933f-0c6ff895813d.ebe69a48e1a0163fe0a3a5ebc90d9502.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Estufa Acros 30 Pulgadas Acero Inoxidable',
                'valor' => 495,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00750154561131l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Maquina de Coser con 27 puntadas Semi Profesional Brother XL 2800',
                'valor' => 300,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/65bd962b-56ee-4669-9c98-c925fbe09b65.1e0a3364ca32dd74f6287b22b22f9494.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Smartphone Samsung Galaxy A05s Negro',
                'valor' => 295,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/38c21b03-92ea-4c06-ac0c-1d42fc428140.61555a552e4d0eb93ae423acd23bb8f2.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'TV Samsung 32 Pulgadas HD Smart TV LED UN32T4310AFXZX',
                'valor' => 325,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00750940182733l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Tablet Samsung Galaxy Negra 8 Pulgadas 2019',
                'valor' => 310,
                'url' => 'https://i5.walmartimages.com.mx/gr/images/product-images/img_large/00019363828795L2.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Ventilador De Torre Mytek 83 cm Control Remoto 3364',
                'valor' => 150,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/dee0e2c9-c92f-4c05-9a48-11cb9980ef2d_1.69af4bdb76344ff8db16b6d961bb0869.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            /*[
                'nombre' => 'Licuadora Oster RojaMod. M6798-13',
                'valor' => 65,
                'url' => 'https://m.media-amazon.com/images/I/615jhzk-LNL._AC_SX569_.jpg'
            ],*/
            [
                'nombre' => 'Batidora de pedestal Hamilton Beach',
                'valor' => 100,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/355afc17-d1f6-4b50-83db-17ff4c4d409d.49134baf04891d83bc50eb4e00203f10.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Cafetera Digital Hamilton Beach 12 Tazas Negra',
                'valor' => 120,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00004009448465l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Plancha Alaciadora Conair Infiniti PRO',
                'valor' => 70,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00007410831583l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF'
            ],
            [
                'nombre' => 'Memoria USB SANDISK 16GB Cruzer Blade 2.0',
                'valor' => 15,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/134cdbb1-85df-4815-ba12-73465c58808e_1.0466662f6a24ef7a37d0417d1e85bc25.jpeg?odnHeight=768&odnWidth=768&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Audífonos Redmi Airdots Negro Xiaomi Bluetooth',
                'valor' => 60,
                'url' => 'https://ss632.liverpool.com.mx/xl/1091105990_2p.jpg',
            ],
            [
                'nombre' => 'Sandwichera Cuisinart 2 Rebanadas',
                'valor' => 65,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/5bf490db-0eba-4646-b27f-f412f11f892b.f159606f28d7c0022bbf83cb45cf787d.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Gorra & Termo BorgWarner',
                'valor' => 30,
                'url' => null,
            ],
            [
                'nombre' => 'Playera, Libreta & Paquete de plumas BorgWarner',
                'valor' => 30,
                'url' => null,
            ],
            [
                'nombre' => 'Licuadora T-Fal Infinyforce 10',
                'valor' => 55,
                'url' => 'https://m.media-amazon.com/images/I/51sCixDWajL.__AC_SX300_SY300_QL70_ML2_.jpg',
            ],
            [
                'nombre' => 'Batidora de inmersion Oster',
                'valor' => 45,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00003426447900-2l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Tostadora Taurus T2P',
                'valor' => 30,
                'url' => 'https://res.cloudinary.com/walmart-labs/image/upload/c_pad,h_500,w_500,q_auto:best/v1716487249242/gr/images/product-images/img_large/00750229785359L.jpg',
            ],
            [
                'nombre' => 'BATIDORA DE MANO 7 VELOCIDADES RCA',
                'valor' => 20,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00750038310453-3l.jpg?odnHeight=768&odnWidth=768&odnBg=FFFFFF',
            ],
            /*[
                'nombre' => 'Freidora de aire gourmia 2.2 qt compact',
                'valor' => 100,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00081000286386l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],*/
            [
                'nombre' => 'Jarra con 4 vasos Crisa',
                'valor' => 15,
                'url' => 'https://res.cloudinary.com/walmart-labs/image/upload/c_pad,h_500,w_500,q_auto:best/v1715897092093/gr/images/product-images/img_large/00007891721078L.jpg',
            ],
            [
                'nombre' => 'Vajilla 12 piezas Crisa',
                'valor' => 25,
                'url' => 'https://i5.walmartimages.com.mx/gr/images/product-images/img_large/00007891713117L.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Alexa',
                'valor' => 80,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00085369795260l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
            [
                'nombre' => 'Audífonos inalámbricos redmi buds 3 lite',
                'valor' => 30,
                'url' => 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/c2458add-2284-4ee0-b661-844c31e0fc3b.c5b0260739afcd55e4177388492a018b.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF',
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
