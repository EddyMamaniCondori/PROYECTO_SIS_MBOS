<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
use Carbon\Carbon;

class DistritoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Crear los 7 distritos con sus respectivos pastores
        DB::table('distritos')->insert([
            'nombre' => 'Bolivar',
            'nro_iglesias' => 3,
            'id_pastor' => 1,
            'id_grupo' => 1,
            'sw_cambio' => false,
            'sw_estado' => false,
            'año' => '2025',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('distritos')->insert([
            'nombre' => 'Cosmos',
            'nro_iglesias' => 2,
            'id_pastor' => 2,
            'id_grupo' => 1,
            'sw_cambio' => false,
            'sw_estado' => false,
            'año' => '2025',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('distritos')->insert([
            'nombre' => 'La Hermosa',
            'nro_iglesias' => 4,
            'id_pastor' => 3,
            'id_grupo' => 2,
            'sw_cambio' => false,
            'sw_estado' => false,
            'año' => '2025',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('distritos')->insert([
            'nombre' => 'Maranatha',
            'nro_iglesias' => 3,
            'id_pastor' => 4,
            'id_grupo' => 2,
            'sw_cambio' => false,
            'sw_estado' => false,
            'año' => '2025',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('distritos')->insert([
            'nombre' => 'Pacajes',
            'nro_iglesias' => 2,
            'id_pastor' => 5,
            'id_grupo' => 1,
            'sw_cambio' => false,
            'sw_estado' => false,
            'año' => '2025',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('distritos')->insert([
            'nombre' => 'Santa Rosa',
            'nro_iglesias' => 3,
            'id_pastor' => 6,
            'id_grupo' => 2,
            'sw_cambio' => false,
            'sw_estado' => false,
            'año' => '2025',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('distritos')->insert([
            'nombre' => 'Villa Adela',
            'nro_iglesias' => 4,
            'id_pastor' => 7,
            'id_grupo' => 2,
            'sw_cambio' => false,
            'sw_estado' => false,
            'año' => '2025',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
