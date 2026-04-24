<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Distrito; 
use App\Models\Iglesia; 
use App\Models\Persona; 

use App\Exports\PuntualidadExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;


class PuntualidadController extends Controller
{

    /*🔹 Puntualidad// SATISFECHOS
    *'ver - puntualidad',
    */
    function __construct()
    {
        // index(): permissions 'ver puntualidad'
        // La etiqueta en la lista era 'ver - puntualidad', usaremos 'ver - puntualidad' para ser exactos.
        $this->middleware('permission:ver-puntualidad', ['only' => ['index']]);
    }

    public function index() //permissions 'ver puntualidad',
    {
        // Traemos todas las iglesias junto con su distrito
        $iglesias = DB::select("
                    SELECT
                    xd.nombre as nombre_distrito,
                    xi.codigo,
                    xi.nombre,
                    xi.tipo,
                    xi.lugar,
                    xp.anio,
                    -- puntualidad por mes
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
                GROUP BY
                    xd.nombre,
                    xi.codigo,
                    xi.nombre,
                    xi.tipo,
                    xi.lugar,
                    xp.anio;
        ");
        return view('puntualidad.index', compact('iglesias'));
    }

    public function exportExcel()
    {
        return Excel::download(new PuntualidadExport, 'puntualidad.xlsx');
    }

    public function exportPdf()
    {
        $iglesias = DB::select("
            SELECT 
                xd.nombre as nombre_distrito,
                xi.codigo, 
                xi.nombre,
                xi.tipo,
                xi.lugar,
                xp.anio,

                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 1) AS INTEGER) AS puntualidad_enero,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 2) AS INTEGER) AS puntualidad_febrero,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 3) AS INTEGER) AS puntualidad_marzo,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 4) AS INTEGER) AS puntualidad_abril,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 5) AS INTEGER) AS puntualidad_mayo,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 6) AS INTEGER) AS puntualidad_junio,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 7) AS INTEGER) AS puntualidad_julio,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 8) AS INTEGER) AS puntualidad_agosto,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 9) AS INTEGER) AS puntualidad_septiembre,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 10) AS INTEGER) AS puntualidad_octubre,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 11) AS INTEGER) AS puntualidad_noviembre,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 12) AS INTEGER) AS puntualidad_diciembre,

                (
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 1) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 2) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 3) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 4) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 5) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 6) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 7) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 8) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 9) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 10) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 11) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 12) AS INTEGER), 0)
                ) AS total_puntaje

            FROM iglesias xi 
            LEFT JOIN distritos xd ON xi.distrito_id = xd.id_distrito
            JOIN puntualidades xp ON xp.id_iglesia = xi.id_iglesia
            JOIN mes xm ON xm.id_puntualidad = xp.id_puntualidad
            WHERE xp.anio = 2026
            AND xi.estado = true
            GROUP BY 
                xd.nombre,
                xi.codigo, 
                xi.nombre,
                xi.tipo,
                xi.lugar,
                xp.anio
            ORDER BY total_puntaje DESC;

        ");

        $fecha = date('d/m/Y');
        $hora = date('H:i:s');

        $pdf = app('dompdf.wrapper');   // ← compatible con Laravel 12
        $pdf->loadView('pdf.puntualidad', compact('iglesias','fecha','hora'))
            ->setPaper('A4', 'landscape');
        return $pdf->stream('puntualidad.pdf');
        //return $pdf->download('puntualidad.pdf');
    }

    public function filtro_general_puntualidad() //este es el filtro general de pendientes por diferentes tipos 
    {
        //dd($request);
        
        $iglesias = Iglesia::all();
        $distritos = Distrito::all(); 
        $encargados = DB::select('select xpp.id_persona, xpp.nombre, xpp.ape_paterno, xpp.ape_materno
                            from personales xp
                            join personas xpp on xp.id_personal = xpp.id_persona
                            join model_has_roles xm on xm.model_id = xpp.id_persona
                            join roles xr on xm.role_id = xr.id
                            where xr.name like ?', ['%Tesorero%']);
                            
        $periodos = DB::table('generas')
            ->selectRaw("TO_CHAR(mes, 'FM00') || ' - ' || anio as label")
            ->selectRaw("anio, mes")
            ->where('anio', '>=', now()->year - 1)
            ->distinct()
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc')
            ->get();
        return view('puntualidad.filtro_general_puntualidad', compact('periodos','iglesias', 'distritos', 'encargados')); 
    }

    public function pdf_filtro_general_puntualidad(Request $request)
    {
        //dd($request);
        $tiposSeleccionados = $request->input('tipos');
        
        // 1. Base de la Consulta (Cambiamos PENDIENTE por ENTREGADO para el Ranking)
        $query = DB::table('generas as xg')
            ->leftJoin('remesas as xr', 'xg.id_remesa', '=', 'xr.id_remesa')
            ->leftJoin('iglesias as xi', 'xg.id_iglesia', '=', 'xi.id_iglesia')
            ->leftJoin('distritos as xd', 'xi.distrito_id', '=', 'xd.id_distrito')
            ->leftJoin('personas as xpas', 'xd.id_pastor', '=', 'xpas.id_persona')
            ->leftJoin('personas as xres', 'xd.id_responsable_remesa', '=', 'xres.id_persona')
            ->select(
                'xi.distrito_id', 
                'xd.nombre as distrito', 
                'xi.codigo', 
                'xi.nombre as nombre_igle', 
                'xi.tipo', 
                'xg.id_remesa',
                'xg.id_iglesia', 
                'xg.mes', 
                'xg.anio', 
                'xr.estado',
                'xr.fecha_entrega', // Crucial para el desempate
                DB::raw("xpas.nombre || ' ' || COALESCE(xpas.ape_paterno, '') as nombre_pas"),
                DB::raw("xres.nombre || ' ' || COALESCE(xres.ape_paterno, '') as nombre_res")
            )
            ->where('xr.estado', 'ENTREGADO'); // Solo entran los que terminaron

        // 2. Lógica de Filtrado por Tipo
        if (!empty($tiposSeleccionados) && !in_array('todos', $tiposSeleccionados)) {
            $query->whereIn('xi.tipo', $tiposSeleccionados);
        }

        // 3. NUEVO: Lógica de Modo de Filtro (Periodo vs Rango)
        if ($request->modo_filtro === 'rango') {
            // Filtro por Rango de Fechas exacto (Usa el Datetime de la remesa)
            $query->whereBetween('xr.fecha_entrega', [
                $request->fecha_desde . ' 00:00:00', 
                $request->fecha_hasta . ' 23:59:59'
            ]);
        } else {
            // Filtro por Periodo (Mes/Año) - Tu lógica original optimizada
            $inicio = explode('-', $request->periodo_inicio);
            $mesInicio = (int)$inicio[0]; 
            $anioInicio = (int)$inicio[1];
            $esRangoPeriodo = $request->has('periodo_fin') && $request->periodo_fin !== $request->periodo_inicio;

            if (!$esRangoPeriodo) {
                $query->where('xg.mes', $mesInicio)->where('xg.anio', $anioInicio);
            } else {
                $fin = explode('-', $request->periodo_fin);
                $mesFin = (int)$fin[0]; $anioFin = (int)$fin[1];
                $query->whereRaw("(xg.anio * 100 + xg.mes) BETWEEN ? AND ?", [
                    ($anioInicio * 100 + $mesInicio),
                    ($anioFin * 100 + $mesFin)
                ]);
            }
        }

        // 4. Filtros de Jerarquía (Encargado / Zona)
        if ($request->nivel_principal === 'capa_encargado' && $request->encargado_id) {
            $query->where('xd.id_responsable_remesa', $request->encargado_id);
        }

        if ($request->sub_nivel_tipo === 'panel_distrito' && $request->has('distritos')) {
            $query->whereIn('xd.id_distrito', $request->distritos);
        } elseif ($request->sub_nivel_tipo === 'panel_zona' && $request->has('zona') && $request->zona !== 'TODOS') {
            $query->where('xi.lugar', $request->zona);
        }
        //ordenamos
        $resultados = $query->orderBy('xr.fecha_entrega', 'asc') // El que entregó más temprano va PRIMERO
                    ->get();

        // Obtenemos los resultados base
        $resultados = $query->get();

        // 5. LA LÓGICA DIFÍCIL: Ranking de Distritos de Oro
        // Agrupamos por distrito para verificar cumplimiento total
        $rankingDistritos = $resultados->groupBy('distrito_id')->map(function ($iglesiasEntregadas, $distritoId) use ($tiposSeleccionados) {
            
            // A. Contar cuántas iglesias TIENE realmente este distrito en la DB (según tipos seleccionados)
            $totalDeberiaTener = DB::table('iglesias')
                ->where('distrito_id', $distritoId)
                ->when(!in_array('todos', $tiposSeleccionados), function($q) use ($tiposSeleccionados) {
                    $q->whereIn('tipo', $tiposSeleccionados);
                })
                ->count();

            // B. Contar cuántas entregaron en este reporte
            $totalEntregadas = $iglesiasEntregadas->unique('id_iglesia')->count();

            // C. REGLA DE ORO: Si falta una sola iglesia, el distrito queda fuera del Ranking
            if ($totalEntregadas < $totalDeberiaTener) {
                return null; 
            }

            // D. DETERMINAR HORA DE FINALIZACIÓN: La hora de la remesa más tardía
            // El distrito termina cuando su última iglesia entrega.
            $ultimaEntrega = $iglesiasEntregadas->max('fecha_entrega');

            $primero = $iglesiasEntregadas->first();
            return (object)[
                'distrito' => $primero->distrito,
                'nombre_res' => $primero->nombre_res,
                'nombre_pas' => $primero->nombre_pas,
                'fecha_finalizacion' => $ultimaEntrega, // Este es el valor de desempate
                'iglesias_contadas' => $totalEntregadas
            ];
        })->filter()
        ->sortBy(function($item) {
            // ESTE ES EL TRUCO: Convertimos a timestamp para que el orden sea matemático y exacto
            return strtotime($item->fecha_finalizacion);
        })
        ->values();
        // Ordenamos: El que terminó antes es el #1

        // 6. Preparar datos para la vista PDF
        $fechaHora = now()->format('d-m-Y_H-i');
        
        // Si el filtro es por Distrito (Sub-nivel)
        if ($request->sub_nivel_tipo === 'panel_distrito' || $request->nivel_principal === 'capa_encargado') {
            $pdf = Pdf::loadView('pdf.pdf_ranking_puntualidad_distritos', [
                'ranking' => $rankingDistritos,
                'modo' => $request->modo_filtro,
                'rango' => ($request->modo_filtro === 'rango') ? 
                            "$request->fecha_desde al $request->fecha_hasta" : 
                            "$request->periodo_inicio a $request->periodo_fin"
            ])->setPaper('letter', 'portrait');

            return $pdf->stream("Ranking_Oro_Distritos_{$fechaHora}.pdf");
        }

        // Si es solo por Iglesias (Ranking individual por hora de entrega)
        $resultadosIglesias = $resultados->sortBy('fecha_entrega');
        
        $pdf = Pdf::loadView('pdf.pdf_ranking_puntualidad_iglesias', [
            'resultados' => $resultadosIglesias
        ])->setPaper('letter', 'portrait');

        return $pdf->stream("Ranking_Individual_{$fechaHora}.pdf");
    }


}
