<?php

namespace App\Http\Controllers;

use App\Models\BautismoCapellan;
use App\Models\UnidadEducativa;
use App\Models\Desafio;
use App\Http\Requests\BautizoCapellanRequest; 
use App\Http\Requests\UpdateBautizoCapellanRequest; 


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
class BautismoCapellanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BautizoCapellanRequest $request)
    {
         try {
            DB::beginTransaction();
            $anioActual = now()->year;
            $id_ue = $request->id_ue;

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
                        'id_ue'        => $validated['id_ue'],
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ];
                }
            }

            if (!empty($dataToInsert)) {
                DB::transaction(function () use ($dataToInsert) {
                    BautismoCapellan::insert($dataToInsert);
                });
            }
            $unidadeducativa = UnidadEducativa::where('id_ue', $id_ue)
                           ->where('año', $anioActual)
                           ->first();
            if (!$unidadeducativa) {
                DB::rollBack();
                return back()->with('error', "No se encontró un desafío para la Unidad Educativa {$id_ue} en el año {$anioActual}.");
            }
            $unidadeducativa->bautismos_alcanzados = $unidadeducativa->bautismos_alcanzados + $total;
            $unidadeducativa->save();
            DB::commit();
            return redirect()->route('bautizos_cape.show', ['id_ue' => $id_ue])
                ->with('success', 'Registro creado correctamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el bautiso: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_ue)
    {
        $anioActual = now()->year;

        $unidad_educativas = UnidadEducativa::find($id_ue);
        $fechaHoy = now(); // o Carbon::now()
        $bautizos_capellanes = DB::table('bautismo_capellans as xb')
        ->select('xb.*')
        ->where('xb.id_ue', $id_ue)
        ->whereRaw('EXTRACT(YEAR FROM xb.fecha_bautizo) = ?', [$anioActual])
        ->orderBy('xb.created_at', 'desc')
        ->get();
    
        return view('bautisos_capellanes.index_distrital', 
        compact('anioActual', 'bautizos_capellanes','unidad_educativas'));
    } 

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BautismoCapellan $bautismoCapellan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBautizoCapellanRequest $request, string $id)
    {
        //dd('estas en update', $request);
        try {
            DB::beginTransaction();
            $id_ue = $request->id_ue;
            $bautizo = BautismoCapellan::find($id);
            $bautizo->update($request->validated());
            

            DB::commit();
            
            return redirect()->route('bautizos_cape.show', ['id_ue' => $id_ue])
                ->with('success', 'Registro Actualizado correctamente.');

            //AuditoriaHelper::registrar('UPDATE', 'Bautisos', $bautizo->id_bautiso);
        } catch (\Exception $e) {
           DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $anio = now()->year;
        $anio_sistema = DB::table('desafios')->max('anio');
        if ($anio > $anio_sistema) {
            $anio = $anio_sistema;
        }
        try {
       
            DB::beginTransaction();
            // Buscar estudiante, si no existe lanzar excepción o manejar error
            $bautizo = BautismoCapellan::find($id);
            if (!$bautizo) {
                return redirect()->route('bautisos.index')
                    ->with('error', 'Bautiso no encontrado');
            }
            $registro = DB::table('unidad_educativas')
            ->select('id_ue')
            ->where('id_ue', $bautizo->id_ue)
            ->first();
            $id_ue = $registro->id_ue;
            
            $desafio = Desafio::where('id_ue', $id_ue)
                ->where('anio', $anio) // Ordena por anio de mayor a menor
                ->first();
            $desafio->decrement('bautizos_alcanzados');
            $desafio->save();
            $bautizo->delete();
            DB::commit();
            return redirect()->route('bautizos_cape.show', ['id_ue' => $id_ue])
                ->with('success', 'Registro Eliminado correctamente.');


        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('bautisos.index')
                ->with('error', 'Error al Eliminar al Bautiso: ' . $e->getMessage());
        }
    }

    /* ---------------------------------------------------------
        4) DASBOARD DE BAUTISOS PARA CAPELLANES
        --------------------------------------------------------- */



    public function dashboard_general_pastores() //PERMISO 'ver dashboard pastor-bautisos distrito',
    {   
        $anioActual = now()->year;
        $persona = Auth::user();
        $id_ue = null; 
        // 1. Lógica para el CAPELLÁN
        if ($persona->hasRole('ASEA_capellan')) {
            $ue = DB::table('capellan')
                    ->select('id_ue')
                    ->where('id_pastor', $persona->id_persona)
                    ->first();

            if (!$ue) {
                return redirect()->route('panel')->with('error', 'No tienes una Unidad Educativa asignada como Capellán.');
            }
            $id_ue = $ue->id_ue;
        }
        // 2. Lógica para el DIRECTOR
        if ($persona->hasRole('ASEA_director')) {
            $ue_director = DB::table('unidad_educativas')
                            ->select('id_ue')
                            ->where('id_director', $persona->id_persona)
                            ->first();

            if (!$ue_director) {
                return redirect()->route('panel')->with('error', 'No eres director de ninguna Unidad Educativa.');
            }
            $id_ue = $ue_director->id_ue;
        }
        if (!$id_ue) {
            return redirect()->route('panel')->with('error', 'No se pudo determinar tu Unidad Educativa.');
        }  
        $unidadeducativa = UnidadEducativa::findOrFail($id_ue);
        $anioDistritos = DB::table('unidad_educativas')
            ->where('estado', true)
            ->value('año');

        $anio = ($anioDistritos < $anioActual) ? $anioDistritos : $anioActual;

        
         /* ---------------------------------------------------------
        1) DESAFÍO — SOLO PARA ESTE DISTRITO
        --------------------------------------------------------- */
        $desafio = DB::table('unidad_educativas')
            ->where('año', $anio)
            ->where('id_ue', $id_ue)
            ->select(
                'desafios_bautismos',
                'bautismos_alcanzados',
                DB::raw('(desafios_bautismos - bautismos_alcanzados) AS diferencia')
            )
            ->first();

        $grafico_desafio = [
            'nombre' => $unidadeducativa->nombre,
            'categorias' => ['Desafío', 'Alcanzado', 'Diferencia'],
            'valores' => [
                (int) ($desafio->desafios_bautismos ?? 0),
                (int) ($desafio->bautismos_alcanzados ?? 0),
                (int) ($desafio->diferencia ?? 0),
            ]
        ];

        /* ---------------------------------------------------------
            2) BAUTIZOS POR MES — SOLO ESTE DISTRITO
        --------------------------------------------------------- */
        
        $bautizosMes = DB::table('bautismo_capellans')
            ->where('id_ue', $id_ue)
            ->whereRaw('EXTRACT(YEAR FROM fecha_bautizo) = ?', [2026]) // O usar la variable $anio
            ->select(
                DB::raw("COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM fecha_bautizo) = 1 THEN 1 ELSE 0 END), 0) AS enero"),
                DB::raw("COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM fecha_bautizo) = 2 THEN 1 ELSE 0 END), 0) AS febrero"),
                DB::raw("COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM fecha_bautizo) = 3 THEN 1 ELSE 0 END), 0) AS marzo"),
                DB::raw("COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM fecha_bautizo) = 4 THEN 1 ELSE 0 END), 0) AS abril"),
                DB::raw("COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM fecha_bautizo) = 5 THEN 1 ELSE 0 END), 0) AS mayo"),
                DB::raw("COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM fecha_bautizo) = 6 THEN 1 ELSE 0 END), 0) AS junio"),
                DB::raw("COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM fecha_bautizo) = 7 THEN 1 ELSE 0 END), 0) AS julio"),
                DB::raw("COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM fecha_bautizo) = 8 THEN 1 ELSE 0 END), 0) AS agosto"),
                DB::raw("COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM fecha_bautizo) = 9 THEN 1 ELSE 0 END), 0) AS septiembre"),
                DB::raw("COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM fecha_bautizo) = 10 THEN 1 ELSE 0 END), 0) AS octubre"),
                DB::raw("COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM fecha_bautizo) = 11 THEN 1 ELSE 0 END), 0) AS noviembre"),
                DB::raw("COALESCE(SUM(CASE WHEN EXTRACT(MONTH FROM fecha_bautizo) = 12 THEN 1 ELSE 0 END), 0) AS diciembre")
            )
            ->first();

    
        $grafico_meses = [
            'nombre' => $unidadeducativa->nombre,
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
        $tipos = DB::table('bautismo_capellans')
            ->where('id_ue', $id_ue)
            ->whereRaw('EXTRACT(YEAR FROM fecha_bautizo) = ?', [2026])
            ->select(
                DB::raw("COALESCE(SUM(CASE WHEN tipo = 'bautizo' THEN 1 ELSE 0 END), 0) AS nro_bautizo"),
                DB::raw("COALESCE(SUM(CASE WHEN tipo = 'profesion de fe' THEN 1 ELSE 0 END), 0) AS nro_profesion_fe"),
                DB::raw("COALESCE(SUM(CASE WHEN tipo = 'rebautismo' THEN 1 ELSE 0 END), 0) AS nro_rebautismo")
            )
            ->first();

        $grafico_tipos = [
            'nombre' => $unidadeducativa->nombre,
            'categorias' => ['Bautizos', 'Profesión de Fe', 'Rebautismos'],
            'valores' => [
                (int) $tipos->nro_bautizo,
                (int) $tipos->nro_profesion_fe,
                (int) $tipos->nro_rebautismo,
            ]
        ];
        return view('bautisos_capellanes.dashboard_general_distrital', compact('unidadeducativa', 'anio','grafico_desafio', 'grafico_meses', 'grafico_tipos')); 
    }



    public function dashboard_bautisos_asea_general() //PARA VER EL DASHBOARD GENERAL DE BAUTISMOS //PERMISO 'dashboard mbos bautisos'
    {
        $anio = now()->year;

        $anio_sistema = DB::table('desafios')->max('anio');
        if ($anio > $anio_sistema) {
            $anio = $anio_sistema;
        }
        // Consulta de los desafíos y alcanzados por distrito
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
        
        //dd($desafios);
        // Preparamos los arrays para la gráfica
        $nombresDistritos = $desafios->pluck('nombre_distrito');
        $datosAlcanzados = $desafios->pluck('bautizos_alcanzados');
        $datosDesafio = $desafios->pluck('desafio_bautizo');

        // Calculamos los porcentajes
        $porcentajes = $desafios->map(function ($item) {
            if ($item->desafio_bautizo > 0) {
                return round(($item->bautizos_alcanzados / $item->desafio_bautizo) * 100, 2);
            }
            return $item->bautizos_alcanzados > 0 ? 100 : 0; // Si no hay desafío pero hay bautizos, es 100%
        });
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
                'categorias' => ['Bautismos', 'Profesión de Fe', 'Rebautismos'],
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
        // 🔵 3. BAUTISMOS – MBOS
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
        'porcentajeGeneral','nombresDistritos', 'datosAlcanzados', 'datosDesafio', 'porcentajes')); 
    }
}
