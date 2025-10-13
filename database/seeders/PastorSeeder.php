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
                'id_pastor' => 1, // Henry Coronel
                'fecha_ordenacion' => '2010-03-15',
                'ordenado' => true,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '2009-01-01',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 2, // Franz Ciro Valdez
                'fecha_ordenacion' => '2015-07-22',
                'ordenado' => true,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '2014-02-10',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 3, // Ronald Moya
                'fecha_ordenacion' => '2018-11-05',
                'ordenado' => true,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '2018-01-15',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 4, // Jhon Mamani
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '2020-05-01',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 5, // Juan Carlos Lima
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => null,
                'contratado' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 6, // Manuel Morante
                'fecha_ordenacion' => '2016-06-11',
                'ordenado' => true,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '2015-02-15',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 7, // Noel Larico
                'fecha_ordenacion' => null,
                'ordenado' => false,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => '2021-01-25',
                'contratado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pastor' => 8, // David Gutierrez
                'fecha_ordenacion' => '2019-04-19',
                'ordenado' => true,
                'cargo' => 'Pastor Distrital',
                'nro_distritos' => 0,
                'fecha_contratacion' => null,
                'contratado' => false,
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
