<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\EstudianteBiblico; 
use App\Models\Iglesia; 
use App\Models\Desafio; 
use App\Models\Mensual; 
use App\Models\AnualIglesia; 
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Requests\EstudianteRequest;
use App\Http\Requests\UpdateEstudianteRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Distrito; 
class EstudiantesController extends Controller
{
    /**
    *     'ver-estudiantes',
    *     'ver avance-estudiantes',
    *     'crear-estudiantes',
    *     'editar-estudiantes',
    *     'eliminar-estudiantes',

     * Display a listing of the resource. //TODO HECHO YA PARA LE DISTRITO 11
     */
    function __construct()
    {
        $this->middleware('permission:ver-estudiantes|ver avance-estudiantes|crear-estudiantes|editar-estudiantes|eliminar-estudiantes', ['only'=>['index']]);
        $this->middleware('permission:ver avance-estudiantes', ['only'=>['index_desafios_inst']]);
        $this->middleware('permission:crear-estudiantes', ['only'=>['create', 'store']]);
        $this->middleware('permission:editar-estudiantes', ['only'=>['edit', 'update']]);
        $this->middleware('permission:eliminar-estudiantes', ['only'=>['destroy']]);
    }


    public function index() // permision ver estudaintes
    {
        // 1️⃣ Definir el año actual y el distrito
        $anioActual = now()->year;
        $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado. Contacta al administrador!.');
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
        // 2️⃣ Consultar estudiantes bíblicos con los filtros

        $estudiantes = EstudianteBiblico::whereNull('id_iglesia')
            ->whereYear('fecha_registro', $anio)
            ->get();
        $estudiantes = EstudianteBiblico::leftjoin('iglesias as xi', 'estudiante_biblicos.id_iglesia', '=', 'xi.id_iglesia')
            ->select(
                'estudiante_biblicos.*',
                'xi.nombre as nombre_iglesia'
            )
            ->whereYear('estudiante_biblicos.fecha_registro', $anio) // mismo año
            ->where('xi.estado', true) // iglesias activas
            ->where('xi.distrito_id', $id_distrito) // solo distrito 11
            ->get();

        // 3️⃣ Enviar datos a la vista
        return view('estudiantes.index', compact('estudiantes', 'anio'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() // permision crear estudaintes
    {
        // Definimos el distrito actual
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

        // Retornamos la vista con los datos
        return view('estudiantes.create', compact('iglesias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EstudianteRequest $request) // permision crear estudaintes
    {
        try {
            DB::beginTransaction();

            // Obtenemos la fecha actual, por ejemplo con Carbon:
            $fechaHoy = now();  // puedes usar también now() si lo tienes importado
            // Creamos el registro incluyendo la fecha_registro
            $estudiante = EstudianteBiblico::create(array_merge(
                $request->validated(),
                ['fecha_registro' => $fechaHoy]
            ));
            //dd($estudiante);
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
                ->where('id_iglesia', $estudiante->id_iglesia)
                ->first();

            if (!$anual_iglesias) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No se encontró el registro anual de la iglesia.');
            }

            $anual_iglesias->increment('estudiantes_alcanzados');
            DB::commit();
            return redirect()->route('estudiantes.index')->with('success', 'Estudiante creado correctamente.');

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el estudiante biblico: ' . $e->getMessage()], 500);
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
    public function edit(string $id) // permision editar estudaintes
    {
        $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;

        $estudiante = EstudianteBiblico::find($id);
        $iglesias = Iglesia::where('estado', true)
            ->where('distrito_id', $id_distrito)
            ->orderBy('nombre') // opcional: para que salgan ordenadas alfabéticamente
            ->get();
        return view('estudiantes.edit', compact('estudiante','iglesias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstudianteRequest $request, string $id) // permision editar estudaintes
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
            $estudiante = EstudianteBiblico::findOrFail($id);
            $anual_iglesias = AnualIglesia::where('id_desafio', $desafio->id_desafio)
                ->where('id_iglesia', $estudiante->id_iglesia)
                ->first();
            if (!$anual_iglesias) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No se encontró el registro anual de la iglesia.');
            }
            $anual_iglesias->decrement('estudiantes_alcanzados');

            $estudiante->update($request->validated());

            $anual_iglesias = AnualIglesia::where('id_desafio', $desafio->id_desafio)
                ->where('id_iglesia', $estudiante->id_iglesia)
                ->first();
            if (!$anual_iglesias) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No se encontró el registro anual de la iglesia.');
            }
            $anual_iglesias->increment('estudiantes_alcanzados');
            DB::commit();
            return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) // permision eliminar estudaintes

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
            $estudiante = EstudianteBiblico::find($id);

            if (!$estudiante) {
                return redirect()->route('estudiantes.index')
                    ->with('error', 'Estudiante no encontrado');
            }

            $anual_iglesias = AnualIglesia::where('id_desafio', $desafio->id_desafio)
                ->where('id_iglesia', $estudiante->id_iglesia)
                ->first();

            if (!$anual_iglesias) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No se encontró el registro anual de la iglesia.');
            }

            $anual_iglesias->decrement('estudiantes_alcanzados');

            $estudiante->delete();

            DB::commit();
            return redirect()->route('estudiantes.index')->with('success', 'Estudiante Eliminado Correctamente');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('estudiantes.index')
                ->with('error', 'Error al Eliminar al Estudiante: ' . $e->getMessage());
        }
    }

    public function index_desafios_inst() //PERMISION 'ver avance-estudiantes',
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

}
