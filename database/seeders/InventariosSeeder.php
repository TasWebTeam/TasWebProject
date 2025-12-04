<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventarios = [
            ['id_cadena' => 'AHO', 'id_sucursal' => 1, 'id_medicamento' => 1, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 35.00],
            ['id_cadena' => 'AHO', 'id_sucursal' => 1, 'id_medicamento' => 2, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 28.00],
            ['id_cadena' => 'AHO', 'id_sucursal' => 1, 'id_medicamento' => 3, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 42.00],
            ['id_cadena' => 'AHO', 'id_sucursal' => 1, 'id_medicamento' => 4, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 48.00],
            ['id_cadena' => 'AHO', 'id_sucursal' => 1, 'id_medicamento' => 5, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 32.00],
            ['id_cadena' => 'AHO', 'id_sucursal' => 1, 'id_medicamento' => 6, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 25.00],
            ['id_cadena' => 'AHO', 'id_sucursal' => 1, 'id_medicamento' => 7, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 38.00],
            ['id_cadena' => 'AHO', 'id_sucursal' => 1, 'id_medicamento' => 8, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 45.00],
            
            ['id_cadena' => 'AHO', 'id_sucursal' => 2, 'id_medicamento' => 1, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 35.00],
            ['id_cadena' => 'AHO', 'id_sucursal' => 2, 'id_medicamento' => 2, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 28.00],
            ['id_cadena' => 'AHO', 'id_sucursal' => 2, 'id_medicamento' => 3, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 42.00],
            ['id_cadena' => 'AHO', 'id_sucursal' => 2, 'id_medicamento' => 4, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 48.00],
            ['id_cadena' => 'AHO', 'id_sucursal' => 2, 'id_medicamento' => 5, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 32.00],
            ['id_cadena' => 'AHO', 'id_sucursal' => 2, 'id_medicamento' => 6, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 25.00],
            ['id_cadena' => 'AHO', 'id_sucursal' => 2, 'id_medicamento' => 7, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 38.00],
            ['id_cadena' => 'AHO', 'id_sucursal' => 2, 'id_medicamento' => 8, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 45.00],
            
            ['id_cadena' => 'BNV', 'id_sucursal' => 3, 'id_medicamento' => 1, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 35.00],
            ['id_cadena' => 'BNV', 'id_sucursal' => 3, 'id_medicamento' => 2, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 28.00],
            ['id_cadena' => 'BNV', 'id_sucursal' => 3, 'id_medicamento' => 3, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 42.00],
            ['id_cadena' => 'BNV', 'id_sucursal' => 3, 'id_medicamento' => 4, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 48.00],
            ['id_cadena' => 'BNV', 'id_sucamento' => 3, 'id_medicamento' => 5, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 32.00],
            ['id_cadena' => 'BNV', 'id_sucursal' => 3, 'id_medicamento' => 6, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 25.00],
            ['id_cadena' => 'BNV', 'id_sucursal' => 3, 'id_medicamento' => 7, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 38.00],
            ['id_cadena' => 'BNV', 'id_sucursal' => 3, 'id_medicamento' => 8, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 45.00],
            
            ['id_cadena' => 'BNV', 'id_sucursal' => 4, 'id_medicamento' => 1, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 35.00],
            ['id_cadena' => 'BNV', 'id_sucursal' => 4, 'id_medicamento' => 2, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 28.00],
            ['id_cadena' => 'BNV', 'id_sucursal' => 4, 'id_medicamento' => 3, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 42.00],
            ['id_cadena' => 'BNV', 'id_sucursal' => 4, 'id_medicamento' => 4, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 48.00],
            ['id_cadena' => 'BNV', 'id_sucursal' => 4, 'id_medicamento' => 5, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 32.00],
            ['id_cadena' => 'BNV', 'id_sucursal' => 4, 'id_medicamento' => 6, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 25.00],
            ['id_cadena' => 'BNV', 'id_sucursal' => 4, 'id_medicamento' => 7, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 38.00],
            ['id_cadena' => 'BNV', 'id_sucursal' => 4, 'id_medicamento' => 8, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 45.00],
            
            ['id_cadena' => 'FAR', 'id_sucursal' => 5, 'id_medicamento' => 1, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 35.00],
            ['id_cadena' => 'FAR', 'id_sucursal' => 5, 'id_medicamento' => 2, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 28.00],
            ['id_cadena' => 'FAR', 'id_sucursal' => 5, 'id_medicamento' => 3, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 42.00],
            ['id_cadena' => 'FAR', 'id_sucursal' => 5, 'id_medicamento' => 4, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 48.00],
            ['id_cadena' => 'FAR', 'id_sucursal' => 5, 'id_medicamento' => 5, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 32.00],
            ['id_cadena' => 'FAR', 'id_sucursal' => 5, 'id_medicamento' => 6, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 25.00],
            ['id_cadena' => 'FAR', 'id_sucursal' => 5, 'id_medicamento' => 7, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 38.00],
            ['id_cadena' => 'FAR', 'id_sucursal' => 5, 'id_medicamento' => 8, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 45.00],
            
            ['id_cadena' => 'FAR', 'id_sucursal' => 6, 'id_medicamento' => 1, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 35.00],
            ['id_cadena' => 'FAR', 'id_sucursal' => 6, 'id_medicamento' => 2, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 28.00],
            ['id_cadena' => 'FAR', 'id_sucursal' => 6, 'id_medicamento' => 3, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 42.00],
            ['id_cadena' => 'FAR', 'id_sucursal' => 6, 'id_medicamento' => 4, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 48.00],
            ['id_cadena' => 'FAR', 'id_sucursal' => 6, 'id_medicamento' => 5, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 32.00],
            ['id_cadena' => 'FAR', 'id_sucursal' => 6, 'id_medicamento' => 6, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 25.00],
            ['id_cadena' => 'FAR', 'id_sucursal' => 6, 'id_medicamento' => 7, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 38.00],
            ['id_cadena' => 'FAR', 'id_sucursal' => 6, 'id_medicamento' => 8, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 45.00],
            
            ['id_cadena' => 'GDL', 'id_sucursal' => 7, 'id_medicamento' => 1, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 35.00],
            ['id_cadena' => 'GDL', 'id_sucursal' => 7, 'id_medicamento' => 2, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 28.00],
            ['id_cadena' => 'GDL', 'id_sucursal' => 7, 'id_medicamento' => 3, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 42.00],
            ['id_cadena' => 'GDL', 'id_sucursal' => 7, 'id_medicamento' => 4, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 48.00],
            ['id_cadena' => 'GDL', 'id_sucursal' => 7, 'id_medicamento' => 5, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 32.00],
            ['id_cadena' => 'GDL', 'id_sucursal' => 7, 'id_medicamento' => 6, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 25.00],
            ['id_cadena' => 'GDL', 'id_sucursal' => 7, 'id_medicamento' => 7, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 38.00],
            ['id_cadena' => 'GDL', 'id_sucursal' => 7, 'id_medicamento' => 8, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 45.00],
            
            ['id_cadena' => 'GDL', 'id_sucursal' => 8, 'id_medicamento' => 1, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 35.00],
            ['id_cadena' => 'GDL', 'id_sucursal' => 8, 'id_medicamento' => 2, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 28.00],
            ['id_cadena' => 'GDL', 'id_sucursal' => 8, 'id_medicamento' => 3, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 42.00],
            ['id_cadena' => 'GDL', 'id_sucursal' => 8, 'id_medicamento' => 4, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 48.00],
            ['id_cadena' => 'GDL', 'id_sucursal' => 8, 'id_medicamento' => 5, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 32.00],
            ['id_cadena' => 'GDL', 'id_sucursal' => 8, 'id_medicamento' => 6, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 25.00],
            ['id_cadena' => 'GDL', 'id_sucursal' => 8, 'id_medicamento' => 7, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 38.00],
            ['id_cadena' => 'GDL', 'id_sucursal' => 8, 'id_medicamento' => 8, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 45.00],
            
            ['id_cadena' => 'SIM', 'id_sucursal' => 9, 'id_medicamento' => 1, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 35.00],
            ['id_cadena' => 'SIM', 'id_sucursal' => 9, 'id_medicamento' => 2, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 28.00],
            ['id_cadena' => 'SIM', 'id_sucursal' => 9, 'id_medicamento' => 3, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 42.00],
            ['id_cadena' => 'SIM', 'id_sucursal' => 9, 'id_medicamento' => 4, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 48.00],
            ['id_cadena' => 'SIM', 'id_sucursal' => 9, 'id_medicamento' => 5, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 32.00],
            ['id_cadena' => 'SIM', 'id_sucursal' => 9, 'id_medicamento' => 6, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 25.00],
            ['id_cadena' => 'SIM', 'id_sucursal' => 9, 'id_medicamento' => 7, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 38.00],
            ['id_cadena' => 'SIM', 'id_sucursal' => 9, 'id_medicamento' => 8, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 45.00],
            
            ['id_cadena' => 'SIM', 'id_sucursal' => 10, 'id_medicamento' => 1, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 35.00],
            ['id_cadena' => 'SIM', 'id_sucursal' => 10, 'id_medicamento' => 2, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 28.00],
            ['id_cadena' => 'SIM', 'id_sucursal' => 10, 'id_medicamento' => 3, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 42.00],
            ['id_cadena' => 'SIM', 'id_sucursal' => 10, 'id_medicamento' => 4, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 48.00],
            ['id_cadena' => 'SIM', 'id_sucursal' => 10, 'id_medicamento' => 5, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 32.00],
            ['id_cadena' => 'SIM', 'id_sucursal' => 10, 'id_medicamento' => 6, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 25.00],
            ['id_cadena' => 'SIM', 'id_sucursal' => 10, 'id_medicamento' => 7, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 38.00],
            ['id_cadena' => 'SIM', 'id_sucursal' => 10, 'id_medicamento' => 8, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 8, 'precio_actual' => 45.00],
        ];

        DB::table('inventarios')->insert($inventarios);
    }
}