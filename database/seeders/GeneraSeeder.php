<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
class GeneraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anio = 2025;

        $genera = [
            // Enero
            ['id_iglesia' => 1, 'id_remesa' => 1, 'mes' => 1, 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 2, 'mes' => 1, 'anio' => $anio],

            // Febrero
            ['id_iglesia' => 1, 'id_remesa' => 3, 'mes' => 2, 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 4, 'mes' => 2, 'anio' => $anio],

            // Marzo
            ['id_iglesia' => 1, 'id_remesa' => 5, 'mes' => 3, 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 6, 'mes' => 3, 'anio' => $anio],

            // Abril
            ['id_iglesia' => 1, 'id_remesa' => 7, 'mes' => 4, 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 8, 'mes' => 4, 'anio' => $anio],

            // Mayo
            ['id_iglesia' => 1, 'id_remesa' => 9, 'mes' => 5, 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 10, 'mes' => 5, 'anio' => $anio],

            // Junio
            ['id_iglesia' => 1, 'id_remesa' => 11, 'mes' => 6, 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 12, 'mes' => 6, 'anio' => $anio],

            // Julio
            ['id_iglesia' => 1, 'id_remesa' => 13, 'mes' => 7, 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 14, 'mes' => 7, 'anio' => $anio],

            // Agosto
            ['id_iglesia' => 1, 'id_remesa' => 15, 'mes' => 8, 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 16, 'mes' => 8, 'anio' => $anio],

            // Septiembre
            ['id_iglesia' => 1, 'id_remesa' => 17, 'mes' => 9, 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 18, 'mes' => 9, 'anio' => $anio],

            // Octubre
            ['id_iglesia' => 1, 'id_remesa' => 19, 'mes' => 10, 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 20, 'mes' => 10, 'anio' => $anio],
        ];

        DB::table('generas')->insert($genera);
    }
}
