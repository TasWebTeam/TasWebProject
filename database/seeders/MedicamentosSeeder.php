<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicamentosSeeder extends Seeder
{
    public function run()
    {
        DB::table('medicamentos')->insert([
            [ 'id_medicamento' => 1, 'nombre' => 'Paracetamol', 'especificacion' => 'Tabletas 500 mg', 'laboratorio' => 'Genfar', 'es_controlado' => 0 ],
            [ 'id_medicamento' => 2, 'nombre' => 'Ibuprofeno', 'especificacion' => 'Cápsulas 400 mg', 'laboratorio' => 'Bayer', 'es_controlado' => 0 ],
            [ 'id_medicamento' => 3, 'nombre' => 'Amoxicilina', 'especificacion' => 'Suspensión 250 mg/5 ml', 'laboratorio' => 'AstraZeneca', 'es_controlado' => 0 ],
            [ 'id_medicamento' => 4, 'nombre' => 'Clonazepam', 'especificacion' => 'Tabletas 2 mg', 'laboratorio' => 'Hoffmann-La Roche', 'es_controlado' => 1 ],
            [ 'id_medicamento' => 5, 'nombre' => 'Metformina', 'especificacion' => 'Tabletas 850 mg', 'laboratorio' => 'Merck', 'es_controlado' => 0 ],
        ]);
    }
}