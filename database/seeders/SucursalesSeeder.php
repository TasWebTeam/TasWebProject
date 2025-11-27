<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SucursalesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sucursales')->insert([
            ['id_cadena' => 'AHO', 'id_sucursal' => 1, 'nombre' => 'Cedros', 'latitud' => 24.7889986, 'longitud' => -107.3725018],
            ['id_cadena' => 'AHO', 'id_sucursal' => 2, 'nombre' => 'Colinas del Parque', 'latitud' => 24.78009930, 'longitud' => -107.39398260],
            ['id_cadena' => 'BNV', 'id_sucursal' => 1, 'nombre' => 'Nicolás Bravo', 'latitud' => 24.79675600, 'longitud' => -107.40102410],
            ['id_cadena' => 'BNV', 'id_sucursal' => 2, 'nombre' => 'Pedro Anaya', 'latitud' => 24.82146940, 'longitud' => -107.38997500],
            ['id_cadena' => 'FAR', 'id_sucursal' => 1, 'nombre' => 'Calzada', 'latitud' => 24.78560420, 'longitud' => -107.37100910],
            ['id_cadena' => 'FAR', 'id_sucursal' => 2, 'nombre' => 'Huertas', 'latitud' => 24.78061330, 'longitud' => -107.36880480],
            ['id_cadena' => 'GDL', 'id_sucursal' => 1, 'nombre' => 'Providencia', 'latitud' => 24.7748065, 'longitud' => -107.3726758],
            ['id_cadena' => 'GDL', 'id_sucursal' => 2, 'nombre' => 'Patria', 'latitud' => 24.764458, 'longitud' => -107.3709133],
            ['id_cadena' => 'SIM', 'id_sucursal' => 1, 'nombre' => 'México 68', 'latitud' => 24.7835207, 'longitud' => -107.3771333],
            ['id_cadena' => 'SIM', 'id_sucursal' => 2, 'nombre' => 'Heroico Colegio Militar', 'latitud' => 24.788829, 'longitud' => -107.3719292],
        ]);
    }
}
