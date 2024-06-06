<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Rol;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AreasSeeder::class,
            DepartamentosSeeder::class,
            LocacionesSeeder::class,
            RolesSeeder::class,
            CamposSeeder::class,
            EstadoIdeasSeeder::class,
            EstadoActividadesSeeder::class,
            EstadoUsuariosPedidosSeeder::class,
            ProductosSeeder::class,
            UsersSeeder::class,
        ]);
    }
}
