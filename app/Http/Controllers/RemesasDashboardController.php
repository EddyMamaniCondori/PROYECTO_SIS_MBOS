<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RemesasDashboardController extends Controller
{
    public function index(){
        //DD('ENTREO AQUI WIIIII');

        $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

        $result =DB::select("
            select
                sum(case when xg.mes = 1 then xri.monto else 0 end) as monto_enero,
                sum(case when xg.mes = 2 then xri.monto else 0 end) as monto_febrero,
                sum(case when xg.mes = 3 then xri.monto else 0 end) as monto_marzo,
                sum(case when xg.mes = 4 then xri.monto else 0 end) as monto_abril,
                sum(case when xg.mes = 5 then xri.monto else 0 end) as monto_mayo,
                sum(case when xg.mes = 6 then xri.monto else 0 end) as monto_junio,
                sum(case when xg.mes = 7 then xri.monto else 0 end) as monto_julio,
                sum(case when xg.mes = 8 then xri.monto else 0 end) as monto_agosto,
                sum(case when xg.mes = 9 then xri.monto else 0 end) as monto_septiembre,
                sum(case when xg.mes = 10 then xri.monto else 0 end) as monto_octubre,
                sum(case when xg.mes = 11 then xri.monto else 0 end) as monto_noviembre,
                sum(case when xg.mes = 12 then xri.monto else 0 end) as monto_diciembre
                from iglesias xi
                join generas xg on xg.id_iglesia = xi.id_iglesia
                join remesas_iglesias xri on xg.id_remesa = xri.id_remesa
                where xg.anio = 2025
                and xi.distrito_id = 11;

        ");
        // $result es un array con 1 objeto, así que accedemos al primero
        $montos = $result[0];

        // Creamos el array con el orden correcto
        $datosRemesas = [
            $montos->monto_enero,
            $montos->monto_febrero,
            $montos->monto_marzo,
            $montos->monto_abril,
            $montos->monto_mayo,
            $montos->monto_junio,
            $montos->monto_julio,
            $montos->monto_agosto,
            $montos->monto_septiembre,
            $montos->monto_octubre,
            $montos->monto_noviembre,
            $montos->monto_diciembre,
        ];

        // Aquí defines el array estático del desafío (por ejemplo)
        $desafioAnual = 800000;
        $desafios = array_fill(0, 12, $desafioAnual);

        $series = [ //remesa del mes + su desafio
            ['name' => 'Desafío Anual', 'data' => $desafios],
            ['name' => 'Remesas Totales', 'data' => $datosRemesas],
        ];

        $series_mensual = [ // mostrar solo mes remesa
            ['name' => 'Remesas Totales', 'data' => $datosRemesas],
        ];

        $alcanzado = array_sum($datosRemesas);
        $diferencia = max($desafioAnual - $alcanzado, 0);

        $series_baras = [
                [
                    'name' => 'Blanco Anual',
                    'data' => [$desafioAnual],
                ],
                [
                    'name' => 'Alcanzado',
                    'data' => [$alcanzado],
                ],
                [
                    'name' => 'Diferencia',
                    'data' => [$diferencia],
                ],
            ];
        $categorias = ['2025']; 
        return view('remesas_dasboards.dashboard', compact('meses', 'series', 'series_mensual','series_baras', 'categorias'));
    }

    /**ejemplo */

    public function index_distrital(){
        //DD('ENTREO AQUI WIIIII');

        $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

        $result =DB::select("
            select
                sum(case when xg.mes = 1 then xri.monto else 0 end) as monto_enero,
                sum(case when xg.mes = 2 then xri.monto else 0 end) as monto_febrero,
                sum(case when xg.mes = 3 then xri.monto else 0 end) as monto_marzo,
                sum(case when xg.mes = 4 then xri.monto else 0 end) as monto_abril,
                sum(case when xg.mes = 5 then xri.monto else 0 end) as monto_mayo,
                sum(case when xg.mes = 6 then xri.monto else 0 end) as monto_junio,
                sum(case when xg.mes = 7 then xri.monto else 0 end) as monto_julio,
                sum(case when xg.mes = 8 then xri.monto else 0 end) as monto_agosto,
                sum(case when xg.mes = 9 then xri.monto else 0 end) as monto_septiembre,
                sum(case when xg.mes = 10 then xri.monto else 0 end) as monto_octubre,
                sum(case when xg.mes = 11 then xri.monto else 0 end) as monto_noviembre,
                sum(case when xg.mes = 12 then xri.monto else 0 end) as monto_diciembre
                from iglesias xi
                join generas xg on xg.id_iglesia = xi.id_iglesia
                join remesas_iglesias xri on xg.id_remesa = xri.id_remesa
                where xg.anio = 2025
                and xi.distrito_id = 11;

        ");
        // $result es un array con 1 objeto, así que accedemos al primero
        $montos = $result[0];

        // Creamos el array con el orden correcto
        $datosRemesas = [
            $montos->monto_enero,
            $montos->monto_febrero,
            $montos->monto_marzo,
            $montos->monto_abril,
            $montos->monto_mayo,
            $montos->monto_junio,
            $montos->monto_julio,
            $montos->monto_agosto,
            $montos->monto_septiembre,
            $montos->monto_octubre,
            $montos->monto_noviembre,
            $montos->monto_diciembre,
        ];

        // Aquí defines el array estático del desafío (por ejemplo)
        $desafioAnual = 800000;
        $desafios = array_fill(0, 12, $desafioAnual);

        $series = [ //remesa del mes + su desafio
            ['name' => 'Desafío Anual', 'data' => $desafios],
            ['name' => 'Remesas Totales', 'data' => $datosRemesas],
        ];

        $series_mensual = [ // mostrar solo mes remesa
            ['name' => 'Remesas Totales', 'data' => $datosRemesas],
        ];

        $alcanzado = array_sum($datosRemesas);
        $diferencia = max($desafioAnual - $alcanzado, 0);

        $series_baras = [
                [
                    'name' => 'Blanco Anual',
                    'data' => [$desafioAnual],
                ],
                [
                    'name' => 'Alcanzado',
                    'data' => [$alcanzado],
                ],
                [
                    'name' => 'Diferencia',
                    'data' => [$diferencia],
                ],
            ];
        $categorias = ['2025']; 
        return view('remesas_dasboards.dashboard_distrital', compact('meses', 'series', 'series_mensual','series_baras', 'categorias'));
    }









     public function dashboard()
    {
        $result = DB::table('desafio_mensuales')
                ->select('mes', 'desafio_bautiso', 'bautisos_alcanzados')
                ->where('iglesia_id', 1)
                ->where('pastor_id', 1)
                ->where('anio', 2025)
                ->orderByRaw("
                    CASE mes
                        WHEN 'enero' THEN 1
                        WHEN 'febrero' THEN 2
                        WHEN 'marzo' THEN 3
                        WHEN 'abril' THEN 4
                        WHEN 'mayo' THEN 5
                        WHEN 'junio' THEN 6
                        WHEN 'julio' THEN 7
                        WHEN 'agosto' THEN 8
                        WHEN 'septiembre' THEN 9
                        WHEN 'octubre' THEN 10
                        WHEN 'noviembre' THEN 11
                        WHEN 'diciembre' THEN 12
                    END
                ")
                ->get();


        // Convertimos a arrays separados
        $meses = $result->pluck('mes');                 // ['enero','febrero',...]
        $desafios = $result->pluck('desafio_bautiso'); // [28,48,40,...]
        $alcanzados = $result->pluck('bautisos_alcanzados'); // [65,59,80,...]

        return view('bautisos.dashboard', compact('meses','desafios','alcanzados'));
    }
}
