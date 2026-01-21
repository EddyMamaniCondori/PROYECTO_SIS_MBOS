<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pastor; 
use App\Models\Iglesia;
use App\Models\Desafio; 
use App\Models\Distrito; 
use App\Models\Mensual; 
use App\Models\AnualIglesia; 
use Illuminate\Support\Facades\DB;
use App\Http\Requests\DesafioMensualRequest;
use Exception;

class DesafioMensualController extends Controller
{
    /**
     * 'ver-desafios mensuales',
        *    'crear-desafios mensuales',
         *   'editar fechas-desafios mensuales',
        *    'editar desafios-desafios mensuales',
     * Display a listing of the resource.
     */
    function __construct()
    {
        // index(): permision 'ver - desafios mensuales'
        $this->middleware('permission:ver-desafios mensuales', ['only' => ['index']]);

        // store(): permision 'crear - desafios mensuales' (Asumo que 'create' también debería estar protegido por este)
        $this->middleware('permission:crear-desafios mensuales', ['only' => ['create', 'store']]);

        // update(): permision 'editar fechas - desafios mensuales'
        $this->middleware('permission:editar fechas-desafios mensuales', ['only' => ['update']]);

        // update_desafios(): permision 'editar desafios - desafios mensuales'
        $this->middleware('permission:editar desafios-desafios mensuales', ['only' => ['update_desafios']]);
        $this->middleware('permission:ver los blancos de 1 mes-desafios mensuales', ['only' => ['index_mes']]);
        $this->middleware('permission:editar desafios mes masivo-desafios mensuales', ['only' => ['index_mes_masivo','updateMasivo']]);
        $this->middleware('permission:graficos x mes MBOS-desafios mensuales', ['only' => ['dashboard_mes_x_distrito']]);
        $this->middleware('permission:graficos todos los meses MBOS-desafios mensuales', ['only' => ['resumenMensualGeneral']]);
    }

