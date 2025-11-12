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

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
class BautisosController extends Controller
{
    /**
     * 
     * Display a listing of the resource.
     */
    public function index()
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

    public function index_distrital()
    {
        $anioActual = now()->year;
        $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito; // todos los estudiantes del distrito Bolivar
        $bautizos = Bautiso::join('iglesias as xi', 'bautisos.iglesia_id', '=', 'xi.id_iglesia')
        ->select(
            'bautisos.*',
            'xi.nombre as nombre_iglesia'
        )
        ->where('xi.distrito_id', $id_distrito)
        ->whereRaw('EXTRACT(YEAR FROM bautisos.fecha_bautizo) = ?', [$anioActual])
        ->orderBy('bautisos.created_at', 'desc')
        ->get();
        return view('bautisos.index', compact('bautizos','anioActual'));
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
    public function store(BautisoRequest $request)
    {
        //dd($request);
        try {
            DB::beginTransaction();
            $anioActual = now()->year;
            $id_distrito = $request->id_distrito;
            $bautiso = Bautiso::create($request->validated()); 
            //se crea el bautizo a la 
            
            if ($request->id_desafio_evento) {
                $desafio = Desafio::where('anio', $anioActual)
                    ->where('id_distrito', $id_distrito)
                    ->first();
                if (!$desafio) {
                    DB::rollBack();
                    return back()->with('error', "No se encontró un desafío para el distrito {$id_distrito} en el año {$anioActual}.");
                }
                $asignacion = AsignaDesafio::where('id_desafio', $desafio->id_desafio)
                    ->where('id_desafio_evento', $request->id_desafio_evento)
                    ->first();
                if ($asignacion) {
                    // ✅ Si existe, incrementar 'alcanzado'
                    $asignacion->increment('alcanzado');
                } else {
                    // 🆕 Si no existe, crear nueva asignación con alcanzado = 1
                    AsignaDesafio::create([
                        'id_desafio' => $desafio->id_desafio,
                        'id_desafio_evento' => $request->id_desafio_evento,
                        'desafio' => 0,
                        'alcanzado' => 1,
                    ]);
                }
            }
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
    public function show(string $id)
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
    public function edit(string $id)
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
    public function update(UpdateBautisoRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $id_distrito = $request->id_distrito;
            $bautizo = Bautiso::find($id);
            $bautizo->update($request->validated());
            

            DB::commit();
            
            return redirect()->route('bautisos.show', ['bautiso' => $id_distrito])
                ->with('success', 'Registro Actualizado correctamente.');


        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) //FUNCIONA
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
            
            DB::commit();
            return redirect()->route('bautisos.show', ['bautiso' => $id_distrito])
                ->with('success', 'Registro Eliminado correctamente.');


        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('bautisos.index')
                ->with('error', 'Error al Eliminar al Bautiso: ' . $e->getMessage());
        }
    }

    public function dashboard_general()
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

        // para el grafica de tipos
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
        return view('bautisos.dashboard_general', compact('graficos', 'anio','desafios', 'graficos_tipos', 'graficos_final'));

        
    }

}
