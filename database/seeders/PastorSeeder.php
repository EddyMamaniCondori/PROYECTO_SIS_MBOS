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
                'id_pastor' => 1,
                'fecha_ordenacion' => '2010-03-12',
                'ordenado' => true,
                'cargo' => 'Pastor Principal',
                'nro_distritos' => 3,
            ],
            [
                'id_pastor' => 2,
                'fecha_ordenacion' => '2015-07-20',
                'ordenado' => true,
                'cargo' => 'Asistente de Distrito',
                'nro_distritos' => 2,
            ],
            [
                'id_pastor' => 3,
                'fecha_ordenacion' => '2012-11-05',
                'ordenado' => true,
                'cargo' => 'Pastor de Jóvenes',
                'nro_distritos' => 1,
            ],
            [
                'id_pastor' => 4,
                'fecha_ordenacion' => '2018-02-14',
                'ordenado' => false,
                'cargo' => 'Evangelista',
                'nro_distritos' => 1,
            ],
            [
                'id_pastor' => 5,
                'fecha_ordenacion' => '2016-09-01',
                'ordenado' => true,
                'cargo' => 'Pastor Asociado',
                'nro_distritos' => 2,
            ],
            [
                'id_pastor' => 6,
                'fecha_ordenacion' => '2020-04-10',
                'ordenado' => false,
                'cargo' => 'Líder de Ministerio',
                'nro_distritos' => 1,
            ],
            [
                'id_pastor' => 7,
                'fecha_ordenacion' => '2011-12-30',
                'ordenado' => true,
                'cargo' => 'Supervisor Regional',
                'nro_distritos' => 4,
            ],
        ];

        foreach ($pastores as $pastor) {
            DB::table('pastors')->insert([
                'id_pastor' => $pastor['id_pastor'],
                'fecha_ordenacion' => $pastor['fecha_ordenacion'],
                'ordenado' => $pastor['ordenado'],
                'cargo' => $pastor['cargo'],
                'nro_distritos' => $pastor['nro_distritos'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
