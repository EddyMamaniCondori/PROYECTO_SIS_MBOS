<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstructorBiblico;
use App\Models\EstudianteBiblico;
use App\Models\Iglesia;
use App\Models\Desafio;
use App\Models\Distrito;
use App\Models\Persona;
use App\Models\AnualIglesia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Http\Requests\InstructorRequest;
use App\Http\Requests\UpdateInstructorRequest;
class InstructoresController extends Controller
{
    /**
     * Display a listing of the resource.
     * 'ver-instructores',
     *       'ver avance-instructores',
     *       'crear-instructores',
     *       'editar-instructores',
     *       'eliminar-instructores',
     */
    function __construct()
    {
        // index(): PERMISION 'ver-instructores'
        // Nota: Agrupamos permisos de CRUD para que la vista index sea accesible.
        $this->middleware('permission:ver-instructores|crear-instructores|editar-instructores|eliminar-instructores|ver avance-instructores', ['only' => ['index']]);

        // index_desafios_inst(): PERMISION 'ver avance-instructores'
        $this->middleware('permission:ver avance-instructores', ['only' => ['index_desafios_inst']]);

        // create() y store(): PERMISION 'crear-instructores'
        $this->middleware('permission:crear-instructores', ['only' => ['create', 'store']]);

        // edit() y update(): PERMISION 'editar-instructores'
        $this->middleware('permission:editar-instructores', ['only' => ['edit', 'update']]);

        // destroy(): PERMISION 'eliminar-instructores'
        $this->middleware('permission:eliminar-instructores', ['only' => ['destroy']]);

        $this->middleware('permission:ver mbos-desafios anual Est Inst|editar mbos-desafios anual Est Inst', ['only' => ['index_mbos', 'index_mbos_dashboard']]);
        $this->middleware('permission:editar mbos-desafios anual Est Inst', ['only' => ['index_mbos_distrital','update_est_inst','index_mbos_distrital_masivo','updateMasivo']]);
        $this->middleware('permission:ver estudiantes de distritos-desafios anual Est Inst', ['only' => ['index_estudiantes_mbos']]);
        $this->middleware('permission:ver instructores de distritos-desafios anual Est Inst', ['only' => ['index_instructores_mbos']]);
        $this->middleware('permission:ver detalle de iglesias de distritos-desafios anual Est Inst', ['only' => ['dashboar_inst_estu_mbos']]);
    }
    public function index() //PERMISION 'ver-instructores',
    {
        $anioActual = now()->year; //muestro los estudiantes del año actual
        $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
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

        $instructores = InstructorBiblico::join('iglesias as xi', 'instructor_biblicos.id_iglesia', '=', 'xi.id_iglesia')
            ->select(
                'instructor_biblicos.*',
                'xi.nombre as nombre_iglesia' // alias para evitar conflicto con nombre del instructor
            )
            ->whereYear('instructor_biblicos.fecha_registro', $anio) // mismo año
            ->where('xi.estado', true) // iglesias activas
            ->where('xi.distrito_id', $id_distrito) // solo distrito 11
            ->get();

        return view('instructores.index', compact('instructores','anio'));

    }
    //Graficas de instructoresy estudiantes por iglesia
    public function index_desafios_inst() //PERMISION 'ver avance-instructores',
    {
        $anioActual = now()->year;
        $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;

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

        $desafios = DB::table('desafios as xd')
            ->join('anual_iglesias as xai', 'xd.id_desafio', '=', 'xai.id_desafio')
            ->join('iglesias as xi', 'xai.id_iglesia', '=', 'xi.id_iglesia')
            ->where('xd.id_distrito', $id_distrito)
            ->where('xd.anio', $anio)
            ->select(
                'xai.id_iglesia',
                'xi.codigo',
                'xi.nombre as nombre_iglesia',
                'xai.desafio_estudiantes',
                'xai.estudiantes_alcanzados',
                'xai.desafio_instructores',
                'xai.instructores_alcanzados'
            )
            ->get();

        // Procesar los datos para el gráfico (ya listos) NUMEROS
        $graficos = $desafios->map(function ($d) {
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
        /*$graficos = $desafios->map(function ($d) { //EN PORCENTAJES
            // Evitar división entre 0
            $desafioEst = max((int)$d->desafio_estudiantes, 1);
            $desafioInst = max((int)$d->desafio_instructores, 1);

            // Cálculo de porcentajes (base 100%)
            $porcEstAlcanzado = ((int)$d->estudiantes_alcanzados / $desafioEst) * 100;
            $porcInstAlcanzado = ((int)$d->instructores_alcanzados / $desafioInst) * 100;

            return [
                'id_iglesia' => $d->id_iglesia,
                'estudiantes' => [
                    'desafio' => 100, // Siempre 100%
                    'alcanzado' => round($porcEstAlcanzado, 2),
                    'diferencia' => round(100 - $porcEstAlcanzado, 2),
                ],
                'instructores' => [
                    'desafio' => 100, // Siempre 100%
                    'alcanzado' => round($porcInstAlcanzado, 2),
                    'diferencia' => round(100 - $porcInstAlcanzado, 2),
                ]
            ];
        });*/

        return view('instructores.dashboard', compact('desafios', 'anio', 'graficos'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create() //PERMISION 'crear-instructores',
    {
        $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;

        // Obtenemos solo las iglesias activas del distrito 11
        $iglesias = Iglesia::where('estado', true)
            ->where('distrito_id', $id_distrito)
            ->orderBy('nombre') // opcional: para que salgan ordenadas alfabéticamente
            ->get();

        return view('instructores.create', compact('iglesias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InstructorRequest $request) //PERMISION 'crear-instructores',
    {
        try {
            DB::beginTransaction();

            $fechaHoy = now(); // puedes usar también now() si lo tienes importado
            $instructor = InstructorBiblico::create(array_merge(
                $request->validated(),
                ['fecha_registro' => $fechaHoy]
            )); // se crea el registro 

            $anioActual = now()->year;
            // Obtener el distrito
            $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;

            $desafio = Desafio::where('id_distrito', $id_distrito)
                ->where('anio', $anioActual)
                ->first();
            
            if (!$desafio) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No se encontró el desafío anual para el distrito.');
            }

            $anual_iglesias = AnualIglesia::where('id_desafio', $desafio->id_desafio)
                ->where('id_iglesia', $instructor->id_iglesia)
                ->first();

            if (!$anual_iglesias) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No se encontró el registro anual de la iglesia.');
            }

            $anual_iglesias->increment('instructores_alcanzados');
            
            DB::commit();
            return redirect()->route('instructores.index')->with('success', 'Instructor registrado correctamente');
        
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el instructor biblico: ' . $e->getMessage()], 500);
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
    public function edit(string $id) //PERMISION 'editar-instructores',
    {
        $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;
        $instructor = InstructorBiblico::find($id);
        $iglesias = Iglesia::where('estado', true)
            ->where('distrito_id', $id_distrito)
            ->orderBy('nombre') // opcional: para que salgan ordenadas alfabéticamente
            ->get();
        return view('instructores.edit', compact('instructor','iglesias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstructorRequest $request, string $id) //PERMISION 'editar-instructores',
    {
         try {
            DB::beginTransaction();  
            $anioActual = now()->year;
            // Obtener el distrito
            $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;
            $desafio = Desafio::where('id_distrito', $id_distrito)
                ->where('anio', $anioActual)
                ->first();
            if (!$desafio) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No se encontró el desafío anual para el distrito.');
            }

            $instructor = InstructorBiblico::find($id);
            //antes de actualizar decrementar la iglesia
            $anual_iglesias = AnualIglesia::where('id_desafio', $desafio->id_desafio)
                ->where('id_iglesia', $instructor->id_iglesia)
                ->first();
            if (!$anual_iglesias) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No se encontró el registro anual de la iglesia.');
            }
            $anual_iglesias->decrement('instructores_alcanzados');
            //actualizamos el registro

            $instructor->update($request->validated());
            $anual_iglesias = AnualIglesia::where('id_desafio', $desafio->id_desafio)
                ->where('id_iglesia', $instructor->id_iglesia)
                ->first();
            if (!$anual_iglesias) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No se encontró el registro anual de la iglesia.');
            }
            $anual_iglesias->increment('instructores_alcanzados');
            DB::commit();
            return redirect()->route('instructores.index')->with('success', 'Instructor actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) //PERMISION 'eliminar-instructores',
    {
        try {
            DB::beginTransaction();
            $anioActual = now()->year;
            // Obtener el distrito
            $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;

            $desafio = Desafio::where('id_distrito', $id_distrito)
                ->where('anio', $anioActual)
                ->first();
            if (!$desafio) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No se encontró el desafío anual para el distrito.');
            }
            // Buscar estudiante, si no existe lanzar excepción o manejar error
            $instructor = InstructorBiblico::find($id);
            if (!$instructor) {
                return redirect()->route('instructores.index')
                    ->with('error', 'Instructor no encontrado');
            }

            $anual_iglesias = AnualIglesia::where('id_desafio', $desafio->id_desafio)
                ->where('id_iglesia', $instructor->id_iglesia)
                ->first();

            if (!$anual_iglesias) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No se encontró el registro anual de la iglesia.');
            }

            $anual_iglesias->decrement('instructores_alcanzados');
            $instructor->delete();
            DB::commit();
            return redirect()->route('instructores.index')->with('success', 'Instructor Eliminado Correctamente');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('instructores.index')
                ->with('error', 'Error al Eliminar: ' . $e->getMessage());
        }
    }

    /**_____________________________________________________________________ */
    /**                                  ADMINISTRATIVO MBOS */
    /**_____________________________________________________________________ */
    // muestra todos los distritos del año actual y sus desafios en blancos y alcanzados de estudiantes y 
    public function index_mbos() // permission  'ver mbos -desafios anual Est Inst',
    {
            $anioActual = now()->year; //sacamos el año actual
            // Obtener el año configurado en la tabla distritos
            $anioDistritos = DB::table('distritos')
                ->where('estado', true)
                ->value('año'); // Obtiene el primer año encontrado
            // Si no hay distritos activos
            if (!$anioDistritos) {
                return view('desafio.index_bautizo', [
                    'desafios' => collect([]),
                    'anioActual' => $anioActual,
                    'mensaje' => 'No hay distritos activos en el sistema.'
                ]);
            }
            if ($anioDistritos < $anioActual) {
                // Mostrar desafíos del año de los distritos (año anterior)
                $resultados = Desafio::query()
                ->join('anual_iglesias as xai', 'desafios.id_desafio', '=', 'xai.id_desafio')
                ->leftjoin('distritos as xdd', 'desafios.id_distrito','=', 'xdd.id_distrito')
                ->where('desafios.anio', $anioActual)
                ->select(
                    'desafios.id_distrito',
                    'xdd.nombre',
                    DB::raw('SUM(xai.desafio_instructores) as total_desafio_instructores'),
                    DB::raw('SUM(xai.desafio_estudiantes) as total_desafios_estudiantes'),
                    DB::raw('SUM(xai.estudiantes_alcanzados) as total_estudiantes_alcanzados'),
                    DB::raw('SUM(xai.instructores_alcanzados) as total_instructores_alcanzados')
                )
                ->groupBy('desafios.id_distrito', 'xdd.nombre')
                ->get();

                $anioActual = $anioDistritos;
                $anioMen = $anioActual+1; 
                return view('instructores.index_mbos', compact('resultados', 'anioActual'))
                        ->with('info', "Desafíos del {$anioMen} pendientes. Habilita el nuevo período en ADMINISTRAR DESAFÍOS.");
            }

            $resultados = Desafio::query()
                ->join('anual_iglesias as xai', 'desafios.id_desafio', '=', 'xai.id_desafio')
                ->leftjoin('distritos as xdd', 'desafios.id_distrito','=', 'xdd.id_distrito')
                ->where('desafios.anio', $anioActual)
                ->select(
                    'desafios.id_desafio',
                    'desafios.id_distrito',
                    'xdd.nombre',
                    DB::raw('SUM(xai.desafio_instructores) as total_desafio_instructores'),
                    DB::raw('SUM(xai.desafio_estudiantes) as total_desafios_estudiantes'),
                    DB::raw('SUM(xai.estudiantes_alcanzados) as total_estudiantes_alcanzados'),
                    DB::raw('SUM(xai.instructores_alcanzados) as total_instructores_alcanzados')
                )
                ->groupBy('desafios.id_desafio', 'desafios.id_distrito', 'xdd.nombre')
                ->get();
        return view('instructores.index_mbos', compact('resultados','anioActual'));
    }
    //muestra los desafios de estudaintes y instructores ya de 1 distrito y sus iglesias //para actualizarlo 1 x1
    //recivimos el id_desafio
    public function index_mbos_distrital($id){ //permission 'editar mbos - desafios anual Est Inst',

        $desafio = Desafio::findOrFail($id);
        if (!$desafio) {
            return redirect()->route('instructores.mbos.distrital')->with('error', 'No se encontro el Desafio.');
        }
        
        //$desafio_iglesias = AnualIglesia::where('id_desafio', $desafio->id_desafio)->get();
        $desafio_iglesias = AnualIglesia::query()
            ->join('iglesias as xi', 'anual_iglesias.id_iglesia', '=', 'xi.id_iglesia')
            ->where('anual_iglesias.id_desafio', $desafio->id_desafio)
            ->select('anual_iglesias.*', 'xi.*') 
            ->get();
            
        if (!$desafio_iglesias) {
            return redirect()->route('instructores.mbos.distrital')->with('error', 'No se encontroron los desafios anuales de las iglesias, Comunicate con el Administrador');
        }
        $anioActual = $desafio->anio;
        return view('instructores.edit_est_inst_distrito', compact('anioActual','desafio_iglesias',));
    }

    public function update_est_inst(Request $request, string $id) // permission  'editar mbos - desafios anual Est Inst',
    {
        try {
            // Validar los datos primero
            $validated = $request->validate([
                'desafio_estudiantes' => 'required|integer|min:0',
                'desafio_instructores' => 'required|integer|min:0',
            ]);

            DB::beginTransaction();

            $desafio_anual_iglesia = AnualIglesia::find($id);
            
            $desafio_anual_iglesia->update($validated);
            
            $id_desafio = $desafio_anual_iglesia->id_desafio;
            
            DB::commit();

            return redirect()->route('desafios.asignacion.distrital', $id_desafio)
                ->with('success', 'Desafío Anual de Iglesia actualizado correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el Desafío: ' . $e->getMessage());
        }
    }
    //muestra los desafios de estudaintes y instructores ya de 1 distrito y sus iglesias //para actualizarlo 1 x1
    //recivimos el id_desafio
    public function index_mbos_distrital_masivo($id){ //permission 'editar mbos - desafios anual Est Inst',

        $desafio = Desafio::findOrFail($id);
        if (!$desafio) {
            return redirect()->route('instructores.mbos.distrital')->with('error', 'No se encontro el Desafio.');
        }
        
        //$desafio_iglesias = AnualIglesia::where('id_desafio', $desafio->id_desafio)->get();
        $desafio_iglesias = AnualIglesia::query()
            ->join('iglesias as xi', 'anual_iglesias.id_iglesia', '=', 'xi.id_iglesia')
            ->where('anual_iglesias.id_desafio', $desafio->id_desafio)
            ->select('anual_iglesias.*', 'xi.*') 
            ->get();
            
        if (!$desafio_iglesias) {
            return redirect()->route('instructores.mbos.distrital')->with('error', 'No se encontroron los desafios anuales de las iglesias, Comunicate con el Administrador');
        }
        $anioActual = $desafio->anio;
        return view('instructores.edit_est_inst_distrito_masivo', compact('anioActual','desafio_iglesias'));
    }

    public function updateMasivo(Request $request) //'editar mbos - desafios anual Est Inst',
    {
        DB::beginTransaction();
        try {

            foreach ($request->desafios as $id => $data) {
                    AnualIglesia::where('id_desafio_iglesia', $id)->update([
                    'desafio_instructores' => $data['desafio_instructores'],
                    'desafio_estudiantes' => $data['desafio_estudiantes'],
                ]);
            }

            DB::commit();
            return redirect()->route('instructores.mbos.distrital')
                ->with('success', 'Desafío Anual de Iglesia actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error en actualización masiva: '.$e->getMessage());
        }
    }
     // muestra todos los distritos del año actual y sus desafios en blancos y alcanzados de estudiantes y 
    //es para ver ya las graficas y los estudiantes, etc.
    
    public function index_mbos_dashboard() // permission  'ver mbos -desafios anual Est Inst',
    {
            $anioActual = now()->year; //sacamos el año actual
            // Obtener el año configurado en la tabla distritos
            $anioDistritos = DB::table('distritos')
                ->where('estado', true)
                ->value('año'); // Obtiene el primer año encontrado
            // Si no hay distritos activos
            if (!$anioDistritos) {
                return view('desafio.index_bautizo', [
                    'desafios' => collect([]),
                    'anioActual' => $anioActual,
                    'mensaje' => 'No hay distritos activos en el sistema.'
                ]);
            }
            if ($anioDistritos < $anioActual) {
                // Mostrar desafíos del año de los distritos (año anterior)
                $resultados = Desafio::query()
                ->join('anual_iglesias as xai', 'desafios.id_desafio', '=', 'xai.id_desafio')
                ->leftjoin('distritos as xdd', 'desafios.id_distrito','=', 'xdd.id_distrito')
                ->where('desafios.anio', $anioActual)
                ->select(
                    'desafios.id_distrito',
                    'xdd.nombre',
                    DB::raw('SUM(xai.desafio_instructores) as total_desafio_instructores'),
                    DB::raw('SUM(xai.desafio_estudiantes) as total_desafios_estudiantes'),
                    DB::raw('SUM(xai.estudiantes_alcanzados) as total_estudiantes_alcanzados'),
                    DB::raw('SUM(xai.instructores_alcanzados) as total_instructores_alcanzados')
                )
                ->groupBy('desafios.id_distrito', 'xdd.nombre')
                ->get();

                $anioActual = $anioDistritos;
                $anioMen = $anioActual+1; 
                return view('instructores.index_mbos', compact('resultados', 'anioActual'))
                        ->with('info', "Desafíos del {$anioMen} pendientes. Habilita el nuevo período en ADMINISTRAR DESAFÍOS.");
            }

            $resultados = Desafio::query()
                ->join('anual_iglesias as xai', 'desafios.id_desafio', '=', 'xai.id_desafio')
                ->leftjoin('distritos as xdd', 'desafios.id_distrito','=', 'xdd.id_distrito')
                ->where('desafios.anio', $anioActual)
                ->select(
                    'desafios.id_desafio',
                    'desafios.id_distrito',
                    'xdd.nombre',
                    DB::raw('SUM(xai.desafio_instructores) as total_desafio_instructores'),
                    DB::raw('SUM(xai.desafio_estudiantes) as total_desafios_estudiantes'),
                    DB::raw('SUM(xai.estudiantes_alcanzados) as total_estudiantes_alcanzados'),
                    DB::raw('SUM(xai.instructores_alcanzados) as total_instructores_alcanzados')
                )
                ->groupBy('desafios.id_desafio', 'desafios.id_distrito', 'xdd.nombre')
                ->get();

            $graficos = $resultados->map(function ($d) {
                return [
                    'id_distrito' => $d->id_distrito,
                    'nombre' => $d->nombre,
                    'estudiantes' => [
                        'desafio' => (int)$d->total_desafios_estudiantes,
                        'alcanzado' => (int)$d->total_estudiantes_alcanzados,
                        'diferencia' => (int)$d->total_desafios_estudiantes - (int)$d->total_estudiantes_alcanzados,
                    ],
                    'instructores' => [
                        'desafio' => (int)$d->total_desafio_instructores,
                        'alcanzado' => (int)$d->total_instructores_alcanzados,
                        'diferencia' => (int)$d->total_desafio_instructores - (int)$d->total_instructores_alcanzados,
                    ]
                ];
            });

            $totales = DB::table('anual_iglesias AS ai') //la suma total de desafios de todos los distritos MBOS
                ->join('desafios AS d', 'd.id_desafio', '=', 'ai.id_desafio')
                ->where('d.anio', $anioActual)
                ->selectRaw('
                    SUM(ai.desafio_instructores) AS total_desafio_instructores,
                    SUM(ai.desafio_estudiantes) AS total_desafio_estudiantes,
                    SUM(ai.instructores_alcanzados) AS total_instructores_alcanzados,
                    SUM(ai.estudiantes_alcanzados) AS total_estudiantes_alcanzados
                ')
                ->first();

            $dataEstudiantes = [
            'desafio'     => $totales->total_desafio_estudiantes,
            'alcanzado'   => $totales->total_estudiantes_alcanzados,
            'diferencia'  => $totales->total_desafio_estudiantes - $totales->total_estudiantes_alcanzados,
            ];

            // INSTRUCTORES
            $dataInstructores = [
                'desafio'     => $totales->total_desafio_instructores,
                'alcanzado'   => $totales->total_instructores_alcanzados,
                'diferencia'  => $totales->total_desafio_instructores - $totales->total_instructores_alcanzados,
            ];
            $porcentajeEstudiantes = 0;
            $porcentajeInstructores = 0;

            if ($totales->total_desafio_estudiantes > 0) {
                $porcentajeEstudiantes = round(
                    ($totales->total_estudiantes_alcanzados / $totales->total_desafio_estudiantes) * 100,
                    2
                );
            }

            if ($totales->total_desafio_instructores > 0) {
                $porcentajeInstructores = round(
                    ($totales->total_instructores_alcanzados / $totales->total_desafio_instructores) * 100,
                    2
                );
            }
        return view('instructores.index_mbos_dashboard', compact('resultados','anioActual', 'graficos',
                    'dataEstudiantes','dataInstructores','porcentajeEstudiantes','porcentajeInstructores'));
    }
    //Mostrar todos los instructores de 1 distrito en especifico
    //nos llega el id distrito y anio
    public function index_instructores_mbos($id, $anio) //PERMISION 'ver instructores de distritos-desafios anual Est Inst',
    {
        $distrito = Distrito::findOrFail($id);
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No se encontro el distrito');
        }
        $id_distrito = $distrito->id_distrito;

        $persona = Persona::findOrFail($distrito->id_pastor); //buscamos al pastor

        $instructores = InstructorBiblico::join('iglesias as xi', 'instructor_biblicos.id_iglesia', '=', 'xi.id_iglesia')
            ->select(
                'instructor_biblicos.*',
                'xi.nombre as nombre_iglesia' // alias para evitar conflicto con nombre del instructor
            )
            ->whereYear('instructor_biblicos.fecha_registro', $anio) // mismo año
            ->where('xi.estado', true) // iglesias activas
            ->where('xi.distrito_id', $id_distrito) // solo distrito 11
            ->get();
        return view('instructores.instructores_x_distrito', compact('instructores','anio','distrito','persona'));
    }
    //Mostrar todos los estudiantes de 1 distrito en especifico
    //nos llega el id distrito y anio
    public function index_estudiantes_mbos($id, $anio) //PERMISION 'ver estudiantes de distritos-desafios anual Est Inst',
    {
        $distrito = Distrito::findOrFail($id);
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No se encontro el distrito');
        }
        $id_distrito = $distrito->id_distrito;

        $persona = Persona::findOrFail($distrito->id_pastor); //buscamos al pastor

        $estudiantes = EstudianteBiblico::join('iglesias as xi', 'estudiante_biblicos.id_iglesia', '=', 'xi.id_iglesia')
            ->select(
                'estudiante_biblicos.*',
                'xi.nombre as nombre_iglesia'
            )
            ->whereYear('estudiante_biblicos.fecha_registro', $anio) // mismo año
            ->where('xi.estado', true) // iglesias activas
            ->where('xi.distrito_id', $id_distrito) // solo distrito 11
            ->get();
        return view('instructores.estudiantes_x_distrito', compact('estudiantes','anio','distrito','persona'));
    }
    //Graficas de instructoresy estudiantes por iglesia
    //nos llega el id distrito y anio
    public function dashboar_inst_estu_mbos($id, $anio) //PERMISION  'ver detalle de iglesias de distritos-desafios anual Est Inst'
    {
        $distrito = Distrito::find($id);
        if (!$distrito) {
            return redirect()->route('instructores.mbos.distrital.ver')->with('error', 'No se encontro a nadie.');
        }
        $id_distrito = $distrito->id_distrito;
        $persona = Persona::find($distrito->id_pastor); 

        $desafios = DB::table('desafios as xd')
            ->join('anual_iglesias as xai', 'xd.id_desafio', '=', 'xai.id_desafio')
            ->join('iglesias as xi', 'xai.id_iglesia', '=', 'xi.id_iglesia')
            ->where('xd.id_distrito', $id_distrito)
            ->where('xd.anio', $anio)
            ->select(
                'xai.id_iglesia',
                'xi.codigo',
                'xi.nombre as nombre_iglesia',
                'xai.desafio_estudiantes',
                'xai.estudiantes_alcanzados',
                'xai.desafio_instructores',
                'xai.instructores_alcanzados'
            )
            ->get();

        // Procesar los datos para el gráfico (ya listos) NUMEROS
        $graficos = $desafios->map(function ($d) {
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
        return view('instructores.dashboard_x_distrito', compact('distrito','persona','desafios', 'anio', 'graficos'));
    }

}
