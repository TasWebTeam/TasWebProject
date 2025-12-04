<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicamentosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('medicamentos')->insert([
            [
                'id_medicamento' => 1,
                'idImagen' => 1,
                'nombre' => 'Paracetamol 500mg',
                'especificacion' => 'Analgésico y antipirético',
                'laboratorio' => 'Farmacias del Ahorro',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 2,
                'idImagen' => 2,
                'nombre' => 'Paracetamol 750mg',
                'especificacion' => 'Analgésico y antipirético',
                'laboratorio' => 'Bruluart',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 3,
                'idImagen' => 3,
                'nombre' => 'Paracetamol 325mg',
                'especificacion' => 'Analgésico y antipirético',
                'laboratorio' => 'Pharmalife',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 4,
                'idImagen' => 4,
                'nombre' => 'Ibuprofeno 400mg',
                'especificacion' => 'Analgésico, antiinflamatorio y antipirético',
                'laboratorio' => 'Farmacias Similares',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 5,
                'idImagen' => 5,
                'nombre' => 'Aspirina 100mg',
                'especificacion' => 'Analgésico, antipirético y antiagregante plaquetario',
                'laboratorio' => 'Bayer',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 6,
                'idImagen' => 6,
                'nombre' => 'Amoxicilina 500mg',
                'especificacion' => 'Antibiótico',
                'laboratorio' => 'Amsa',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 7,
                'idImagen' => 7,
                'nombre' => 'Omeprazol 20mg',
                'especificacion' => 'Inhibidor de la bomba de protones (Antiácido)',
                'laboratorio' => 'Ultra',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 8,
                'idImagen' => 8,
                'nombre' => 'Metformina 850mg',
                'especificacion' => 'Hipoglucemiante oral (Antidiabético)',
                'laboratorio' => 'Asofarma',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 9,
                'idImagen' => 9,
                'nombre' => 'Loratadina 10mg',
                'especificacion' => 'Antihistamínico para alergias',
                'laboratorio' => 'Farmacias del Ahorro',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 10,
                'idImagen' => 10,
                'nombre' => 'Diclofenaco 100mg',
                'especificacion' => 'Antiinflamatorio no esteroideo (AINE)',
                'laboratorio' => 'Ultra',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 11,
                'idImagen' => 11,
                'nombre' => 'Naproxeno 500mg',
                'especificacion' => 'Analgésico y antiinflamatorio',
                'laboratorio' => 'Farmacias del Ahorro',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 12,
                'idImagen' => 12,
                'nombre' => 'Ranitidina 50mg',
                'especificacion' => 'Antagonista de receptores H2 (Antiulceroso)',
                'laboratorio' => 'Pisa',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 13,
                'idImagen' => 13,
                'nombre' => 'Cefalexina 500mg',
                'especificacion' => 'Antibiótico (Cefalosporina)',
                'laboratorio' => 'Amsa',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 14,
                'idImagen' => 14,
                'nombre' => 'Ibuprofeno 600mg',
                'especificacion' => 'Analgésico, antiinflamatorio y antipirético',
                'laboratorio' => 'Pharmalife',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 15,
                'idImagen' => 15,
                'nombre' => 'Aspirina 500mg',
                'especificacion' => 'Analgésico, antipirético y antiinflamatorio',
                'laboratorio' => 'Bayer',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 16,
                'idImagen' => 16,
                'nombre' => 'Amoxicilina 250mg',
                'especificacion' => 'Antibiótico',
                'laboratorio' => 'Amsa',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 17,
                'idImagen' => 17,
                'nombre' => 'Omeprazol 40mg',
                'especificacion' => 'Inhibidor de la bomba de protones (Antiácido)',
                'laboratorio' => 'Amsa',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 18,
                'idImagen' => 18,
                'nombre' => 'Metformina 1000mg',
                'especificacion' => 'Hipoglucemiante oral (Antidiabético)',
                'laboratorio' => 'Farmacias del Ahorro',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 19,
                'idImagen' => 19,
                'nombre' => 'Ibuprofeno 800mg',
                'especificacion' => 'Analgésico, antiinflamatorio y antipirético',
                'laboratorio' => 'Farmacias Similares',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 20,
                'idImagen' => 20,
                'nombre' => 'Ácido Acetilsalicílico 300mg',
                'especificacion' => 'Analgésico y antipirético',
                'laboratorio' => 'Amsa',
                'es_controlado' => 0
            ],
            [
                'id_medicamento' => 21,
                'idImagen' => 21,
                'nombre' => 'Losartán 300mg',
                'especificacion' => 'Antihipertensivo (ARA-II)',
                'laboratorio' => 'Ultra',
                'es_controlado' => 0
            ],
        ]);
    }
}
