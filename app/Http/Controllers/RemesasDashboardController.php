<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Distrito; 
class RemesasDashboardController extends Controller
{

    /**
     * 'ver-remesas dashboard',
     */
    function __construct()
    {
        // index() y index_distrital(): permission 'ver-remesas dashboard'
        // Asumo que ambas funciones son vistas principales de dashboards que usan el mismo permiso de visualización general.
        $this->middleware('permission:ver-remesas dashboard', ['only' => ['index', 'index_distrital', 'dashboard']]);
        
        // dashboard() no tiene etiqueta explícita de permiso.
        $this->middleware('permission:ver dashboar pastor-remesas dashboard', ['only' => ['dashboard_finanzas_distrito']]);
        $this->middleware('permission:ver dashboar remesas filiales pastor-remesas dashboard', ['only' => ['dashboard_finanzas_filiales_distrito']]);
        $this->middleware('permission:ver dashboar fondo local pastor-remesas dashboard', ['only' => ['dashboard_fondo_local_filiales_distrito']]);
    }
    public function index(){ 
        
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

    public function index_distrital()
    {
        $anio = 2025;
        $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

        $result = DB::select("
            SELECT
                xd.id_distrito,
                xd.nombre AS nombre_distrito,
                COALESCE(xbr.monto, 0) AS blanco_monto,
                SUM(CASE WHEN xg.mes = 1  THEN xri.monto ELSE 0 END) AS monto_enero,
                SUM(CASE WHEN xg.mes = 2  THEN xri.monto ELSE 0 END) AS monto_febrero,
                SUM(CASE WHEN xg.mes = 3  THEN xri.monto ELSE 0 END) AS monto_marzo,
                SUM(CASE WHEN xg.mes = 4  THEN xri.monto ELSE 0 END) AS monto_abril,
                SUM(CASE WHEN xg.mes = 5  THEN xri.monto ELSE 0 END) AS monto_mayo,
                SUM(CASE WHEN xg.mes = 6  THEN xri.monto ELSE 0 END) AS monto_junio,
                SUM(CASE WHEN xg.mes = 7  THEN xri.monto ELSE 0 END) AS monto_julio,
                SUM(CASE WHEN xg.mes = 8  THEN xri.monto ELSE 0 END) AS monto_agosto,
                SUM(CASE WHEN xg.mes = 9  THEN xri.monto ELSE 0 END) AS monto_septiembre,
                SUM(CASE WHEN xg.mes = 10 THEN xri.monto ELSE 0 END) AS monto_octubre,
                SUM(CASE WHEN xg.mes = 11 THEN xri.monto ELSE 0 END) AS monto_noviembre,
                SUM(CASE WHEN xg.mes = 12 THEN xri.monto ELSE 0 END) AS monto_diciembre,
                SUM(xri.monto) AS total_anual
            FROM iglesias xi
            JOIN generas xg ON xg.id_iglesia = xi.id_iglesia
            JOIN remesas_iglesias xri ON xg.id_remesa = xri.id_remesa
            LEFT JOIN distritos xd ON xi.distrito_id = xd.id_distrito
            LEFT JOIN blanco_remesas xbr ON xbr.id_distrito = xd.id_distrito
            WHERE xg.anio = ?
            GROUP BY xd.id_distrito, xd.nombre, xbr.monto
            ORDER BY xd.nombre;
        ", [$anio]);

        // Construimos datos por distrito
        $dataDistritos = [];
        foreach ($result as $row) {
            $datosRemesas = [
                $row->monto_enero, $row->monto_febrero, $row->monto_marzo,
                $row->monto_abril, $row->monto_mayo, $row->monto_junio,
                $row->monto_julio, $row->monto_agosto, $row->monto_septiembre,
                $row->monto_octubre, $row->monto_noviembre, $row->monto_diciembre
            ];

            $alcanzado = array_sum($datosRemesas);
            $desafioAnual = $row->blanco_monto ?: 0; // valor por defecto si no tiene blanco
            $diferencia = max($desafioAnual - $alcanzado, 0); //garantisa una diferencia >0

            $dataDistritos[] = [
                'nombre_distrito' => $row->nombre_distrito,
                'series_mensual' => [
                    ['name' => 'Remesas Totales', 'data' => $datosRemesas],
                ],
                'series_baras' => [
                    ['name' => 'Blanco Anual', 'data' => [$desafioAnual]],
                    ['name' => 'Alcanzado', 'data' => [$alcanzado]],
                    ['name' => 'Diferencia', 'data' => [$diferencia]],
                ],
                'totales' => [
                    'desafio' => $desafioAnual,
                    'alcanzado' => $alcanzado,
                    'diferencia' => $diferencia
                ]
            ];
        }
        //dd($dataDistritos);
        return view('remesas_dasboards.dashboard_distrital', compact('meses', 'dataDistritos'));
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

    //con esto obtnemos 
    public function dashboard_finanzas_distrito() //permision'ver dashboar pastor-remesas dashboard',
    {
        $persona = Auth::user(); 
        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;

        $anio = 2025;
        //$id_distrito =11;
        /* ============================================================
            1) TABLA DINÁMICA — REMESAS POR IGLESIA (MESES)
        ============================================================ */

        $remesasMes = DB::select("
            SELECT
                xi.codigo,
                xi.nombre AS nombre_iglesia,
                MAX(CASE WHEN xg.mes = 1 THEN xri.monto ELSE 0 END) AS mes_enero,
                MAX(CASE WHEN xg.mes = 2 THEN xri.monto ELSE 0 END) AS mes_febrero,
                MAX(CASE WHEN xg.mes = 3 THEN xri.monto ELSE 0 END) AS mes_marzo,
                MAX(CASE WHEN xg.mes = 4 THEN xri.monto ELSE 0 END) AS mes_abril,
                MAX(CASE WHEN xg.mes = 5 THEN xri.monto ELSE 0 END) AS mes_mayo,
                MAX(CASE WHEN xg.mes = 6 THEN xri.monto ELSE 0 END) AS mes_junio,
                MAX(CASE WHEN xg.mes = 7 THEN xri.monto ELSE 0 END) AS mes_julio,
                MAX(CASE WHEN xg.mes = 8 THEN xri.monto ELSE 0 END) AS mes_agosto,
                MAX(CASE WHEN xg.mes = 9 THEN xri.monto ELSE 0 END) AS mes_septiembre,
                MAX(CASE WHEN xg.mes = 10 THEN xri.monto ELSE 0 END) AS mes_octubre,
                MAX(CASE WHEN xg.mes = 11 THEN xri.monto ELSE 0 END) AS mes_noviembre,
                MAX(CASE WHEN xg.mes = 12 THEN xri.monto ELSE 0 END) AS mes_diciembre
            FROM iglesias xi
            JOIN generas xg ON xi.id_iglesia = xg.id_iglesia
            JOIN remesas_iglesias xri ON xg.id_remesa = xri.id_remesa
            WHERE xi.distrito_id = ?
            AND xg.anio = ?
            GROUP BY xi.codigo, xi.nombre
            ORDER BY xi.codigo;
        ", [$id_distrito, $anio]);

        /* ============================================================
            2) BLANCO DEL DISTRITO
        ============================================================ */

        $blanco = DB::table('blanco_remesas')
            ->where('id_distrito', $id_distrito)
            ->where('anio', $anio)
            ->value('monto') ?? 0;

        /* ============================================================
            3) ALCANZADO (TOTAL REMESAS DE TODAS LAS IGLESIAS)
        ============================================================ */

        $totalAlcanzado = DB::table('iglesias as xi')
            ->join('generas as xg', 'xi.id_iglesia', '=', 'xg.id_iglesia')
            ->join('remesas_iglesias as xri', 'xg.id_remesa', '=', 'xri.id_remesa')
            ->where('xi.distrito_id', $id_distrito)
            ->where('xg.anio', $anio)
            ->sum('xri.monto');

        $diferencia = $blanco - $totalAlcanzado;
        //dd($blanco);
        /* ============================================================
            4) ARMAR DATOS PARA LAS GRÁFICAS
        ============================================================ */

        $graficoResumen = [
            'categorias' => ['Blanco', 'Alcanzado', 'Diferencia'],
            'valores'    => [
                (float)$blanco,
                (float)$totalAlcanzado,
                (float)$diferencia
            ]
        ];

        return view('remesas_dasboards.dashboard_pastor_distrital', [
            'tablaMeses'    => $remesasMes,
            'graficoResumen'=> $graficoResumen,
            'blanco' => $blanco,
            'alcanzado' => $totalAlcanzado,
        ]);
    }
    //muestra las remesas de las filiales de 1 distrito
    public function dashboard_finanzas_filiales_distrito() //permision 'ver dashboar remesas filiales pastor-remesas dashboard',
    {
        $persona = Auth::user(); 
        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;

        $anio = 2025;
        //$id_distrito =1;
        /* ============================================================
            1) TABLA DINÁMICA — REMESAS POR IGLESIA (MESES)
        ============================================================ */

        $remesasMes = DB::select("
            SELECT
                xi.id_iglesia,
                xi.codigo,
                xi.nombre AS nombre_iglesia,
                MAX(CASE WHEN xg.mes = 1 THEN xri.monto_remesa ELSE 0 END) AS mes_enero,
                MAX(CASE WHEN xg.mes = 2 THEN xri.monto_remesa ELSE 0 END) AS mes_febrero,
                MAX(CASE WHEN xg.mes = 3 THEN xri.monto_remesa ELSE 0 END) AS mes_marzo,
                MAX(CASE WHEN xg.mes = 4 THEN xri.monto_remesa ELSE 0 END) AS mes_abril,
                MAX(CASE WHEN xg.mes = 5 THEN xri.monto_remesa ELSE 0 END) AS mes_mayo,
                MAX(CASE WHEN xg.mes = 6 THEN xri.monto_remesa ELSE 0 END) AS mes_junio,
                MAX(CASE WHEN xg.mes = 7 THEN xri.monto_remesa ELSE 0 END) AS mes_julio,
                MAX(CASE WHEN xg.mes = 8 THEN xri.monto_remesa ELSE 0 END) AS mes_agosto,
                MAX(CASE WHEN xg.mes = 9 THEN xri.monto_remesa ELSE 0 END) AS mes_septiembre,
                MAX(CASE WHEN xg.mes = 10 THEN xri.monto_remesa ELSE 0 END) AS mes_octubre,
                MAX(CASE WHEN xg.mes = 11 THEN xri.monto_remesa ELSE 0 END) AS mes_noviembre,
                MAX(CASE WHEN xg.mes = 12 THEN xri.monto_remesa ELSE 0 END) AS mes_diciembre
            FROM iglesias xi
            JOIN generas xg ON xi.id_iglesia = xg.id_iglesia
            JOIN remesas_filiales xri ON xg.id_remesa = xri.id_remesa
            WHERE xi.distrito_id = ?
            AND xg.anio = ?
            GROUP BY xi.id_iglesia, xi.codigo, xi.nombre
            ORDER BY xi.codigo;
        ", [$id_distrito, $anio]);


        /* ============================================================
            4) ARMAR DATOS PARA LAS GRÁFICAS
        ============================================================ */


        return view('remesas_dasboards.dashboard_filiales_pastor_distrital', [
            'tablaMeses'    => $remesasMes,
        ]);
    }

    public function dashboard_fondo_local_filiales_distrito()//permision'ver dashboar fondo local pastor-remesas dashboard',
    {
        $persona = Auth::user(); 
        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;

        $anio = 2025;
        //$id_distrito =1;
        /* ============================================================
            1) TABLA DINÁMICA — REMESAS POR IGLESIA (MESES)
        ============================================================ */

        $remesasMes = DB::select("
            SELECT
                xi.id_iglesia,
                xi.codigo,
                xi.nombre AS nombre_iglesia,
                MAX(CASE WHEN xg.mes = 1 THEN xri.fondo_local ELSE 0 END) AS mes_enero,
                MAX(CASE WHEN xg.mes = 2 THEN xri.fondo_local ELSE 0 END) AS mes_febrero,
                MAX(CASE WHEN xg.mes = 3 THEN xri.fondo_local ELSE 0 END) AS mes_marzo,
                MAX(CASE WHEN xg.mes = 4 THEN xri.fondo_local ELSE 0 END) AS mes_abril,
                MAX(CASE WHEN xg.mes = 5 THEN xri.fondo_local ELSE 0 END) AS mes_mayo,
                MAX(CASE WHEN xg.mes = 6 THEN xri.fondo_local ELSE 0 END) AS mes_junio,
                MAX(CASE WHEN xg.mes = 7 THEN xri.fondo_local ELSE 0 END) AS mes_julio,
                MAX(CASE WHEN xg.mes = 8 THEN xri.fondo_local ELSE 0 END) AS mes_agosto,
                MAX(CASE WHEN xg.mes = 9 THEN xri.fondo_local ELSE 0 END) AS mes_septiembre,
                MAX(CASE WHEN xg.mes = 10 THEN xri.fondo_local ELSE 0 END) AS mes_octubre,
                MAX(CASE WHEN xg.mes = 11 THEN xri.fondo_local ELSE 0 END) AS mes_noviembre,
                MAX(CASE WHEN xg.mes = 12 THEN xri.fondo_local ELSE 0 END) AS mes_diciembre
            FROM iglesias xi
            JOIN generas xg ON xi.id_iglesia = xg.id_iglesia
            JOIN remesas_filiales xri ON xg.id_remesa = xri.id_remesa
            WHERE xi.distrito_id = ?
            AND xg.anio = ?
            GROUP BY xi.id_iglesia, xi.codigo, xi.nombre
            ORDER BY xi.codigo;
        ", [$id_distrito, $anio]);


        /* ============================================================
            4) ARMAR DATOS PARA LAS GRÁFICAS
        ============================================================ */


        return view('remesas_dasboards.dash_filial_fondo_local_pastor_distrital', [
            'tablaMeses'    => $remesasMes,
        ]);
    }

    
}
