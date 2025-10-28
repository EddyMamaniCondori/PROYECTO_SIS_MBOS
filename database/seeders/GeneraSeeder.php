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
            ['id_iglesia' => 1, 'id_remesa' => 1, 'id_gasto' => 1, 'mes' => 'Enero', 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 2, 'id_gasto' => 2, 'mes' => 'Enero', 'anio' => $anio],

            // Febrero
            ['id_iglesia' => 1, 'id_remesa' => 3, 'id_gasto' => 3, 'mes' => 'Febrero', 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 4, 'id_gasto' => 4, 'mes' => 'Febrero', 'anio' => $anio],

            // Marzo
            ['id_iglesia' => 1, 'id_remesa' => 5, 'id_gasto' => 5, 'mes' => 'Marzo', 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 6, 'id_gasto' => 6, 'mes' => 'Marzo', 'anio' => $anio],

            // Abril
            ['id_iglesia' => 1, 'id_remesa' => 7, 'id_gasto' => 7, 'mes' => 'Abril', 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 8, 'id_gasto' => 8, 'mes' => 'Abril', 'anio' => $anio],

            // Mayo
            ['id_iglesia' => 1, 'id_remesa' => 9, 'id_gasto' => 9, 'mes' => 'Mayo', 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 10, 'id_gasto' => 10, 'mes' => 'Mayo', 'anio' => $anio],

            // Junio
            ['id_iglesia' => 1, 'id_remesa' => 11, 'id_gasto' => 11, 'mes' => 'Junio', 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 12, 'id_gasto' => 12, 'mes' => 'Junio', 'anio' => $anio],

            // Julio
            ['id_iglesia' => 1, 'id_remesa' => 13, 'id_gasto' => 13, 'mes' => 'Julio', 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 14, 'id_gasto' => 14, 'mes' => 'Julio', 'anio' => $anio],

            // Agosto
            ['id_iglesia' => 1, 'id_remesa' => 15, 'id_gasto' => 15, 'mes' => 'Agosto', 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 16, 'id_gasto' => 16, 'mes' => 'Agosto', 'anio' => $anio],

            // Septiembre
            ['id_iglesia' => 1, 'id_remesa' => 17, 'id_gasto' => 17, 'mes' => 'Septiembre', 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 18, 'id_gasto' => 18, 'mes' => 'Septiembre', 'anio' => $anio],

            // Octubre
            ['id_iglesia' => 1, 'id_remesa' => 19, 'id_gasto' => 19, 'mes' => 'Octubre', 'anio' => $anio],
            ['id_iglesia' => 2, 'id_remesa' => 20, 'id_gasto' => 20, 'mes' => 'Octubre', 'anio' => $anio],
        ];

        DB::table('genera')->insert($genera);
    }
}
