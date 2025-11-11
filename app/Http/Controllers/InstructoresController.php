<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstructorBiblico;
use App\Models\Iglesia;
use App\Models\Desafio;
use App\Models\AnualIglesia;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Requests\InstructorRequest;
use App\Http\Requests\UpdateInstructorRequest;
class InstructoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anioActual = now()->year; //muestro los estudiantes del año actual
        $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
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

    public function index_desafios_inst()
    {
        $anioActual = now()->year;
        $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
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
        /*$graficos = $desafios->map(function ($d) {
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
        });*/
        $graficos = $desafios->map(function ($d) { //EN PORCENTAJES
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
        });

        return view('instructores.dashboard', compact('desafios', 'anio', 'graficos'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
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
    public function store(InstructorRequest $request)
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
    public function edit(string $id)
    {
        $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
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
    public function update(UpdateInstructorRequest $request, string $id)
    {
         try {
            DB::beginTransaction();  
            $anioActual = now()->year;
            // Obtener el distrito
            $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
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
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $anioActual = now()->year;
            // Obtener el distrito
            $persona = Auth::user(); 

        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
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

    
}
