<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Distrito;
use App\Models\Desafio;
use App\Models\Mensual;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
    /**
     * 'ver dashboard pastores-panel',
     * ver avance pastores-panel
     */
    function __construct()
    {
        // dashboard_pastores(): permision 'ver dashboard pastores - panel'
        $this->middleware('permission:ver dashboard pastores-panel', ['only' => ['dashboard_pastores']]);
        $this->middleware('permission:ver avance pastores-panel', ['only' => ['ver_avance_pastores']]);
    }

    public function dashboard_pastores() //permision 'ver dashboard pastores - panel',
    {
        $anio = now()->year;
        $persona = Auth::user(); 
        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        
        if (!$distrito) {
            return redirect()->route('perfil')
                ->with('error', 'No tienes un distrito asignado. ¡Comunícate con el Administrador!');
        }
        $id_distrito = $distrito->id_distrito;

        /**datos para los cards
         */
         $resumenIglesias = DB::table('iglesias')
        ->select(
            DB::raw("COUNT(*) as total_iglesias"),
            DB::raw("SUM(CASE WHEN tipo = 'Iglesia' THEN 1 ELSE 0 END) as total_iglesia"),
            DB::raw("SUM(CASE WHEN tipo = 'Grupo' THEN 1 ELSE 0 END) as total_grupo"),
            DB::raw("SUM(CASE WHEN tipo = 'Filial' THEN 1 ELSE 0 END) as total_filial")
        )
        ->where('distrito_id', $id_distrito)
        ->first();

        /**DATOS DE BAUTISOS DEL DISTRITO */
        $bautiso = DB::table('desafios as xd')
            ->join('distritos as xdd', 'xd.id_distrito', '=', 'xdd.id_distrito')
            ->where('xdd.id_distrito', $id_distrito)
            ->where('xd.anio', $anio)
            ->select(
                'xdd.id_distrito',
                'xdd.nombre as nombre_distrito',
                'xd.desafio_bautizo',
                'xd.bautizos_alcanzados',
                DB::raw('(xd.desafio_bautizo - xd.bautizos_alcanzados) as diferencia')
            )
            ->first();


        $graficos_final = [
            'categorias' => ['Desafío', 'Alcanzado', 'Diferencia'],
            'valores' => [
                (int) $bautiso?->desafio_bautizo,
                (int) $bautiso?->bautizos_alcanzados,
                (int) $bautiso?->diferencia
            ]
        ];
        /**DATOS DE VISITAS DEL DISTRITO */
        // Obtener el desafío anual del distrito
        $desafio = Desafio::where('anio', $anio)
            ->where('id_distrito', $id_distrito)
            ->first();
        if (!$desafio) {
            return redirect()->back()->with('error', 'Desafío anual no encontrado.');
        }
        $mensuales = Mensual::where('id_desafio', $desafio->id_desafio)
            ->orderBy('mes') // importante para que los meses estén en orden
            ->get();
        $mensuales = $mensuales->filter(fn($m) => $m->mes <= now()->month); // SOLO LOS DESAFIOS DE HASTA EL MES ACTUAL

        $meses = [];
        $desafios = [];
        $alcanzados = [];
        $nombresMeses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        foreach ($mensuales as $m) {
            $meses[] = $nombresMeses[$m->mes] ?? 'Desconocido';
            $desafios[] = (int) $m->desafio_visitas;
            $alcanzados[] = (int) $m->visitas_alcanzadas;
        }  
        //DATOS DE INSTRUCTORES Y ESTUDIANTES

       $totales = DB::table('desafios as xd')
            ->join('anual_iglesias as xai', 'xd.id_desafio', '=', 'xai.id_desafio')
            ->where('xd.id_distrito', $id_distrito)
            ->where('xd.anio', $anio)
            ->select(
                DB::raw('SUM(xai.desafio_estudiantes) as total_desafio_estudiantes'),
                DB::raw('SUM(xai.estudiantes_alcanzados) as total_estudiantes_alcanzados'),
                DB::raw('SUM(xai.desafio_instructores) as total_desafio_instructores'),
                DB::raw('SUM(xai.instructores_alcanzados) as total_instructores_alcanzados'),
                DB::raw('SUM(xai.desafio_estudiantes - xai.estudiantes_alcanzados) as diferencia_estudiantes'),
                DB::raw('SUM(xai.desafio_instructores - xai.instructores_alcanzados) as diferencia_instructores')
            )
            ->first();
        $grafico_estudiantes = [
            'categorias' => ['Desafío', 'Alcanzado', 'Diferencia'],
            'valores' => [
                (int) $totales->total_desafio_estudiantes,
                (int) $totales->total_estudiantes_alcanzados,
                (int) $totales->diferencia_estudiantes,
            ]
        ];

        $grafico_instructores = [
            'categorias' => ['Desafío', 'Alcanzado', 'Diferencia'],
            'valores' => [
                (int) $totales->total_desafio_instructores,
                (int) $totales->total_instructores_alcanzados,
                (int) $totales->diferencia_instructores,
            ]
        ];

        return view('dashboards.dashboard_pastores', compact('resumenIglesias', 'graficos_final', 'distrito','meses', 'desafios', 'alcanzados', 'grafico_estudiantes', 'grafico_instructores' ));
    }
    //MBOS - puede ver el avance en graficos de los pastores
    public function ver_avance_pastores($id, $anio) //permision 'ver avanve pastores - panel',
    {
        $distrito = Distrito::findOrFail($id);
        $pastor = Persona::findOrFail($distrito->id_pastor);
        if (!$distrito) {
            return redirect()->route('panel')
                ->with('error', 'No pudimos encontra al Distrito en el refistro');
        }

        $id_distrito = $distrito->id_distrito;
        /**DATOS DE IGLESIA Del Distrito  **/
        $resumenIglesias = DB::table('iglesias') //ajustar solo junciona para el año actual
        ->select(
            DB::raw("COUNT(*) as total_iglesias"),
            DB::raw("SUM(CASE WHEN tipo = 'Iglesia' THEN 1 ELSE 0 END) as total_iglesia"),
            DB::raw("SUM(CASE WHEN tipo = 'Grupo' THEN 1 ELSE 0 END) as total_grupo"),
            DB::raw("SUM(CASE WHEN tipo = 'Filial' THEN 1 ELSE 0 END) as total_filial")
        )
        ->where('distrito_id', $id_distrito)
        ->first();
        //datos de bautismos por tipos
        $tipos = DB::table('distritos as xd')
            ->leftJoin('iglesias as xi', 'xi.distrito_id', '=', 'xd.id_distrito')
            ->leftJoin('bautisos as xb', function ($join) use ($anio) {
                $join->on('xb.id_iglesia', '=', 'xi.id_iglesia')
                    ->whereRaw('EXTRACT(YEAR FROM xb.fecha_bautizo) = ?', [$anio]);
            })
            ->where('xd.estado', true)
            ->where('xd.id_distrito', $id_distrito) // <-- Filtro por distrito específico
            ->select(
                'xd.id_distrito',
                'xd.nombre as nombre_distrito',
                DB::raw("COALESCE(SUM(CASE WHEN xb.tipo = 'bautizo' THEN 1 ELSE 0 END), 0) as nro_bautizo"),
                DB::raw("COALESCE(SUM(CASE WHEN xb.tipo = 'profesion de fe' THEN 1 ELSE 0 END), 0) as nro_profesion_fe"),
                DB::raw("COALESCE(SUM(CASE WHEN xb.tipo = 'rebautismo' THEN 1 ELSE 0 END), 0) as nro_rebautismo")
            )
            ->groupBy('xd.id_distrito', 'xd.nombre')
            ->first(); // Usamos first() para obtener un solo objeto con los totales
        $labelsBautizos = ['Bautismos', 'Profesión de Fe', 'Rebautismo'];
        $valoresBautizos = [
            (int) $tipos->nro_bautizo,
            (int) $tipos->nro_profesion_fe,
            (int) $tipos->nro_rebautismo
        ];
            /**DATOS DE BAUTISOS DEL DISTRITO **/
        $bautiso = DB::table('desafios as xd')
            ->join('distritos as xdd', 'xd.id_distrito', '=', 'xdd.id_distrito')
            ->where('xdd.id_distrito', $id_distrito)
            ->where('xd.anio', $anio)
            ->select(
                'xdd.id_distrito',
                'xdd.nombre as nombre_distrito',
                'xd.desafio_bautizo',
                'xd.bautizos_alcanzados',
                DB::raw('(xd.desafio_bautizo - xd.bautizos_alcanzados) as diferencia')
            )
            ->first();
        if (!$bautiso) {
            return redirect()->route('panel')
                ->with('info', 'Aún no hay desafíos asignados a tu distrito este año.');
        }

        $graficos_final = [
            'categorias' => ['Desafío', 'Alcanzado', 'Diferencia'],
            'valores' => [
                (int) $bautiso->desafio_bautizo,
                (int) $bautiso->bautizos_alcanzados,
                (int) $bautiso->diferencia
            ]
        ];
        $desafio = Desafio::where('anio', $anio)
            ->where('id_distrito', $id_distrito)
            ->first();
        if (!$desafio) {
            return redirect()->back()->with('error', 'Desafío anual no encontrado.');
        }
        $mensuales = Mensual::where('id_desafio', $desafio->id_desafio)
            ->orderBy('mes') // importante para que los meses estén en orden
            ->get();
        $mensuales = $mensuales->filter(fn($m) => $m->mes <= now()->month); // SOLO LOS DESAFIOS DE HASTA EL MES ACTUAL
        $meses = [];
        $desafios = [];
        $alcanzados = [];
        $nombresMeses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        foreach ($mensuales as $m) {
            $meses[] = $nombresMeses[$m->mes] ?? 'Desconocido';
            $desafios[] = (int) $m->desafio_visitas;
            $alcanzados[] = (int) $m->visitas_alcanzadas;
        }  
        //DATOS DE INSTRUCTORES Y ESTUDIANTES

       $totales = DB::table('desafios as xd')
            ->join('anual_iglesias as xai', 'xd.id_desafio', '=', 'xai.id_desafio')
            ->where('xd.id_distrito', $id_distrito)
            ->where('xd.anio', $anio)
            ->select(
                DB::raw('SUM(xai.desafio_estudiantes) as total_desafio_estudiantes'),
                DB::raw('SUM(xai.estudiantes_alcanzados) as total_estudiantes_alcanzados'),
                DB::raw('SUM(xai.desafio_instructores) as total_desafio_instructores'),
                DB::raw('SUM(xai.instructores_alcanzados) as total_instructores_alcanzados'),
                DB::raw('SUM(xai.desafio_estudiantes - xai.estudiantes_alcanzados) as diferencia_estudiantes'),
                DB::raw('SUM(xai.desafio_instructores - xai.instructores_alcanzados) as diferencia_instructores')
            )
            ->first();
        $grafico_estudiantes = [
            'categorias' => ['Desafío', 'Alcanzado', 'Diferencia'],
            'valores' => [
                (int) $totales->total_desafio_estudiantes,
                (int) $totales->total_estudiantes_alcanzados,
                (int) $totales->diferencia_estudiantes,
            ]
        ];
        $grafico_instructores = [
            'categorias' => ['Desafío', 'Alcanzado', 'Diferencia'],
            'valores' => [
                (int) $totales->total_desafio_instructores,
                (int) $totales->total_instructores_alcanzados,
                (int) $totales->diferencia_instructores,
            ]
        ];
        //**VER GRAFICOS POR IGLESIA DE ESE DESAFIO */
        $desafios_ins_est = DB::table('desafios as xd')
            ->join('anual_iglesias as xai', 'xd.id_desafio', '=', 'xai.id_desafio')
            ->join('iglesias as xi', 'xai.id_iglesia', '=', 'xi.id_iglesia')
            ->where('xd.id_distrito', $id_distrito)
            ->where('xd.anio', $anio)
            ->select(
                'xai.id_iglesia',
                'xi.codigo',
                'xi.tipo',
                'xi.nombre as nombre_iglesia',
                'xai.desafio_estudiantes',
                'xai.estudiantes_alcanzados',
                'xai.desafio_instructores',
                'xai.instructores_alcanzados'
            )
            ->get();

        // Procesar los datos para el gráfico (ya listos) NUMEROS
        $graficos_ins_est = $desafios_ins_est->map(function ($d) {
            return [
                'id_iglesia' => $d->id_iglesia,
                'estudiantes' => [
                    'desafio' => (int)$d->desafio_estudiantes,
                    'alcanzado' => (int)$d->estudiantes_alcanzados,
                    'diferencia' => (int)$d->desafio_estudiantes - (int)$d->estudiantes_alcanzados,
                ],
                'instructores' => [
                    'desafio' => (int)$d->desafio_instructores,
                    'alcanzado' => (int)$d->instructores_alcanzados,
                    'diferencia' => (int)$d->desafio_instructores - (int)$d->instructores_alcanzados,
                ]
            ];
        });
        //esta parte es para ver la puntualidad de remesas de los distritos.
        $puntualidad = DB::select("
                    SELECT
					xd.id_distrito,
                    xd.nombre as nombre_distrito,
                    xi.codigo,
                    xi.nombre,
                    xi.tipo,
                    xi.lugar,
                    xp.anio,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 1) AS puntualidad_enero,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 2) AS puntualidad_febrero,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 3) AS puntualidad_marzo,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 4) AS puntualidad_abril,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 5) AS puntualidad_mayo,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 6) AS puntualidad_junio,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 7) AS puntualidad_julio,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 8) AS puntualidad_agosto,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 9) AS puntualidad_septiembre,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 10) AS puntualidad_octubre,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 11) AS puntualidad_noviembre,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 12) AS puntualidad_diciembre
                FROM iglesias xi 
                LEFT JOIN distritos xd ON xi.distrito_id = xd.id_distrito
                JOIN puntualidades xp ON xp.id_iglesia = xi.id_iglesia
                JOIN mes xm ON xm.id_puntualidad = xp.id_puntualidad
                WHERE xp.anio = 2026
                and xi.estado = true
                and xd.id_distrito = :distrito
				GROUP BY
					xd.id_distrito,
                    xd.nombre,
                    xi.codigo,
                    xi.nombre,
                    xi.tipo,
                    xi.lugar,
                    xp.anio;", ['distrito' => $id_distrito]);

        //para saber el estado del diezmo en cada mes

        $resultados_remesas = DB::table(DB::raw('generate_series(1, 12) as m(mes)'))
            ->leftJoin('generas as xg', function($join) {
                $join->on('m.mes', '=', 'xg.mes')
                    ->where('xg.anio', '=', $anio);
            })
            ->leftJoin('iglesias as xi', function($join) use ($id_distrito) {
                $join->on('xg.id_iglesia', '=', 'xi.id_iglesia')
                    ->where('xi.distrito_id', '=', $id_distrito);
            })
            ->leftJoin('remesas_iglesias as xri', 'xg.id_remesa', '=', 'xri.id_remesa')
            ->select('m.mes', DB::raw('COALESCE(SUM(xri.monto), 0) as total_monto'))
            ->groupBy('m.mes')
            ->orderBy('m.mes')
            ->get();

        $montoOriginal = DB::table('blanco_remesas')
            ->where('anio', $anio)
            ->where('id_distrito', 36)
            ->value('monto') ?? 0; // Si no existe el registro, usamos 0

        // Dividimos (en PHP 0 / 12 no da error)
        $blanco_mensual = $montoOriginal / 12;

        $datosRemesas = $resultados_remesas->pluck('total_monto')->toArray();
        // 2. Crear un array de 12 posiciones con el valor del blanco mensual
        $datosBlanco = array_fill(0, 12, round($blanco_mensual, 2));
        // 3. Nombres de los meses para el eje de la gráfica
        $mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        //dd($datosRemesas,$datosBlanco,$mesesNombres, $blanco_mensual);
        return view('dashboards.dashboard_pastor_mbos', compact('pastor','graficos_ins_est','desafios_ins_est','resumenIglesias', 'graficos_final', 'distrito','meses',
         'desafios', 'alcanzados', 'grafico_estudiantes', 'grafico_instructores', 'puntualidad',
         'datosRemesas','datosBlanco','mesesNombres', 'blanco_mensual',
        'labelsBautizos','valoresBautizos'));
    }


    public function dashboardTesorero() //para ver panel de tesoreros
    {
        $anioActual = date('Y');
         // ¿Hay blancos para este año?
        $hayBlancos = DB::table('blanco_remesas')
        ->where('anio', $anioActual)
        ->exists();

        // ¿Hay remesas para este año?
        $hayRemesas = DB::table('generas')
            ->where('anio', $anioActual)
            ->exists();

        if ($hayBlancos && $hayRemesas) {
            $anio = $anioActual;
        }else{
            $anio = $anioActual -1;
        }

        // A) Total blanco
        $blanco = DB::table('blanco_remesas')
            ->where('anio', $anio)
            ->sum('monto');

        // B) Total alcanzado

        $alcanzado = DB::table('remesas_iglesias as ri')
            ->join('generas as g', 'g.id_remesa', '=', 'ri.id_remesa')
            ->where('g.anio', $anio)
            ->sum('ri.monto');

        // C) Porcentaje
        $porcentaje = $blanco > 0 ? ($alcanzado / $blanco) * 100 : 0;

        // D) Diferencia
        // positivo = superó el desafío
        // negativo = falta alcanzar
        $diferencia = $alcanzado - $blanco;

        // E) Gráfica mensual
        $mensual = DB::select("
            SELECT xg.mes, SUM(xri.monto) AS total_mes
            FROM generas xg
            JOIN remesas_iglesias xri ON xri.id_remesa = xg.id_remesa
            WHERE xg.anio = ?
            GROUP BY xg.mes
            ORDER BY xg.mes
        ", [$anio]);

        // Formatear para JS
        $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

        $dataMensual = array_fill(0, 12, 0);
        foreach ($mensual as $row) {
            $dataMensual[$row->mes - 1] = (float)$row->total_mes;
        }
        // PROMEDIO MENSUAL (solo meses con datos)
        $promedioMensual = array_sum($dataMensual) / max(1, count(array_filter($dataMensual)));
        // Ranking top 5 distritos
        $topDistritos = DB::select("
            SELECT d.nombre AS distrito, SUM(xri.monto) AS total
            FROM iglesias i
            JOIN distritos d ON d.id_distrito = i.distrito_id
            JOIN generas xg ON xg.id_iglesia = i.id_iglesia
            JOIN remesas_iglesias xri ON xri.id_remesa = xg.id_remesa
            WHERE xg.anio = ?
            GROUP BY d.nombre
            ORDER BY total DESC
            LIMIT 5
        ", [$anio]);

        // Distritos de alerta (< 50%)
        $alertas = DB::select("
            SELECT 
                d.id_distrito,
                d.nombre AS distrito,
                SUM(xri.monto) AS total,
                br.monto AS blanco
            FROM iglesias i
            JOIN distritos d ON d.id_distrito = i.distrito_id
            JOIN generas xg ON xg.id_iglesia = i.id_iglesia
            JOIN remesas_iglesias xri ON xri.id_remesa = xg.id_remesa
            JOIN blanco_remesas br ON br.id_distrito = d.id_distrito
            WHERE xg.anio = ?
            GROUP BY d.id_distrito, d.nombre, br.monto
            HAVING SUM(xri.monto) < br.monto * 0.5;", [$anio]);
        return view('panel.tesorero', [
            'blanco' => $blanco,
            'alcanzado' => $alcanzado,
            'porcentaje' => round($porcentaje, 2),
            'diferencia' => $diferencia,
            'meses' => $meses,
            'dataMensual' => $dataMensual,
            'promedioMensual' => $promedioMensual,
            'topDistritos' => $topDistritos,
            'alertas' => $alertas
        ]);
    }

    public function dashboardSecretario()
    {
        $anio = date('Y');
        // ==============================
        // 🔵 1. KPIs BASE - IGLESIAS
        // ==============================
        $totalIglesias = DB::table('iglesias')
            ->where('estado', true)
            ->count();

        $iglesiasTipoIglesia = DB::table('iglesias')
            ->where('tipo', 'Iglesia')
            ->where('estado', true)
            ->count();

        $iglesiasTipoGrupo = DB::table('iglesias')
            ->where('tipo', 'Grupo')
            ->where('estado', true)
            ->count();

        $iglesiasTipoFilial = DB::table('iglesias')
            ->where('tipo', 'Filial')
            ->where('estado', true)
            ->count();


        // ============================
    // 🔵 2. BAUTISMOS – GENERAL MBOS
        // ============================
        $b_desafio = DB::table('desafios')
                    ->where('anio', $anio)
                    ->sum('desafio_bautizo');
        $b_alcanzado = DB::table('bautisos')
                    ->whereYear('fecha_bautizo', $anio)
                    ->count();
        $b_diferencia = $b_alcanzado - $b_desafio;


        // ============================
        // 🔵 3. BAUTISMOS – DISTRITOS (2025)
        // ============================

        $b_desafio_d = DB::table('desafios')
            ->where('anio', $anio)
            ->sum('desafio_bautizo');

        $b_alcanzado_d = DB::table('desafios')
            ->where('anio', $anio)
            ->sum('bautizos_alcanzados');

        $b_diferencia_d = $b_alcanzado_d - $b_desafio_d;


        // ============================
        // 🔵 4. Datos para Gráfica — Iglesias por Distrito
        // ============================
        $iglesiasPorDistrito = DB::table('iglesias as i')
            ->join('distritos as d', 'd.id_distrito', '=', 'i.distrito_id')
            ->select('d.nombre as distrito', DB::raw('COUNT(*) as total'))
            ->where('i.estado', true)
            ->groupBy('d.nombre')
            ->orderBy('total', 'DESC')
            ->get();
        $porcentajeGeneral = $b_desafio > 0 
        ? round(($b_alcanzado / $b_desafio) * 100, 1)
        : 0;

        return view('panel.secretario', compact(
            'totalIglesias',
            'iglesiasTipoIglesia',
            'iglesiasTipoGrupo',
            'iglesiasTipoFilial',
            'b_desafio',
            'b_alcanzado',
            'b_diferencia',
            'b_desafio_d',
            'b_alcanzado_d',
            'b_diferencia_d',
            'anio',
            'iglesiasPorDistrito',
            'porcentajeGeneral'
        ));
    }


    /**________________PANEL_________ MBOS */
    public function panel_mbos() //permision 'ver dashboard pastores - panel',
    {
        $anio = date('Y');
        // 1. Total de bautismos alcanzados
        $b_alcanzado = DB::table('desafios')
            ->where('anio', $anio)
            ->sum('bautizos_alcanzados');

        // 2. Total de desafío del año
        $b_desafio = DB::table('desafios')
            ->where('anio', $anio)
            ->sum('desafio_bautizo');

        // 3. Diferencia
        $b_diferencia = $b_alcanzado - $b_desafio;

        // 4. Porcentaje de avance
        if ($b_desafio > 0) {
            $porcentajeGeneral = round(($b_alcanzado / $b_desafio) * 100, 2);
        } else {
            $porcentajeGeneral = 0;
        }
        // ________PARA REMESAS GESTION_____________
        // 1. SUMA del monto alcanzado
        $r_alcanzado = DB::table('generas as g')
            ->join('remesas_iglesias as r', 'g.id_remesa', '=', 'r.id_remesa')
            ->where('g.anio', $anio)
            ->sum('r.monto');

        // 2. SUMA del blanco (desafío)
        $r_blanco = DB::table('blanco_remesas')
            ->where('anio', $anio)
            ->sum('monto');
        //dd($r_blanco);
        $mes_maximo = DB::table('generas')
        ->where('anio', $anio)
        ->max('mes');

        $r_blanco = ($r_blanco / 12) * $mes_maximo;
        // 3. DIFERENCIA
        $r_diferencia = $r_alcanzado - $r_blanco;

        // 4. PORCENTAJE
        $r_porcentaje = $r_blanco > 0 
            ? round(($r_alcanzado / $r_blanco) * 100, 2)
            : 0;


        return view('panel.panel', compact(
            'anio',
            'b_alcanzado',
            'b_desafio',
            'b_diferencia',
            'porcentajeGeneral',
            'anio',
            'r_alcanzado',
            'r_blanco',
            'r_diferencia',
            'r_porcentaje',
        ));
    }
}
