<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Locacion;

class LocacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locaciones = [
            ['nombre' => 'Almacen', 'area_id' => 4],
            ['nombre' => 'Tool room', 'area_id' => 4],
            ['nombre' => 'Prensas', 'area_id' => 4],
            ['nombre' => 'BVR y BTU elemento', 'area_id' => 1],
            ['nombre' => 'Calidad Exhaust', 'area_id' => 1],
            ['nombre' => 'Mantenimiento', 'area_id' => 1],
            ['nombre' => 'OSL 2 elemento', 'area_id' => 1],
            ['nombre' => 'OSP elemento', 'area_id' => 1],
            ['nombre' => 'OSMa 1 elemento', 'area_id' => 1],
            ['nombre' => 'Cuarto controlado 2', 'area_id' => 1],
            ['nombre' => 'Cuarto controlado 1', 'area_id' => 1],
            ['nombre' => 'Cuarto limpio', 'area_id' => 1],
            ['nombre' => 'NH3 elemento', 'area_id' => 1],
            ['nombre' => 'PM elemento', 'area_id' => 1],
            ['nombre' => 'Mantenimiento OSMa', 'area_id' => 1],
            ['nombre' => 'Mercury', 'area_id' => 1],
            ['nombre' => 'PV8', 'area_id' => 1],
            ['nombre' => 'OSP sensor', 'area_id' => 1],
            ['nombre' => 'OSMa sensor', 'area_id' => 1],
            ['nombre' => 'PM sensor', 'area_id' => 1],
            ['nombre' => 'EACV ASSY', 'area_id' => 3],
            ['nombre' => 'EACV Cuarto Controlado ', 'area_id' => 3],
            ['nombre' => 'EACV ETB TPS/1-2', 'area_id' => 3],
            ['nombre' => 'Hornos Gen V', 'area_id' => 2],
            ['nombre' => 'Hornos PV8', 'area_id' => 2],
            ['nombre' => 'Hornos family 0', 'area_id' => 2],
            ['nombre' => 'Mantenimiento Ignicion', 'area_id' => 2],
            ['nombre' => 'Final Gen V', 'area_id' => 2],
            ['nombre' => 'Final Gen IV', 'area_id' => 2],
            ['nombre' => 'Empaque PV8', 'area_id' => 2],
            ['nombre' => 'Jaula ignicion', 'area_id' => 2],
            ['nombre' => 'Family 0', 'area_id' => 2],
            ['nombre' => 'Oficina gerente de planta', 'area_id' => 4],
            ['nombre' => 'Oficina seguridad patrimonial', 'area_id' => 4],
            ['nombre' => 'Visitors', 'area_id' => 4],
            ['nombre' => 'Sala fundadores', 'area_id' => 4],
            ['nombre' => 'Sala arocena', 'area_id' => 4],
            ['nombre' => 'Recepción', 'area_id' => 4],
            ['nombre' => 'Cuarto de limpieza', 'area_id' => 4],
            ['nombre' => 'Sala de capacitación', 'area_id' => 4],
            ['nombre' => 'Oficina Reclutamiento', 'area_id' => 4],
            ['nombre' => 'Finanzas', 'area_id' => 4],
            ['nombre' => 'Gage crib', 'area_id' => 4],
            ['nombre' => 'Hidrofobia', 'area_id' => 4],
            ['nombre' => 'Laboratorio', 'area_id' => 4],
            ['nombre' => 'Oficina calidad', 'area_id' => 4],
            ['nombre' => 'Ingenieria', 'area_id' => 4],
            ['nombre' => 'Oficinas manufactura', 'area_id' => 4],
            ['nombre' => 'Sala centenario', 'area_id' => 4],
            ['nombre' => 'Sala Nazas', 'area_id' => 4],
            ['nombre' => 'Sala laguna', 'area_id' => 4],
            ['nombre' => 'Oficina Facilities', 'area_id' => 4],
            ['nombre' => 'Gerencia Recursos humanos', 'area_id' => 4],
            ['nombre' => 'Oficina Nominas', 'area_id' => 4],
            ['nombre' => 'Comunicación y capacitación', 'area_id' => 4],
            ['nombre' => 'Oficina IT', 'area_id' => 4],
            ['nombre' => 'Oficina Psicóloga', 'area_id' => 4],
            ['nombre' => 'Oficina Labores', 'area_id' => 4],
            ['nombre' => 'Oficina EHS', 'area_id' => 4],
            ['nombre' => 'Sala torreon', 'area_id' => 4],
            ['nombre' => 'Lean', 'area_id' => 4],
            ['nombre' => 'Comedor', 'area_id' => 4],
            ['nombre' => 'Servicio medico', 'area_id' => 4],
            ['nombre' => 'Cuarentena ignición', 'area_id' => 4],
            ['nombre' => 'Retrabajo ignición', 'area_id' => 4],
            ['nombre' => 'Sorteo SQE', 'area_id' => 4],
            ['nombre' => 'Jaula SQE', 'area_id' => 4],
            ['nombre' => 'Distribuidores', 'area_id' => 4],
            ['nombre' => 'Oficina Toolcrib', 'area_id' => 4],
            ['nombre' => 'Oficina almacén', 'area_id' => 4],
            ['nombre' => 'Recibo', 'area_id' => 4],
            ['nombre' => 'Jaula almacen', 'area_id' => 4],
            ['nombre' => 'Tool crib', 'area_id' => 4],
            ['nombre' => 'PC&L', 'area_id' => 4]
        ];

        foreach ($locaciones as $locacion) {
            Locacion::create($locacion);
        }
    }
}