     public function index()  //permision 'ver - desafios mensuales',
    {
        try {
            $anioActual = now()->year;

            // Obtener el año configurado en la tabla distritos
            $anioDistritos = DB::table('distritos')
                ->where('estado', true)
                ->value('año');
            
            // Si no hay distritos activos
            if (!$anioDistritos) {
                return view('desafio.index_bautizo', [
                    'desafios' => collect([]),
                    'anioActual' => $anioActual,
                    'mensaje' => 'No hay distritos activos en el sistema.',
                    'ultimoMes' => null,
                    'siguienteMes' => 1
                ]);
            }
            
            // Determinar qué año usar
            $anio = ($anioDistritos < $anioActual) ? $anioDistritos : $anioActual;
            
            // Obtener todos los desafíos mensuales del año
            $mensuales = Mensual::select('mensuales.mes', 'mensuales.anio', 'mensuales.fecha_limite')
                ->distinct()
                ->join('desafios as xd', 'mensuales.id_desafio', '=', 'xd.id_desafio')
                ->where('mensuales.anio', $anio)
                ->orderBy('mensuales.mes', 'asc')
                ->get();
            
            // Determinar el último mes creado y el siguiente mes a crear
            $ultimoMes = null;
            $siguienteMes = 1; // Por defecto, enero
            
            if ($mensuales->isNotEmpty()) {
                // Obtener el mes más alto (último creado)
                $ultimoMes = $mensuales->max('mes');
                
                // Calcular el siguiente mes
                if ($ultimoMes < 12) {
                    $siguienteMes = $ultimoMes + 1;
                } else {
                    // Si ya llegamos a diciembre (12), el siguiente sería enero del próximo año
                    $siguienteMes = 13; // O podrías manejarlo de otra forma
                }
            }
            //dd($mensuales, $anio, $siguienteMes);
            return view('desafio_mensuales.index_mes', compact('mensuales', 'anio', 'siguienteMes'));
            
        } catch (\Exception $e) {
            \Log::error('Error en index de DesafioMensualController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al cargar los desafíos mensuales.');
        }
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
     */ ///CREA UN MES DESAFIO PARA TODOS LOS DESAFIOS
    public function store(DesafioMensualRequest $request)//permision 'crear - desafios mensuales',
    {
        try {
            // Los datos ya vienen validados
            $validated = $request->validated();

            // 1. Buscar todos los desafíos  del año
            $desafios = Desafio::where('anio', $validated['anio'])
                ->get();

            // Verificar si existen desafíos para ese año
            if ($desafios->isEmpty()) {
                return redirect()->back()->with('error', 'No existen desafíos para el año ' . $validated['anio']);
            }

            // Verificar si ya existe un mensual para este mes y año
            $existeMensual = Mensual::where('mes', $validated['mes'])
                ->where('anio', $validated['anio'])
                ->exists();

            if ($existeMensual) {
                return redirect()->back()->with('error', 'Ya existen registros mensuales para el mes ' . $validated['mes'] . ' del año ' . $validated['anio']);
            }

            $registrosCreados = 0;

            // 2. Recorrer cada desafío y crear un registro mensual
            foreach ($desafios as $desafio) {
                Mensual::create([
                    'mes' => $validated['mes'],
                    'anio' => $validated['anio'],
                    'fecha_limite' => $validated['fecha_limite'],
                    'id_desafio' => $desafio->id_desafio
                ]);
                
                $registrosCreados++;
            }

            return redirect()->back()->with('success', "Se crearon exitosamente {$registrosCreados} registros mensuales para el mes {$validated['mes']} del año {$validated['anio']}");

        } catch (\Exception $e) {
            \Log::error('Error en store de DesafioMensualController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al crear los registros mensuales: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    //actualizar el desafio mensual pero solo la fecha
    public function update(DesafioMensualRequest $request, string $id) //permision 'editar fechas - desafios mensuales',
    {
        try {
            // Los datos ya vienen validados
            $validated = $request->validated();

            // 1. Buscar todos los mensuales que coincidan con el mes y año del request
            $mensuales = Mensual::where('mes', $validated['mes'])
                ->where('anio', $validated['anio'])
                ->get();

            // Verificar si existen registros
            if ($mensuales->isEmpty()) {
                return redirect()->back()->with('error', 'No se encontraron registros mensuales para el mes ' . $validated['mes'] . ' del año ' . $validated['anio']);
            }

            $registrosActualizados = 0;

            // 2. Recorrer todos los mensuales encontrados y actualizar solo la fecha_limite
            foreach ($mensuales as $mensual) {
                $mensual->update([
                    'fecha_limite' => $validated['fecha_limite']
                ]);
                
                $registrosActualizados++;
            }

            return redirect()->back()->with('success', "Se actualizó la fecha límite en {$registrosActualizados} registros mensuales.");

        } catch (\Exception $e) {
            \Log::error('Error en update de DesafioMensualController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar los registros mensuales: ' . $e->getMessage());
        }
    }

    //actualiza el desafio mensual en su campo desafio_visitas
    //recive id_mensual
    public function update_desafios(Request $request, $id) //permission  'editar desafios - desafios mensuales',
    {
        //dd($request, $id);
        try {
            DB::beginTransaction();

            // Validación simple
            $request->validate([
                'desafio_visitas' => 'required|integer|min:0',
            ]);

            // Buscar el registro mensual
            $mensual = Mensual::findOrFail($id);

            // Actualizar el campo
            $mensual->update([
                'desafio_visitas' => $request->desafio_visitas,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Desafío mensual actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar el desafío mensual: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //obtenemos todos los distritos con desafios mensual asignado 
    //para asignar desafio y editarlos
    public function index_mes($mes, $anio) //pERMISION 'ver los blancos de 1 mes-desafios mensuales'
    {

        $resultados = Mensual::query()
            ->join('desafios as xd', 'mensuales.id_desafio', '=', 'xd.id_desafio')
            
            ->join('distritos as xdd', 'xd.id_distrito', '=', 'xdd.id_distrito')
            ->leftjoin('personas as xp', 'xdd.id_pastor', '=', 'xp.id_persona')
            ->where('mensuales.anio', $anio)
            ->where('mensuales.mes', $mes)
            ->select(
                'xdd.nombre', 
                'xd.id_desafio', 
                'mensuales.*',
                'xp.nombre as nombre_p',
                'xp.ape_paterno',
                'xp.ape_materno'
            )
            ->get();
        return view('desafio_mensuales.asignar_desafios_a_mes', compact('resultados', 'mes', 'anio'));
    }

    //obtenemos todos los distritos con desafios mensual asignado 
    //para asignar desafio y editarlos masivamente
    public function index_mes_masivo($mes, $anio) //'editar desafios masivo-desafios mensuales',
    {

        $resultados = Mensual::query()
            ->join('desafios as xd', 'mensuales.id_desafio', '=', 'xd.id_desafio')
            
            ->join('distritos as xdd', 'xd.id_distrito', '=', 'xdd.id_distrito')
            ->leftjoin('personas as xp', 'xdd.id_pastor', '=', 'xp.id_persona')
            ->where('mensuales.anio', $anio)
            ->where('mensuales.mes', $mes)
            ->select(
                'xdd.nombre', 
                'xd.id_desafio', 
                'mensuales.*',
                'xp.nombre as nombre_p',
                'xp.ape_paterno',
                'xp.ape_materno'
            )
            ->get();
        return view('desafio_mensuales.asignar_desafios_a_mes_masivo', compact('resultados', 'mes', 'anio'));
    }
    //actualiza el desafio mensual en su campo desafio_visitas masivamente
    public function updateMasivo(Request $request) // permision 'editar desafios masivo-desafios mensuales',
    {
        try {
            DB::beginTransaction();

            // Verificar que llegan datos
            if (!$request->has('registros')) {
                return back()->with('error', 'No se enviaron datos para actualizar.');
            }

            foreach ($request->registros as $id => $data) {

                // Validación individual
                if (!isset($data['desafio_visitas'])) continue;

                Mensual::where('id_mensual', $id)->update([
                    'desafio_visitas' => (int)$data['desafio_visitas']
                ]);
            }

            DB::commit();
            return back()->with('success', 'Asignación masiva actualizada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error en actualización masiva: '.$e->getMessage());
        }
    }
    //va mostrar todos graficos x mes
    public function dashboard_mes_x_distrito($mes, $anio) //Permision 'graficos x mes MBOS-desafios mensuales',
    {
        // Obtener los resultados
        $resultados = Mensual::query()
            ->join('desafios as xd', 'mensuales.id_desafio', '=', 'xd.id_desafio')
            ->join('distritos as xdd', 'xd.id_distrito', '=', 'xdd.id_distrito')
            ->leftJoin('personas as xp', 'xdd.id_pastor', '=', 'xp.id_persona')
            ->where('mensuales.anio', $anio)
            ->where('mensuales.mes', $mes)
            ->select(
                'xdd.id_distrito',
                'xdd.nombre as nombre_distrito',
                'mensuales.desafio_visitas',
                'mensuales.visitas_alcanzadas',
                'xp.nombre as nombre_p',
                'xp.ape_paterno',
                'xp.ape_materno'
            )
            ->get();


        // ---- CÁLCULOS PARA LAS TARJETAS ----
        $totalDistritos = $resultados->count();
        $completaron = $resultados->filter(fn ($r) => 
                (int)$r->visitas_alcanzadas >= (int)$r->desafio_visitas
            )->count();
        
        $faltan = $totalDistritos - $completaron;
        //dd($completaron, $totalDistritos, $faltan);


        // ---- DATOS PARA LA GRÁFICA DINÁMICA ----
        $graficos = $resultados->map(function ($d) {

            $diferencia = $d->desafio_visitas - $d->visitas_alcanzadas;

            return [
                'id_distrito' => $d->id_distrito,
                'desafio'     => $d->desafio_visitas,
                'alcanzado'   => $d->visitas_alcanzadas,
                'diferencia'  => $diferencia,
            ];
        });


        return view(
            'desafio_mensuales.dashboard_mensual_visitas',
            compact(
                'resultados',
                'mes',
                'anio',
                'totalDistritos',
                'completaron',
                'faltan',
                'graficos'
            )
        );
    }
    //para grafico de todos los meses del año las visitas por mes
    public function resumenMensualGeneral() //permission 'graficos todos los meses MBOS-desafios mensuales',
    {
        $anio = now()->year;

        // Obtener todos los desafíos del año con su distrito y pastor
        $desafios = \DB::table('desafios')
            ->join('distritos', 'distritos.id_distrito', '=', 'desafios.id_distrito')
            ->join('personas', 'personas.id_persona', '=', 'distritos.id_pastor')
            ->select(
                'desafios.id_desafio',
                'desafios.anio',
                'distritos.nombre as distrito',
                'personas.nombre as pastor'
            )
            ->where('desafios.anio', $anio)
            ->get();

        // Obtener todos los mensuales para esos desafíos
        $mensuales = Mensual::whereIn('id_desafio', $desafios->pluck('id_desafio'))
            ->orderBy('mes')
            ->get();

        // Nombre de meses
        $nombresMeses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
            4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
            10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        // Armar estructura final
        $resultado = [];

        foreach ($desafios as $d) {
            $filtrados = $mensuales->where('id_desafio', $d->id_desafio)
                                ->filter(fn($m) => $m->mes <= now()->month);

            $meses = [];
            $desafiosArr = [];
            $alcanzadosArr = [];
            $diferenciasArr = [];

            foreach ($filtrados as $m) {
                $meses[] = $nombresMeses[$m->mes];
                $desafiosArr[] = (int) $m->desafio_visitas;
                $alcanzadosArr[] = (int) $m->visitas_alcanzadas;
                $diferenciasArr[] = (int) ($m->desafio_visitas - $m->visitas_alcanzadas);
            }

            $resultado[] = [
                "id_desafio" => $d->id_desafio,
                "distrito" => $d->distrito,
                "pastor" => $d->pastor,
                "meses" => $meses,
                "desafios" => $desafiosArr,
                "alcanzados" => $alcanzadosArr,
                "diferencias" => $diferenciasArr,
            ];

        }

        return view("desafio_mensuales.dashboard_mbos_mensual_visitas", compact("resultado", "anio"));
    }
    

    
}
