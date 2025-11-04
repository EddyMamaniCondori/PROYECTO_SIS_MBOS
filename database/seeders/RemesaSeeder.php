<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
use Carbon\Carbon;
class RemesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
            'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        $remesas = [];

        foreach ($meses as $index => $mes) {
            $numeroMes = $index + 1;

            for ($i = 1; $i <= 2; $i++) {

                // Fecha límite: entre el día 15 y 20
                $fechaLimite = Carbon::create(2025, $numeroMes, 20);

                // Fecha entrega: de 2 días antes a 2 días después del límite
                $fechaEntrega = (clone $fechaLimite)->addDays(rand(-2, 2));

                // Diferencia en días
                $diferencia = $fechaEntrega->diffInDays($fechaLimite, false); // negativo si es después

                // Estado según la diferencia
                if ($diferencia >= 0) {
                    $estado = "Sin atraso ({$diferencia} días antes)";
                } else {
                    $estado = "Con retraso (" . abs($diferencia) . " días)";
                }

                $remesas[] = [
                    'cierre'         => (bool)rand(0, 1),
                    'deposito'       => (bool)rand(0, 1),
                    'documentacion'  => (bool)rand(0, 1),
                    'fecha_entrega'  => $fechaEntrega->toDateString(),
                    'fecha_limite'   => $fechaLimite->toDateString(),
                    'estado'         => $estado,
                    'observacion'    => "Remesa {$i} correspondiente al mes de {$mes}",
                ];
            }
        }

        DB::table('remesas')->insert($remesas);
    }
}
