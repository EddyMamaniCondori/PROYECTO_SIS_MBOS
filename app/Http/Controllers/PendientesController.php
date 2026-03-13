<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Distrito; 
use App\Models\Iglesia; 
use App\Models\Persona; 
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\PendientesExport;


class PendientesController extends Controller
{
    /**
     * 
     * 'ver anual-pendientes',
      *      'ver distrital-pendientes',
      *      'ver mensual-pendientes',
     */

    function __construct()
    {
        // index(): permision ver anual-pendientes
        // Nota: Esta función es un reporte anual general.
        $this->middleware('permission:ver anual-pendientes', ['only' => ['index', 'filtro_anual']]); 

        // index_distrital(): permision ver distrital-pendientes
        $this->middleware('permission:ver distrital-pendientes', ['only' => ['index_distrital','filtro_anual']]);

        // index_mensual(): permision ver mensual-pendientes
        $this->middleware('permission:ver mensual-pendientes', ['only' => ['index_mensual','filtro_anual']]);

        // La función 'filtro_anual' es el procesamiento del formulario de 'index', por lo que se agrupa con 'index'.
        
    }

    public function index() 
    {
        $datos = DB::select("
            select 	xd.nombre as nombre_distrito,
                    xp.nombre as nombre_p, xp.ape_paterno, xp.ape_materno,
                    xi.codigo, xi.nombre,
                    xi.tipo, xi.lugar, xg.*,
                    xr.estado
            from generas xg
            join remesas xr on xg.id_remesa = xr.id_remesa 
            join iglesias xi on xg.id_iglesia = xi.id_iglesia
            left join distritos xd on xi.distrito_id = xd.id_distrito
            left join personas xp on xd.id_pastor = xp.id_persona
            where xr.estado = 'PENDIENTE'
            and anio = 2026
            order by nombre_distrito 
        ");  // Trae todos los registros de la tabla asociada a RemesaImport
        $datos_totales = DB::select("
            SELECT 
                xi.tipo,
                COUNT(*) AS total
            FROM generas xg
            JOIN remesas xr ON xg.id_remesa = xr.id_remesa 
            JOIN iglesias xi ON xg.id_iglesia = xi.id_iglesia
            WHERE xg.anio = 2026
            AND xr.estado = 'PENDIENTE'
            GROUP BY xi.tipo
            ORDER BY xi.tipo;
        ");

        $c_iglesias = 0;
        $c_grupo = 0;
        $c_filiales = 0;
        

        foreach ($datos_totales as $dato) {
            $tipo = strtolower(trim($dato->tipo)); // Minusculas y sin espacios extras
            $total = $dato->total;

            if (strpos($tipo, 'filial') !== false) {
                $c_filiales += $total;
            } elseif (strpos($tipo, 'grupo') !== false) {
                $c_grupo += $total;
            } elseif (strpos($tipo, 'iglesia') !== false) {
                $c_iglesias += $total;
            }
        }
        $total = $c_filiales+$c_grupo+$c_iglesias;

         $personal = DB::select("
            SELECT xp.id_persona, xp.nombre, xp.ape_paterno, xr.name
            FROM personas xp
            JOIN personales xpp ON xp.id_persona = xpp.id_personal
            JOIN model_has_roles xm ON xp.id_persona = xm.model_id 
            JOIN roles xr ON xm.role_id = xr.id
            WHERE xr.name LIKE 'Tesorero'
        ");


        return view('pendientes.index_anual', compact('datos', 'c_iglesias', 'c_grupo', 'c_filiales', 'total', 'personal')); // Pasa esos datos a la vista*/
        //return view('pendientes.pruebas');
    }

    public function filtro_anual(Request $request)
    {   
         $validated = $request->validate([
            'periodoInicio' => 'required|string',
            'periodoFinal' => 'required|string',
            'tipo' => 'required|array|min:1',
            'tipo.*' => 'string', // Opcional, para validar cada elemento del array
        ]);

        //dd($request);
        $periodoInicio = $request->input('periodoInicio'); // e.g. "03-2025"
        $periodoFinal = $request->input('periodoFinal');   // e.g. "12-2025"
        $tipos = $request->input('tipo', []);              // e.g. [1,2,3
        
        if (!$periodoInicio || !$periodoFinal) {
            return back()->with('error', 'Debe seleccionar ambos periodos');
        }

        // Extraemos mes y año para filtrar por separado
        [$mesInicio, $anioInicio] = array_map('intval', explode('-', $periodoInicio));
        [$mesFinal, $anioFinal] = array_map('intval', explode('-', $periodoFinal));

        if (count($tipos) === 0) {
            return back()->with('error', 'Debe seleccionar al menos un tipo');
        }
            
        // Para pasar el array de tipos a cadena para SQL, escapando bien:
        $placeholders = implode(',', array_fill(0, count($tipos), '?'));

        //dd($mesInicio, $mesFinal, $anioFinal, $anioInicio, $placeholders, $tipos);
        // Validar los datos si quieres
        $sql = "
        select  
            xd.nombre as nombre_distrito,
            xp.nombre as nombre_p, 
            xp.ape_paterno, 
            xp.ape_materno,
            xi.codigo, 
            xi.nombre,
            xi.tipo, 
            xi.lugar, 
            xg.*,
            xr.estado
        from generas xg
        join remesas xr on xg.id_remesa = xr.id_remesa 
        join iglesias xi on xg.id_iglesia = xi.id_iglesia
        left join distritos xd on xi.distrito_id = xd.id_distrito
        left join personas xp on xd.id_pastor = xp.id_persona
        where xr.estado = 'PENDIENTE'
        and (
            (xg.anio > ? OR (xg.anio = ? AND xg.mes >= ?))
            AND
            (xg.anio < ? OR (xg.anio = ? AND xg.mes <= ?))
        )
        and LOWER(xi.tipo) IN ($placeholders)
        order by nombre_distrito
        ";

        $params = [
            $anioInicio, $anioInicio, $mesInicio,
            $anioFinal, $anioFinal, $mesFinal,
            ...array_map('strtolower', $tipos)
        ];
        $datos = DB::select($sql, $params);


        $datos_totales = DB::select("
            SELECT 
                xi.tipo,
                COUNT(*) AS total
            FROM generas xg
            JOIN remesas xr ON xg.id_remesa = xr.id_remesa 
            JOIN iglesias xi ON xg.id_iglesia = xi.id_iglesia
            WHERE xr.estado = 'PENDIENTE'
            AND (
                (xg.anio > ? OR (xg.anio = ? AND xg.mes >= ?))
                AND
                (xg.anio < ? OR (xg.anio = ? AND xg.mes <= ?))
            )
            AND LOWER(xi.tipo) IN ($placeholders)
            GROUP BY xi.tipo
            ORDER BY xi.tipo
        ", $params);

        // Inicializas contadores
        $c_iglesias = 0;
        $c_grupo = 0;
        $c_filiales = 0;

        // Sumamos según coincidencia en el tipo (minusculas y trim)
        foreach ($datos_totales as $dato) {
            $tipo = strtolower(trim($dato->tipo));
            $total = $dato->total;

            if (strpos($tipo, 'filial') !== false) {
                $c_filiales += $total;
            } elseif (strpos($tipo, 'grupo') !== false) {
                $c_grupo += $total;
            } elseif (strpos($tipo, 'iglesia') !== false) {
                $c_iglesias += $total;
            }
        }
        $total = $c_filiales+$c_grupo+$c_iglesias;
        return view('pendientes.index_anual', compact('datos', 'c_iglesias', 'c_grupo', 'c_filiales', 'total'));
    }


    public function index_mensual() 
    {
        $datos = DB::select("
            SELECT
                xg.mes,
                xg.anio,
                COUNT(CASE WHEN LOWER(xi.tipo) = 'iglesia' THEN 1 END) AS nro_iglesias,
                COUNT(CASE WHEN LOWER(xi.tipo) = 'grupo' THEN 1 END) AS nro_grupos,
                COUNT(CASE WHEN LOWER(xi.tipo) = 'filial' THEN 1 END) AS nro_filiales,
                COUNT(*) AS total
            FROM generas xg
            JOIN remesas xr ON xg.id_remesa = xr.id_remesa
            JOIN iglesias xi ON xg.id_iglesia = xi.id_iglesia
            WHERE xr.estado = 'PENDIENTE'
            GROUP BY xg.anio, xg.mes
            ORDER BY xg.anio, xg.mes;
        ");  
        return view('pendientes.index_mensual', compact('datos')); 
    }

    public function index_distrital() 
    {
        $persona = Auth::user(); 
        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;

        $anio = 2025;

        $datos = DB::select("
                    SELECT 
                    xi.id_iglesia,
                    xi.codigo,
                    xi.nombre,
                    COALESCE(MAX(CASE WHEN xg.mes = 1 THEN xr.estado END), 'SIN REGISTRO') AS mes_enero,
                    COALESCE(MAX(CASE WHEN xg.mes = 2 THEN xr.estado END), 'SIN REGISTRO') AS mes_febrero,
                    COALESCE(MAX(CASE WHEN xg.mes = 3 THEN xr.estado END), 'SIN REGISTRO') AS mes_marzo,
                    COALESCE(MAX(CASE WHEN xg.mes = 4 THEN xr.estado END), 'SIN REGISTRO') AS mes_abril,
                    COALESCE(MAX(CASE WHEN xg.mes = 5 THEN xr.estado END), 'SIN REGISTRO') AS mes_mayo,
                    COALESCE(MAX(CASE WHEN xg.mes = 6 THEN xr.estado END), 'SIN REGISTRO') AS mes_junio,
                    COALESCE(MAX(CASE WHEN xg.mes = 7 THEN xr.estado END), 'SIN REGISTRO') AS mes_julio,
                    COALESCE(MAX(CASE WHEN xg.mes = 8 THEN xr.estado END), 'SIN REGISTRO') AS mes_agosto,
                    COALESCE(MAX(CASE WHEN xg.mes = 9 THEN xr.estado END), 'SIN REGISTRO') AS mes_septiembre,
                    COALESCE(MAX(CASE WHEN xg.mes = 10 THEN xr.estado END), 'SIN REGISTRO') AS mes_octubre,
                    COALESCE(MAX(CASE WHEN xg.mes = 11 THEN xr.estado END), 'SIN REGISTRO') AS mes_noviembre,
                    COALESCE(MAX(CASE WHEN xg.mes = 12 THEN xr.estado END), 'SIN REGISTRO') AS mes_diciembre
                FROM generas xg
                JOIN remesas xr ON xg.id_remesa = xr.id_remesa
                JOIN iglesias xi ON xg.id_iglesia = xi.id_iglesia
                WHERE xi.distrito_id = ?
                AND xg.anio = ?
                GROUP BY xi.id_iglesia, xi.codigo, xi.nombre
                ORDER BY xi.nombre;
        ",[$id_distrito, $anio]);  

        return view('pendientes.vista_distrital', compact('datos')); 
    }

    public function filtro_general_pendientes() //este es el filtro general de pendientes por diferentes tipos 
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
        return view('pendientes.filtro_general_pendientes', compact('periodos','iglesias', 'distritos', 'encargados')); 
    }

    public function pdf_filtro_general_pendientes(Request $request) //este es el filtro general de pendientes por diferentes tipos 
    {
        //dd($request);
        // Supongamos que recibes el array desde el request
        $tiposSeleccionados = $request->input('tipos'); // Ej: ['iglesia', 'filial'] o ['todos']
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
                DB::raw("xpas.nombre || ' ' || COALESCE(xpas.ape_paterno, '') || ' ' || COALESCE(xpas.ape_materno, '') as nombre_pas"),
                DB::raw("xres.nombre || ' ' || COALESCE(xres.ape_paterno, '') || ' ' || COALESCE(xres.ape_materno, '') as nombre_res")
            )
            ->where('xr.estado', 'PENDIENTE');

        // --- LÓGICA DE FILTRADO POR TIPO ---

        if (!empty($tiposSeleccionados) && !in_array('todos', $tiposSeleccionados)) {
            $query->whereIn('xi.tipo', $tiposSeleccionados);
        }
         // --- LÓGICA DE FILTRADO POR PERIODOS---
        // Extraer Mes e Inicio
        $inicio = explode('-', $request->periodo_inicio); // [02, 2026]
        $mesInicio = (int)$inicio[0];
        $anioInicio = (int)$inicio[1];
        // Determinar si hay un rango o es mes único
        $esRango = $request->has('periodo_fin') && $request->periodo_fin !== $request->periodo_inicio;
        //integramos al Query
        $query->where(function($q) use ($mesInicio, $anioInicio, $esRango, $request) {
            if (!$esRango) {
                // CASO 1: Mes Único
                $q->where('xg.mes', $mesInicio)
                ->where('xg.anio', $anioInicio);
            } else {
                // CASO 2: Rango de Meses
                $fin = explode('-', $request->periodo_fin);
                $mesFin = (int)$fin[0];
                $anioFin = (int)$fin[1];

                // Lógica: (Anio > AnioInicio O (Anio == AnioInicio Y Mes >= MesInicio))
                // Y (Anio < AnioFin O (Anio == AnioFin Y Mes <= MesFin))
                $q->where(function($sub) use ($mesInicio, $anioInicio, $mesFin, $anioFin) {
                    $sub->whereRaw("(xg.anio * 100 + xg.mes) BETWEEN ? AND ?", [
                        ($anioInicio * 100 + $mesInicio),
                        ($anioFin * 100 + $mesFin)
                    ]);
                });
            }
        });
        if ($request->nivel_principal === 'capa_encargado' && $request->encargado_id) {
            $query->where('xd.id_responsable_remesa', $request->encargado_id);
        }

        if ($request->sub_nivel_tipo === 'panel_distrito' && $request->has('distritos')) {
            // Aplicamos el filtro a la tabla distritos (xd)
            $query->whereIn('xd.id_distrito', $request->distritos);
        }
        // CASO: SUB-NIVEL ZONA
        elseif ($request->sub_nivel_tipo === 'panel_zona' && $request->has('zona')) {
            if ($request->zona !== 'TODOS') {
                $query->where('xi.lugar', $request->zona);
            }
        }
        
        $resultados = $query->orderBy('xd.nombre')
                        ->orderBy('xi.nombre')
                        ->orderBy('xg.mes')
                        ->get();

        
       


        //dd($resultados);


        // 2. Generar la lista de meses/años del rango (Cabeceras)
        $periodos = $resultados->map(function($item) {
            return $item->anio . '-' . str_pad($item->mes, 2, '0', STR_PAD_LEFT);
        })->unique()->sort()->values();


         
            if ($request->action === 'excel') {
                $mesesNom = [
                    1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun',
                    7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
                ];

                $datosAgrupados = $resultados->groupBy('distrito')->map(function ($itemsDistrito, $distrito) use ($periodos) {
                    return $itemsDistrito->groupBy('id_iglesia')->map(function ($itemsIglesia) use ($periodos, $distrito) {
                        $primero = $itemsIglesia->first();
                        
                        $estados = $itemsIglesia->mapWithKeys(function ($item) {
                            $key = $item->anio . '-' . str_pad($item->mes, 2, '0', STR_PAD_LEFT);
                            return [$key => $item->estado];
                        });

                        return (object)[
                            'distrito'   => $distrito, // Ahora sí funcionará
                            'codigo'     => $primero->codigo,
                            'nombre'     => $primero->nombre_igle,
                            'tipo'       => $primero->tipo,
                            'nombre_pas' => $primero->nombre_pas,
                            'nombre_res' => $primero->nombre_res,
                            'estados'    => $estados
                        ];
                    });
                });
                $fechaHora = now()->format('Y-m-d_H-i');

                $nombreArchivo = "Reporte_remesas_pendientes_{$fechaHora}.xlsx";
                
                return Excel::download(
                    new PendientesExport($datosAgrupados, $periodos, $mesesNom), 
                    $nombreArchivo
                );
            }

        $totalGeneral = $resultados->count();
        $totalIglesias = $resultados->where('tipo', 'Iglesia')->count();
        $totalFiliales = $resultados->where('tipo', 'Filial')->count();
        $totalGrupos   = $resultados->where('tipo', 'Grupo')->count();

        if(count($periodos) > 1){// por si son varios meses
            $datosAgrupados = $resultados->groupBy('distrito')->map(function ($itemsDistrito) use ($periodos) {
                return $itemsDistrito->groupBy('id_iglesia')->map(function ($itemsIglesia) use ($periodos) {
                    $primero = $itemsIglesia->first();
                    
                    // Creamos un mapa de estados: "2026-02" => "ENTREGADO"
                    $estados = $itemsIglesia->mapWithKeys(function ($item) {
                                    // Creamos la llave "2026-02" (Año-Mes con dos dígitos)
                                    $key = $item->anio . '-' . str_pad($item->mes, 2, '0', STR_PAD_LEFT);
                                    return [$key => $item->estado];
                                });
                    // Nota: debes agregar 'concat(anio, "-", lpad(mes, 2, "0")) as mes_anio_key' en tu SELECT SQL
                    
                    return (object)[
                        'codigo' => $primero->codigo,
                        'nombre' => $primero->nombre_igle,
                        'tipo'   => $primero->tipo,
                        'nombre_pas' => $primero->nombre_pas,
                        'nombre_res' => $primero->nombre_res,
                        'estados' => $estados
                    ];
                });
            });
             //dd($datosAgrupados);
            $pdf = Pdf::loadView('pdf.pdf_reporte_pendientes_varios_meses', [
                'datos' => $datosAgrupados,
                'total'         => $totalGeneral,
                'totalIglesias' => $totalIglesias,
                'totalFiliales' => $totalFiliales,
                'totalGrupos'   => $totalGrupos,
                'periodos' => $periodos,
                'mesesNom' => [
                    1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun',
                    7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
                ]
            ])->setPaper('letter', 'portrait'); // Recomendado Landscape para muchas columnas
            $fechaHora = now()->format('d-m-Y_H-i');
            $nombrePdf = "Rep_Pendientes_{$fechaHora}.pdf";
            return $pdf->stream($nombrePdf);
        }else{ //por si es solo 1 mes
            $datosAgrupados = $resultados->groupBy('distrito'); 
            $pdf = Pdf::loadView('pdf.pdf_reporte_pendientes', [
                'datos' => $datosAgrupados,
                'total'         => $totalGeneral,
                'totalIglesias' => $totalIglesias,
                'totalFiliales' => $totalFiliales,
                'totalGrupos'   => $totalGrupos,
                'periodo' => $request->periodo_inicio . ($request->has('periodo_fin') ? ' a ' . $request->periodo_fin : ''),
            ])->setPaper('letter', 'portrait');

            $fechaHora = now()->format('d-m-Y_H-i');
            $nombrePdf = "Rep_Pendientes_{$fechaHora}.pdf";
        
            return $pdf->stream($nombrePdf);
        }
        

        
    }


}
