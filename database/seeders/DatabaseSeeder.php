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
        //$roles = [
        //    ['nombre' => 'Admin'],
        //    ['nombre' => 'Recursos'],
        //    ['nombre' => 'Usuarios'],
        //    ['nombre' => 'Invitado']
        //];

        //foreach ($roles as $rol) {
        //    Rol::create($rol);
        //}

       $usuarios = [
        [
            'nombre' => 'Kiara Admin',
            'ibm' => 11233
        ],
    ];

    foreach ($usuarios as $usuario) {
        User::create($usuario);
    }
    }
}
