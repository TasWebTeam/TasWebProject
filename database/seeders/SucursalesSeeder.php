<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SucursalesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sucursales')->insert([
            ['id_sucursal' => 1, 'id_cadena' => 'AHO', 'nombre' => 'Cedros', 'latitud' => 24.7889986, 'longitud' => -107.3725018],
            ['id_sucursal' => 2, 'id_cadena' => 'AHO', 'nombre' => 'Colinas del Parque', 'latitud' => 24.78009930, 'longitud' => -107.39398260],

            ['id_sucursal' => 1, 'id_cadena' => 'BNV', 'nombre' => 'Nicolás Bravo', 'latitud' => 24.79675600, 'longitud' => -107.40102410],
            ['id_sucursal' => 2, 'id_cadena' => 'BNV', 'nombre' => 'Pedro Anaya', 'latitud' => 24.82146940, 'longitud' => -107.38997500],

            ['id_sucursal' => 1, 'id_cadena' => 'FAR', 'nombre' => 'Calzada', 'latitud' => 24.78560420, 'longitud' => -107.37100910],
            ['id_sucursal' => 2, 'id_cadena' => 'FAR', 'nombre' => 'Huertas', 'latitud' => 24.78061330, 'longitud' => -107.36880480],

            ['id_sucursal' => 1, 'id_cadena' => 'GDL', 'nombre' => 'Providencia', 'latitud' => 24.7748065, 'longitud' => -107.3726758],
            ['id_sucursal' => 2, 'id_cadena' => 'GDL', 'nombre' => 'Patria', 'latitud' => 24.764458, 'longitud' => -107.3709133],

            ['id_sucursal' => 1, 'id_cadena' => 'SIM', 'nombre' => 'México 68', 'latitud' => 24.7835207, 'longitud' => -107.3771333],
            ['id_sucursal' => 2, 'id_cadena' => 'SIM', 'nombre' => 'Heroico Colegio Militar', 'latitud' => 24.788829, 'longitud' => -107.3719292],
        ]);
    }
}
