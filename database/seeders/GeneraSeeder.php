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

        $genera = [
            // Enero
            ['id_iglesia' => 1, 'id_remesa' => 1, 'mes' => 12, 'anio' => 2022],
        ];

        DB::table('generas')->insert($genera);
    }
}
