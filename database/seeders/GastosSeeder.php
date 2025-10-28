<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
use Carbon\Carbon;

class GastosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anio = 2025;
        $meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
            'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre'
        ];

        $gastos = [];

        foreach ($meses as $mes) {
            for ($i = 1; $i <= 2; $i++) {
                $monto = number_format(rand(500, 5000) + (rand(0, 9999) / 10000), 4, '.', '');

                $gastos[] = [
                    'monto'       => $monto,
                    'observacion' => "ya sabes gastos",
                    'mes'         => $mes,
                    'anio'         => $anio,
                ];
            }
        }

        DB::table('gastos')->insert($gastos);
    }
}
