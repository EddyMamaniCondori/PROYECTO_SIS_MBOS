<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bautiso; 
use App\Models\Iglesia; 
use App\Models\Distrito;
use App\Models\Desafio;
use App\Models\DesafioEvento;
use App\Models\AsignaDesafio;
use App\Http\Requests\BautisoRequest;
use App\Http\Requests\UpdateBautisoRequest;
use App\Helpers\AuditoriaHelper;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
class BautisosController extends Controller
{
    /**
     * 'ver-bautisos',
    *        'crear-bautisos',
    *        'editar-bautisos',
    *        'eliminar-bautisos',
    *        'dashboard-mbos bautisos',
     * Display a listing of the resource.
     */

    function __construct()
    {
        // index(): PERMISO ver bautisos
        $this->middleware('permission:ver-bautisos', ['only' => ['index']]);
        // show() (Paso 1) y store() (Paso 2): PERMISO crear bautisos
        $this->middleware('permission:crear-bautisos', ['only' => ['show', 'store']]);
        // edit() y update(): PERMISO editar bautisos
        $this->middleware('permission:editar-bautisos', ['only' => ['edit', 'update']]);
        // destroy(): PERMISO eliminar bautisos
        $this->middleware('permission:eliminar-bautisos', ['only' => ['destroy']]);
        // dashboard_general(): PERMISO dashboard mbos bautisos
        $this->middleware('permission:dashboard-mbos bautisos', ['only' => ['dashboard_general']]);
        $this->middleware('permission:ver pastor-bautisos distrito', ['only' => ['bautisos_distrital']]);
        $this->middleware('permission:ver dashboard pastor-bautisos distrito', ['only' => ['dashboard_general_pastores']]);
    }
    public function index() //PERMISO ver bautisos
    {   
        $año = now()->year;
        $distritos = DB::table('distritos as xd')
                    ->leftJoin('iglesias as xi', 'xi.distrito_id', '=', 'xd.id_distrito')
                    ->leftJoin('bautisos as xb', function($join) use ($año) {
                        $join->on('xb.id_iglesia', '=', 'xi.id_iglesia')
                            ->whereRaw("EXTRACT(YEAR FROM xb.fecha_bautizo) = ?", [$año]);
                    })
                    ->select(
                        'xd.id_distrito',
                        'xd.nombre as nombre_distrito',
                        DB::raw("COALESCE(SUM(CASE WHEN xb.tipo = 'bautizo' THEN 1 ELSE 0 END), 0) as nro_bautizo"),
                        DB::raw("COALESCE(SUM(CASE WHEN xb.tipo = 'profesion de fe' THEN 1 ELSE 0 END), 0) as nro_profesion_fe"),
                        DB::raw("COALESCE(SUM(CASE WHEN xb.tipo = 'rebautismo' THEN 1 ELSE 0 END), 0) as nro_rebautismo"),
                        DB::raw("COALESCE(COUNT(xb.id_bautiso), 0) as total")
                    )
                    ->where('xd.estado', true)
                    ->groupBy('xd.id_distrito', 'xd.nombre')
                    ->orderBy('xd.nombre')
                    ->get();

        return view('bautisos.index', compact('distritos','año'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(BautisoRequest $request) //PERMISO Crear bautisos - paso 2
    {
        
        try {
            DB::beginTransaction();
            $anioActual = now()->year;
            $id_distrito = $request->id_distrito;
            $total = $request->cant_bautizo +$request->cant_profesion+$request->cant_rebautismo;
            //insertamos todos los bautismos_
            $validated = $request->validated();
            $dataToInsert = [];
            $categorias = [
                'cant_bautizo'      => 'bautizo',
                'cant_profesion'    => 'profesion de fe',
                'cant_rebautismo'   => 'rebautismo',
            ];
            foreach ($categorias as $campoCantidad => $nombreTipo) {
                $cantidad = (int) $validated[$campoCantidad];
                for ($i = 0; $i < $cantidad; $i++) {
                    $dataToInsert[] = [
                        'tipo'              => $nombreTipo,
                        'fecha_bautizo'     => $validated['fecha_bautizo'],
                        'id_iglesia'        => $validated['id_iglesia'],
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ];
                }
            }
            if (!empty($dataToInsert)) {
                DB::transaction(function () use ($dataToInsert) {
                    Bautiso::insert($dataToInsert);
                });
            }
            $desafio_bautismo = Desafio::where('id_distrito', $id_distrito)
                           ->where('anio', $anioActual)
                           ->first();
            if (!$desafio_bautismo) {
                DB::rollBack();
                return back()->with('error', "No se encontró un desafío para el distrito {$id_distrito} en el año {$anioActual}.");
            }
            $desafio_bautismo->bautizos_alcanzados = $desafio_bautismo->bautizos_alcanzados + $total;
            $desafio_bautismo->save();

            //se crea el bautizo en el desafio
            if ($request->id_desafio_evento) {
                $asignacion = AsignaDesafio::where('id_desafio', $desafio_bautismo->id_desafio)
                    ->where('id_desafio_evento', $request->id_desafio_evento)
                    ->first();
                if ($asignacion) {
                    $asignacion->increment('alcanzado', $total);
                } else {
                    // 🆕 Si no existe, crear nueva asignación con alcanzado = 1
                    AsignaDesafio::create([
                        'id_desafio' => $desafio_bautismo->id_desafio,
                        'id_desafio_evento' => $request->id_desafio_evento,
                        'desafio' => 0,
                        'alcanzado' => $total,
                    ]);
                }
            }
            //AuditoriaHelper::registrar('CREATE', 'Bautisos', $bautiso->id_bautiso);
            DB::commit();
            return redirect()->route('bautisos.show', ['bautiso' => $id_distrito])
                ->with('success', 'Registro creado correctamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el bautiso: ' . $e->getMessage()], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id) //PERMISO Crear bautisos - paso 1
    {
        $anioActual = now()->year;

        $distrito = Distrito::find($id);

        $iglesias = Iglesia::where('estado', true)
            ->where('distrito_id', $id) // solo iglesias del distrito 11
            ->orderBy('nombre') // opcional: para que salgan ordenadas alfabéticamente
            ->get();
        $fechaHoy = now(); // o Carbon::now()

        $desafio_eventos = DesafioEvento::where('estado', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_final', '>=', now())
            ->get();

        $bautisos = DB::table('bautisos as xb')
            ->join('iglesias as xi', 'xb.id_iglesia', '=', 'xi.id_iglesia')
            ->select('xb.*', 'xi.nombre as nombre_iglesia', 'xi.tipo as tipo_iglesia')
            ->where('xi.distrito_id', $id)
            ->whereRaw("EXTRACT(YEAR FROM xb.fecha_bautizo) = ?", [$anioActual])
            ->orderBy('xb.created_at', 'desc')
            ->get();
    
        return view('bautisos.index_distrital', compact('iglesias', 'anioActual', 'bautisos','distrito', 'desafio_eventos'));
    }   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) //PERMISO editar bautisos
    {
        $bautizo = Bautiso::find($id);

        $registro = DB::table('iglesias')
            ->select('distrito_id')
            ->where('id_iglesia', $bautizo->id_iglesia)
            ->first();

        $id_distrito = $registro->distrito_id;
        $iglesias = Iglesia::where('estado', true)
            ->where('distrito_id', $id_distrito)
            ->orderBy('nombre') // opcional: para que salgan ordenadas alfabéticamente
            ->get();
        
        return view('bautisos.edit', compact('bautizo','iglesias','id_distrito'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBautisoRequest $request, string $id) //PERMISO editar bautisos paso 2
    {
        try {
            DB::beginTransaction();
            $id_distrito = $request->id_distrito;
            $bautizo = Bautiso::find($id);
            $bautizo->update($request->validated());
            

            DB::commit();
            
            return redirect()->route('bautisos.show', ['bautiso' => $id_distrito])
                ->with('success', 'Registro Actualizado correctamente.');

            AuditoriaHelper::registrar('UPDATE', 'Bautisos', $bautizo->id_bautiso);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) //FUNCIONA //PERMISO eliminar bautisos
    {
        try {
            DB::beginTransaction();
            // Buscar estudiante, si no existe lanzar excepción o manejar error
            $bautizo = Bautiso::find($id);
            if (!$bautizo) {
                return redirect()->route('bautisos.index')
                    ->with('error', 'Bautiso no encontrado');
            }
            $registro = DB::table('iglesias')
            ->select('distrito_id')
            ->where('id_iglesia', $bautizo->id_iglesia)
            ->first();

            $id_distrito = $registro->distrito_id;

            //DD($id_distrito, $bautizo);
            $bautizo->delete();
            AuditoriaHelper::registrar('DELETE', 'Bautisos', $bautizo->id_bautiso);
            DB::commit();
            return redirect()->route('bautisos.show', ['bautiso' => $id_distrito])
                ->with('success', 'Registro Eliminado correctamente.');


        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('bautisos.index')
                ->with('error', 'Error al Eliminar al Bautiso: ' . $e->getMessage());
        }
    }

    public function dashboard_general() //PARA VER EL DASHBOARD GENERAL DE BAUTISMOS //PERMISO 'dashboard mbos bautisos'
    {
        $anio= now()->year;
        
        // Consulta de los desafíos por distrito
        $desafios = DB::table('desafios as xd')
            ->join('distritos as xdd', 'xd.id_distrito', '=', 'xdd.id_distrito')
            ->where('xd.anio', $anio)
            ->select(
                'xdd.id_distrito',
                'xdd.nombre as nombre_distrito',
                'xd.desafio_bautizo',
                'xd.bautizos_alcanzados',
                DB::raw('(xd.desafio_bautizo - xd.bautizos_alcanzados) as diferencia')
            )
            ->orderBy('xdd.nombre')
            ->get();
        //consulta de sacar los bautisos por mes del año actual.
        $bautizosPorDistrito = DB::table('distritos as xd')
            ->leftJoin('iglesias as xi', 'xi.distrito_id', '=', 'xd.id_distrito')
            ->leftJoin('bautisos as xb', function($join) use ($anio) {
                $join->on('xb.id_iglesia', '=', 'xi.id_iglesia')
                    ->whereRaw('EXTRACT(YEAR FROM xb.fecha_bautizo) = ?', [$anio]);
            })
            ->where('xd.estado', true)
            ->groupBy('xd.id_distrito')
            ->select(
                'xd.id_distrito',
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM xb.fecha_bautizo) = 1 THEN 1 ELSE 0 END), 0) AS enero'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM xb.fecha_bautizo) = 2 THEN 1 ELSE 0 END), 0) AS febrero'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM xb.fecha_bautizo) = 3 THEN 1 ELSE 0 END), 0) AS marzo'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM xb.fecha_bautizo) = 4 THEN 1 ELSE 0 END), 0) AS abril'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM xb.fecha_bautizo) = 5 THEN 1 ELSE 0 END), 0) AS mayo'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM xb.fecha_bautizo) = 6 THEN 1 ELSE 0 END), 0) AS junio'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM xb.fecha_bautizo) = 7 THEN 1 ELSE 0 END), 0) AS julio'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM xb.fecha_bautizo) = 8 THEN 1 ELSE 0 END), 0) AS agosto'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM xb.fecha_bautizo) = 9 THEN 1 ELSE 0 END), 0) AS septiembre'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM xb.fecha_bautizo) = 10 THEN 1 ELSE 0 END), 0) AS octubre'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM xb.fecha_bautizo) = 11 THEN 1 ELSE 0 END), 0) AS noviembre'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM xb.fecha_bautizo) = 12 THEN 1 ELSE 0 END), 0) AS diciembre')
            )
            ->get();

        // Preparar para frontend (por ejemplo con etiquetas para la gráfica)
        $graficos = $bautizosPorDistrito->map(function ($d) {
            return [
                'id_distrito' => $d->id_distrito,
                'meses' => [
                    'Enero' => (int)$d->enero,
                    'Febrero' => (int)$d->febrero,
                    'Marzo' => (int)$d->marzo,
                    'Abril' => (int)$d->abril,
                    'Mayo' => (int)$d->mayo,
                    'Junio' => (int)$d->junio,
                    'Julio' => (int)$d->julio,
                    'Agosto' => (int)$d->agosto,
                    'Septiembre' => (int)$d->septiembre,
                    'Octubre' => (int)$d->octubre,
                    'Noviembre' => (int)$d->noviembre,
                    'Diciembre' => (int)$d->diciembre,
                ],
            ];
        });

        // para el grafica de tipos de del año actual
        $tipos = DB::table('distritos as xd')
            ->leftJoin('iglesias as xi', 'xi.distrito_id', '=', 'xd.id_distrito')
            ->leftJoin('bautisos as xb', function ($join) use ($anio) {
                $join->on('xb.id_iglesia', '=', 'xi.id_iglesia')
                    ->whereRaw('EXTRACT(YEAR FROM xb.fecha_bautizo) = ?', [$anio]);
            })
            ->where('xd.estado', true)
            ->select(
                'xd.id_distrito',
                'xd.nombre as nombre_distrito',
                DB::raw("COALESCE(SUM(CASE WHEN xb.tipo = 'bautizo' THEN 1 ELSE 0 END), 0) as nro_bautizo"),
                DB::raw("COALESCE(SUM(CASE WHEN xb.tipo = 'profesion de fe' THEN 1 ELSE 0 END), 0) as nro_profesion_fe"),
                DB::raw("COALESCE(SUM(CASE WHEN xb.tipo = 'rebautismo' THEN 1 ELSE 0 END), 0) as nro_rebautismo")
            )
            ->groupBy('xd.id_distrito', 'xd.nombre')
            ->orderBy('xd.nombre')
            ->get();
        
        $graficos_tipos = $tipos->map(function ($d) {
            return [
                'id_distrito' => $d->id_distrito,
                'nombre' => $d->nombre_distrito,
                'categorias' => ['Bautizos', 'Profesión de Fe', 'Rebautismos'],
                'valores' => [
                    (int) $d->nro_bautizo,
                    (int) $d->nro_profesion_fe,
                    (int) $d->nro_rebautismo
                ]
            ];
        });

         // 🔹 Preparamos datos listos para ApexCharts
        $graficos_final = $desafios->map(function ($d) {
            return [
                'id_distrito' => $d->id_distrito,
                'nombre' => $d->nombre_distrito,
                'categorias' => ['Desafío', 'Alcanzado', 'Diferencia'],
                'valores' => [
                    (int) $d->desafio_bautizo,
                    (int) $d->bautizos_alcanzados,
                    (int) $d->diferencia
                ]
            ];
        });

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
        // 🔵 3. BAUTISMOS – DISTRITOS
        // ============================

        $b_desafio_d = DB::table('desafios')
            ->where('anio', $anio)
            ->sum('desafio_bautizo');

        $b_alcanzado_d = DB::table('desafios')
            ->where('anio', $anio)
            ->sum('bautizos_alcanzados');

        $b_diferencia_d = $b_alcanzado_d - $b_desafio_d;
        $porcentajeGeneral = $b_desafio > 0 
        ? round(($b_alcanzado / $b_desafio) * 100, 1)
        : 0;

        return view('bautisos.dashboard_general', compact('graficos', 'anio','desafios', 'graficos_tipos', 'graficos_final',
        'b_desafio',
        'b_alcanzado',
        'b_diferencia',
        'b_desafio_d',
        'b_alcanzado_d',
        'b_diferencia_d',
        'porcentajeGeneral')); 
    }
    //
    //_________________________________________________________DASBOAR PARA PASTOR DISTRITAL___________________________________________________________
    //

    public function dashboard_general_pastores() //PERMISO 'ver dashboard pastor-bautisos distrito',
    {   
        $anioActual = now()->year; //muestro los estudiantes del año actual
        $persona = Auth::user(); 
        
        
        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        //$id_pastor = 3;
        //$distrito = Distrito::where('id_pastor', $id_pastor)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;// todos los estudiantes del distrito Bolivar  

        $anioDistritos = DB::table('distritos')
            ->where('estado', true)
            ->value('año');

        if (!$anioDistritos) {
            return view('desafio.index', [
                'desafios' => collect([]),
                'anioActual' => $anioActual,
                'mensaje' => 'No hay distritos activos en el sistema.'
            ]);
        }
        $anio = ($anioDistritos < $anioActual) ? $anioDistritos : $anioActual;


         /* ---------------------------------------------------------
        1) DESAFÍO — SOLO PARA ESTE DISTRITO
        --------------------------------------------------------- */
        $desafio = DB::table('desafios')
            ->where('anio', $anio)
            ->where('id_distrito', $id_distrito)
            ->select(
                'desafio_bautizo',
                'bautizos_alcanzados',
                DB::raw('(desafio_bautizo - bautizos_alcanzados) AS diferencia')
            )
            ->first();

        $grafico_desafio = [
            'nombre' => $distrito->nombre,
            'categorias' => ['Desafío', 'Alcanzado', 'Diferencia'],
            'valores' => [
                (int) ($desafio->desafio_bautizo ?? 0),
                (int) ($desafio->bautizos_alcanzados ?? 0),
                (int) ($desafio->diferencia ?? 0),
            ]
        ];

        /* ---------------------------------------------------------
            2) BAUTIZOS POR MES — SOLO ESTE DISTRITO
        --------------------------------------------------------- */
        $bautizosMes = DB::table('iglesias as i')
            ->leftJoin('bautisos as b', function ($join) use ($anio) {
                $join->on('b.id_iglesia', '=', 'i.id_iglesia')
                    ->whereRaw('EXTRACT(YEAR FROM b.fecha_bautizo) = ?', [$anio]);
            })
            ->where('i.distrito_id', $id_distrito)
            ->select(
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM b.fecha_bautizo)=1 THEN 1 ELSE 0 END),0) AS enero'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM b.fecha_bautizo)=2 THEN 1 ELSE 0 END),0) AS febrero'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM b.fecha_bautizo)=3 THEN 1 ELSE 0 END),0) AS marzo'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM b.fecha_bautizo)=4 THEN 1 ELSE 0 END),0) AS abril'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM b.fecha_bautizo)=5 THEN 1 ELSE 0 END),0) AS mayo'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM b.fecha_bautizo)=6 THEN 1 ELSE 0 END),0) AS junio'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM b.fecha_bautizo)=7 THEN 1 ELSE 0 END),0) AS julio'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM b.fecha_bautizo)=8 THEN 1 ELSE 0 END),0) AS agosto'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM b.fecha_bautizo)=9 THEN 1 ELSE 0 END),0) AS septiembre'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM b.fecha_bautizo)=10 THEN 1 ELSE 0 END),0) AS octubre'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM b.fecha_bautizo)=11 THEN 1 ELSE 0 END),0) AS noviembre'),
                DB::raw('COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM b.fecha_bautizo)=12 THEN 1 ELSE 0 END),0) AS diciembre')
            )
            ->first();

        $grafico_meses = [
            'nombre' => $distrito->nombre,
            'categorias' => [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ],
            'valores' => [
                (int) $bautizosMes->enero,
                (int) $bautizosMes->febrero,
                (int) $bautizosMes->marzo,
                (int) $bautizosMes->abril,
                (int) $bautizosMes->mayo,
                (int) $bautizosMes->junio,
                (int) $bautizosMes->julio,
                (int) $bautizosMes->agosto,
                (int) $bautizosMes->septiembre,
                (int) $bautizosMes->octubre,
                (int) $bautizosMes->noviembre,
                (int) $bautizosMes->diciembre,
            ]
        ];

        /* ---------------------------------------------------------
        3) TIPOS DE BAUTIZO — SOLO ESTE DISTRITO
        --------------------------------------------------------- */
        $tipos = DB::table('iglesias as i')
            ->leftJoin('bautisos as b', function ($join) use ($anio) {
                $join->on('b.id_iglesia', '=', 'i.id_iglesia')
                    ->whereRaw('EXTRACT(YEAR FROM b.fecha_bautizo)=?', [$anio]);
            })
            ->where('i.distrito_id', $id_distrito)
            ->select(
                DB::raw("COALESCE(SUM(CASE WHEN b.tipo = 'bautizo' THEN 1 ELSE 0 END),0) AS nro_bautizo"),
                DB::raw("COALESCE(SUM(CASE WHEN b.tipo = 'profesion de fe' THEN 1 ELSE 0 END),0) AS nro_profesion_fe"),
                DB::raw("COALESCE(SUM(CASE WHEN b.tipo = 'rebautismo' THEN 1 ELSE 0 END),0) AS nro_rebautismo")
            )
            ->first();

        $grafico_tipos = [
            'nombre' => $distrito->nombre,
            'categorias' => ['Bautizos', 'Profesión de Fe', 'Rebautismos'],
            'valores' => [
                (int) $tipos->nro_bautizo,
                (int) $tipos->nro_profesion_fe,
                (int) $tipos->nro_rebautismo,
            ]
        ];
        /* ---------------------------------------------------------
        4) BAUTIZOS POR IGLESIA — SOLO ESTE DISTRITO
        --------------------------------------------------------- */

        $bautizosPorIglesia = DB::table('iglesias as i')
            ->leftJoin('bautisos as b', function ($join) use ($anio) {
                $join->on('b.id_iglesia', '=', 'i.id_iglesia')
                    ->whereRaw('EXTRACT(YEAR FROM b.fecha_bautizo) = ?', [$anio]);
            })
            ->where('i.distrito_id', $id_distrito)
            ->select(
                'i.nombre as iglesia',
                DB::raw('COUNT(b.id_bautiso) AS total_bautizos')
            )
            ->groupBy('i.nombre')
            ->orderBy('i.nombre')
            ->get();

        // Preparar datos para ApexCharts
        $grafico_iglesias = [
            'nombre'     => $distrito->nombre,
            'categorias' => $bautizosPorIglesia->pluck('iglesia'),
            'valores'    => $bautizosPorIglesia->pluck('total_bautizos')->map(fn($v) => (int)$v),
        ];
        return view('bautisos.dashboard_general_distrital', compact('distrito', 'anio','grafico_desafio', 'grafico_meses', 'grafico_tipos','grafico_iglesias')); 
    }
    public function bautisos_distrital()//EL PASTOR VE SU AVANCE DE BAUTISMOS DASHBOARD //PERMISO 'ver pastor-bautisos distrito',
    {
        $anioActual = now()->year;
        $persona = Auth::user(); 
        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        //$id_pastor = 3;
        //$distrito = Distrito::where('id_pastor', $id_pastor)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;// todos los estudiantes del distrito Bolivar  

        $anioDistritos = DB::table('distritos')
            ->where('estado', true)
            ->value('año');

        if (!$anioDistritos) {
            return view('desafio.index', [
                'desafios' => collect([]),
                'anioActual' => $anioActual,
                'mensaje' => 'No hay distritos activos en el sistema.'
            ]);
        }
        $anio = ($anioDistritos < $anioActual) ? $anioDistritos : $anioActual;
        $bautizos = DB::table('bautisos as xb')
        ->join('iglesias as xi', 'xb.id_iglesia', '=', 'xi.id_iglesia')
        ->where('xi.distrito_id', $id_distrito)
        ->whereYear('xb.fecha_bautizo', $anioActual) 
        ->select(
            'xi.nombre as iglesia',
            'xi.tipo as tipo_iglesia',
            'xb.*'
        )
        ->get();

        return view('bautisos.bautisos_x_distrito', compact('distrito', 'anio','bautizos')); 
    }


    
}
