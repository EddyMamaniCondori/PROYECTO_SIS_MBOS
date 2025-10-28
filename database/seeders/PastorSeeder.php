<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
use Carbon\Carbon;

class PastorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pastores = [
            [
                'id_pastor' => 3, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2021',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 4, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2011',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 5, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2022',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 6, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2022',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 7, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2024',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 8, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2014',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 9, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2009',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 10, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2020',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 11, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2024',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 12, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2025',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 13, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2011',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 14, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2016',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 15, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2010',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 16, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2016',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 17, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2022',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 18, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2022',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 19, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2023',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 20, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-08-2021',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 21, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2010',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 22, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2023',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 23, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2022',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 24, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2003',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 25, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2014',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 26, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2025',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 27, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2017',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 28, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2010',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 29, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2021',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 30, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-1997',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 31, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2022',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 32, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-02-2023',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 33, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2021',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 34, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-02-2023',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 35, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-02-2023',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 36, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2025',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 37, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2023',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 38, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2019',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 39, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-09-2021',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 40, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-01-2015',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 41, // Henry Coronel
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '01-02-2023',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
        ];
        foreach ($pastores as $pastor) {
            DB::table('pastors')->insert([
                'id_pastor' => $pastor['id_pastor'],
                'fecha_ordenacion' => $pastor['fecha_ordenacion'],
                'ordenado' => $pastor['ordenado'],
                'cargo' => $pastor['cargo'],
                'nro_distritos' => $pastor['nro_distritos'],
                'fecha_contratacion' => Carbon::now()->subYears(5)->format('Y-m-d'), // ejemplo de fecha
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
