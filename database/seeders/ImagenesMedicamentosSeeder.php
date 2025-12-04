<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImagenesMedicamentosSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        for ($i = 1; $i <= 21; $i++) {
            $data[] = [
                'idImagen' => $i,
                'URL' => "images/medicamentos/" . match ($i) {
                    1 => 'paracetamol500mgAhorro.png',
                    2 => 'paracetamol750mgBruluart.png',
                    3 => 'paracetamol325mgPharmalife.png',
                    4 => 'Ibuprofeno400mgSimilares.png',
                    5 => 'aspirina100mgBayer.png',
                    6 => 'amoxicilina500mgAmsa.png',
                    7 => 'omeprazol20mgUltra.png',
                    8 => 'metformina850mgAsofarma.png',
                    9 => 'loratadina10mgAhorra.png',
                    10 => 'diclofenaco100mgUltra.png',
                    11 => 'naproxeno500mgAhorro.png',
                    12 => 'ranitidina50mgPisa.png',
                    13 => 'cefalexina500mgAmsa.png',
                    14 => 'ibuprofeno600mgPharmalife.png',
                    15 => 'aspirina500mgBayer.png',
                    16 => 'amoxicilina250mgPharmalife.png',
                    17 => 'omeprazol40mgAmsa.png',
                    18 => 'metformina1000mgAsofarma.png',
                    19 => 'ibuprofeno800mgSimilares.png',
                    20 => 'acidoacetilsalicilico300mgAmsa.png',
                    21 => 'losartan300mgUltra.png',
                }
            ];
        }

        DB::table('imagenes_medicamentos')->insert($data);
    }
}
