<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CadenasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cadenas')->insert([
            ['id_cadena' => 'AHO', 'nombre' => 'Farmacias del Ahorro'],
            ['id_cadena' => 'GDL', 'nombre' => 'Farmacias Guadalajara'],
            ['id_cadena' => 'BNV', 'nombre' => 'Farmacias Benavides'],
            ['id_cadena' => 'SIM', 'nombre' => 'Farmacias Similares'],
            ['id_cadena' => 'FAR', 'nombre' => 'Farmacias Farmacon'],
        ]);
    }
}
