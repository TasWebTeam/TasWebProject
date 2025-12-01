<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventariosSeeder extends Seeder
{
    public function run()
    {
        DB::table('inventarios')->insert([
            // AHO Sucursal 1
            [ 'id_inventario' => 1,  'id_cadena' => 'AHO', 'id_sucursal' => 1, 'id_medicamento' => 1, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 2,  'precio_actual' => 120.50 ],
            [ 'id_inventario' => 2,  'id_cadena' => 'AHO', 'id_sucursal' => 1, 'id_medicamento' => 2, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 80, 'precio_actual' => 85.00 ],
            [ 'id_inventario' => 3,  'id_cadena' => 'AHO', 'id_sucursal' => 1, 'id_medicamento' => 3, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 80, 'precio_actual' => 95.00 ],
            [ 'id_inventario' => 4,  'id_cadena' => 'AHO', 'id_sucursal' => 1, 'id_medicamento' => 4, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 80, 'precio_actual' => 140.00 ],
            [ 'id_inventario' => 5,  'id_cadena' => 'AHO', 'id_sucursal' => 1, 'id_medicamento' => 5, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 80, 'precio_actual' => 110.00 ],

            // AHO Sucursal 2
            [ 'id_inventario' => 6,  'id_cadena' => 'AHO', 'id_sucursal' => 2, 'id_medicamento' => 1, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 0,  'precio_actual' => 120.50 ],
            [ 'id_inventario' => 7,  'id_cadena' => 'AHO', 'id_sucursal' => 2, 'id_medicamento' => 2, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 80, 'precio_actual' => 85.00 ],
            [ 'id_inventario' => 8,  'id_cadena' => 'AHO', 'id_sucursal' => 2, 'id_medicamento' => 3, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 80, 'precio_actual' => 95.00 ],
            [ 'id_inventario' => 9,  'id_cadena' => 'AHO', 'id_sucursal' => 2, 'id_medicamento' => 4, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 80, 'precio_actual' => 140.00 ],
            [ 'id_inventario' => 10, 'id_cadena' => 'AHO', 'id_sucursal' => 2, 'id_medicamento' => 5, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 80, 'precio_actual' => 110.00 ],

            // BNV Sucursal 3
            [ 'id_inventario' => 11, 'id_cadena' => 'BNV', 'id_sucursal' => 3, 'id_medicamento' => 1, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 2,  'precio_actual' => 120.50 ],
            [ 'id_inventario' => 12, 'id_cadena' => 'BNV', 'id_sucursal' => 3, 'id_medicamento' => 2, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 80, 'precio_actual' => 85.00 ],
            [ 'id_inventario' => 13, 'id_cadena' => 'BNV', 'id_sucursal' => 3, 'id_medicamento' => 3, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 80, 'precio_actual' => 95.00 ],
            [ 'id_inventario' => 14, 'id_cadena' => 'BNV', 'id_sucursal' => 3, 'id_medicamento' => 4, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 80, 'precio_actual' => 140.00 ],
            [ 'id_inventario' => 15, 'id_cadena' => 'BNV', 'id_sucursal' => 3, 'id_medicamento' => 5, 'stock_minimo' => 10, 'stock_maximo' => 150, 'stock_actual' => 80, 'precio_actual' => 110.00 ],
        ]);
    }
}