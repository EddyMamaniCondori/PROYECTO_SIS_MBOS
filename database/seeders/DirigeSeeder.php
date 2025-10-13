<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
use Carbon\Carbon;

class DirigeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            // ---------------------- GESTIÓN 2022 ----------------------
            [
                'id_distrito' => 1,
                'id_pastor' => 1,
                'fecha_asignacion' => Carbon::create(2022, 1, 1),
                'fecha_finalizacion' => Carbon::create(2024, 12, 31),
                'año' => 2022,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 2,
                'id_pastor' => 2,
                'fecha_asignacion' => Carbon::create(2022, 1, 1),
                'fecha_finalizacion' => Carbon::create(2023, 12, 31),
                'año' => 2022,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 3,
                'id_pastor' => 3,
                'fecha_asignacion' => Carbon::create(2022, 1, 1),
                'fecha_finalizacion' => null,
                'año' => 2022,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 4,
                'id_pastor' => 4,
                'fecha_asignacion' => Carbon::create(2022, 1, 1),
                'fecha_finalizacion' => Carbon::create(2023, 12, 31),
                'año' => 2022,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 5,
                'id_pastor' => 5,
                'fecha_asignacion' => Carbon::create(2022, 1, 1),
                'fecha_finalizacion' => Carbon::create(2024, 12, 31),
                'año' => 2022,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 6,
                'id_pastor' => 6,
                'fecha_asignacion' => Carbon::create(2022, 1, 1),
                'fecha_finalizacion' => null,
                'año' => 2022,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 7,
                'id_pastor' => 7,
                'fecha_asignacion' => Carbon::create(2022, 1, 1),
                'fecha_finalizacion' => Carbon::create(2023, 12, 31),
                'año' => 2022,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ---------------------- GESTIÓN 2023 ----------------------
            [
                'id_distrito' => 1,
                'id_pastor' => 1,
                'fecha_asignacion' => Carbon::create(2023, 1, 1),
                'fecha_finalizacion' => Carbon::create(2024, 12, 31),
                'año' => 2023,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 2,
                'id_pastor' => 8,
                'fecha_asignacion' => Carbon::create(2023, 1, 1),
                'fecha_finalizacion' => null,
                'año' => 2023,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 3,
                'id_pastor' => 3,
                'fecha_asignacion' => Carbon::create(2023, 1, 1),
                'fecha_finalizacion' => null,
                'año' => 2023,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 4,
                'id_pastor' => 4,
                'fecha_asignacion' => Carbon::create(2023, 1, 1),
                'fecha_finalizacion' => Carbon::create(2025, 12, 31),
                'año' => 2023,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 5,
                'id_pastor' => 5,
                'fecha_asignacion' => Carbon::create(2023, 1, 1),
                'fecha_finalizacion' => null,
                'año' => 2023,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 6,
                'id_pastor' => 6,
                'fecha_asignacion' => Carbon::create(2023, 1, 1),
                'fecha_finalizacion' => Carbon::create(2025, 12, 31),
                'año' => 2023,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 7,
                'id_pastor' => 7,
                'fecha_asignacion' => Carbon::create(2023, 1, 1),
                'fecha_finalizacion' => null,
                'año' => 2023,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ---------------------- GESTIÓN 2024 ----------------------
            [
                'id_distrito' => 1,
                'id_pastor' => 1,
                'fecha_asignacion' => Carbon::create(2024, 1, 1),
                'fecha_finalizacion' => Carbon::create(2025, 12, 31),
                'año' => 2024,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 2,
                'id_pastor' => 8,
                'fecha_asignacion' => Carbon::create(2024, 1, 1),
                'fecha_finalizacion' => Carbon::create(2025, 12, 31),
                'año' => 2024,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 3,
                'id_pastor' => 3,
                'fecha_asignacion' => Carbon::create(2024, 1, 1),
                'fecha_finalizacion' => null,
                'año' => 2024,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 4,
                'id_pastor' => 4,
                'fecha_asignacion' => Carbon::create(2024, 1, 1),
                'fecha_finalizacion' => Carbon::create(2025, 12, 31),
                'año' => 2024,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 5,
                'id_pastor' => 5,
                'fecha_asignacion' => Carbon::create(2024, 1, 1),
                'fecha_finalizacion' => null,
                'año' => 2024,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 6,
                'id_pastor' => 6,
                'fecha_asignacion' => Carbon::create(2024, 1, 1),
                'fecha_finalizacion' => Carbon::create(2025, 12, 31),
                'año' => 2024,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_distrito' => 7,
                'id_pastor' => 7,
                'fecha_asignacion' => Carbon::create(2024, 1, 1),
                'fecha_finalizacion' => null,
                'año' => 2024,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('diriges')->insert($datos);
    }
}
