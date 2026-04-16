<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait CalculaSaldoTrait 
{  
    private function recalcularSaldosDesde($id_iglesia, $anio, $mes) //VERIFICADA
    {
        // 1. Valor de referencia numérico para comparar fechas (AAAAMM)
        $valorReferencia = ($anio * 100) + $mes;

        // 2. Buscamos el saldo final del mes anterior al que estamos registrando
        $ultimoSaldo = DB::table('generas as xg')
            ->join('remesas_filiales as xf', 'xg.id_remesa', '=', 'xf.id_remesa')
            ->where('xg.id_iglesia', $id_iglesia)
            ->whereRaw("(xg.anio * 100 + xg.mes) < ?", [$valorReferencia])
            ->orderBy('xg.anio', 'desc')
            ->orderBy('xg.mes', 'desc')
            ->value('xf.fondo_l_final') ?? 0;

        // 3. Obtenemos todas las remesas desde el mes actual hacia el futuro
        $remesasFuturas = DB::table('generas as xg')
            ->join('remesas_filiales as xf', 'xg.id_remesa', '=', 'xf.id_remesa')
            ->where('xg.id_iglesia', $id_iglesia)
            ->whereRaw("(xg.anio * 100 + xg.mes) >= ?", [$valorReferencia])
            ->orderBy('xg.anio', 'asc')
            ->orderBy('xg.mes', 'asc')
            ->select('xf.id_remesa', 'xf.fondo_local', 'xf.gasto') // fondo_local es el INGRESO del mes
            ->get();

        $saldoArrastrado = $ultimoSaldo;

        // 4. Actualizamos una por una (Efecto Dominó)
        foreach ($remesasFuturas as $rf) {
            $fondoLFinal = $saldoArrastrado + $rf->fondo_local - $rf->gasto;

            DB::table('remesas_filiales')
                ->where('id_remesa', $rf->id_remesa)
                ->update([
                    'fondo_l_anterior' => $saldoArrastrado,
                    'fondo_l_final'    => $fondoLFinal
                ]);

            $saldoArrastrado = $fondoLFinal;
        }

        // 5. El último saldo calculado es el que queda como saldo actual de la Iglesia
        DB::table('iglesias')
            ->where('id_iglesia', $id_iglesia)
            ->update(['fondo_local' => $saldoArrastrado]);
    }
}