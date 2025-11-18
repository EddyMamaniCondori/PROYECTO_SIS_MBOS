<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iglesia;
use App\Models\Distrito;
use App\Models\LiderLocal;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;
use App\Http\Requests\IglesiaRequest;
use App\Http\Requests\UpdateIglesiaRequest;
use Illuminate\Support\Facades\Auth;
class IglesiaController extends Controller
{
    /**
     * // 🔹 Iglesias // SATISFECHO
      *      'ver-iglesias',
       *     'reactivar-iglesias',
       *     'ver eliminados-iglesias',
        *    'crear-iglesias',
       *     'editar-iglesias',
       *     'eliminar-iglesias',

        *    // (IGLESIAS) asignaciones aparte //SATISFECHO
        *    'asignaciones-iglesias',

     * Display a listing of the resource.
     */
    
    function __construct()
    {
        // index(): permision ver-iglesias
        $this->middleware('permission:ver-iglesias', ['only' => ['index']]);

        // index_eliminado(): permission ver eliminados-iglesias
        $this->middleware('permission:ver eliminados-iglesias', ['only' => ['index_eliminado']]);

        // create() y store(): permision crear-iglesias
        $this->middleware('permission:crear-iglesias', ['only' => ['create', 'store']]);

        // edit() y update(): permision editar-iglesias
        $this->middleware('permission:editar-iglesias|editar pastor-iglesias', ['only' => ['edit', 'update']]);

        // destroy(): permision eliminar-iglesias
        $this->middleware('permission:eliminar-iglesias', ['only' => ['destroy']]);

        // reactive(): permission reactivar-iglesias
        $this->middleware('permission:reactivar-iglesias', ['only' => ['reactive']]);

        // Asignaciones (index_asignaciones, asignarDistrito, cambiarDistrito, liberar): permission asignaciones-iglesias
        $this->middleware('permission:asignaciones-iglesias', ['only' => [
            'index_asignaciones', 
            'asignarDistrito', 
            'cambiarDistrito', 
            'liberar'
        ]]);
        $this->middleware('permission:ver pastor-iglesias|index_lideres_locales_pastores', ['only' => ['index_pastores']]);
        $this->middleware('permission:editar pastor iglesias-lideres locales', ['only' => ['updateMasivo','index_lideres_locales_pastores']]);
        $this->middleware('permission:ver x distritos-lideres locales', ['only' => ['resumenDistritos','detallePorDistrito']]);
        $this->middleware('ver x iglesias-lideres locales', ['only' => ['detallePorDistrito']]);
    }


    public function index()// permision ver-iglesias
    {
        // Traemos todas las iglesias junto con su distrito
        $iglesias = Iglesia::leftJoin('distritos', 'iglesias.distrito_id', '=', 'distritos.id_distrito')
        ->where('iglesias.estado', true)
        ->select('iglesias.*', 'distritos.nombre as nombre_distrito')
        ->get();


        return view('iglesias.index', compact('iglesias'));
    }

   
   
    /**
     * Show the form for creating a new resource.
     */
    public function create() // permision crear-iglesias 1
    {
        $distritos = Distrito::where('estado', true)->get();
        return view('iglesias.create', compact('distritos'));
    }

    public function store(IglesiaRequest $request) // permision crear-iglesias2
    {
        // Datos base comunes
        $data = [
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'estado' => true,
            'feligresia' => $request->feligresia,
            'feligresia_asistente' => $request->feligresia_asistente,
            'ciudad' => $request->ciudad,
            'zona' => $request->zona,
            'calle' => $request->calle,
            'nro' => $request->nro,
            'lugar' => $request->lugar,
            'tipo' => $request->tipo,
            'distrito_id' => $request->distrito_id ?: null, // asigna null si está vacío
        ];

        // Crear la iglesia
        $iglesia = Iglesia::create($data);

        // Si se asignó distrito, actualizar nro_iglesias
        if ($iglesia->distrito_id) {
            $distrito = Distrito::find($iglesia->distrito_id);
            if ($distrito) {
                $distrito->nro_iglesias = ($distrito->nro_iglesias ?? 0) + 1;
                $distrito->save();
            }
        }

        return redirect()->route('iglesias.index')
            ->with('success', 'Iglesia creada correctamente.');
    }


    /**
     * Muestra una iglesia específica.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) // permision editar-iglesias1
    {
        $iglesia = Iglesia::find($id);

        $distritos = Distrito::where('estado', true)->get();
        return view('iglesias.edit', compact('iglesia', 'distritos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIglesiaRequest $request, string $id) // permision editar-iglesias2
    {
        $data = [
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'feligresia' => $request->feligresia,
            'feligresia_asistente' => $request->feligresia_asistente,
            'ciudad' => $request->ciudad,
            'zona' => $request->zona,
            'calle' => $request->calle,
            'nro' => $request->nro,
            'lugar' => $request->lugar,
            'tipo' => $request->tipo,
            'distrito_id' => $request->distrito_id ?: null,
        ];

        $iglesia = Iglesia::findOrFail($id);

        // Si se seleccionó un distrito
        if (!is_null($data['distrito_id'])) {

            if (!is_null($iglesia->distrito_id)) {
                // Si los distritos son diferentes
                if ($iglesia->distrito_id != $data['distrito_id']) {
                    $distrito_A = Distrito::find($iglesia->distrito_id);
                    $distrito_B = Distrito::find($data['distrito_id']);

                    $distrito_A->nro_iglesias -= 1;
                    $distrito_A->save();

                    $distrito_B->nro_iglesias += 1;
                    $distrito_B->save();
                }
            } else {
                // La iglesia no tenía distrito antes
                $distrito_c = Distrito::find($data['distrito_id']);
                $distrito_c->nro_iglesias += 1;
                $distrito_c->save();
            }
        }

        // Actualizar la iglesia
        $iglesia->update($data);

        return redirect()->route('iglesias.index')
                        ->with('success', 'Iglesia actualizada correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) // permision eliminar-iglesias
    {
         try {
            DB::beginTransaction();
                $iglesia = Iglesia::find($id);

                if (!$iglesia) {
                    throw new \Exception("El distrito con ID {$id} no existe.");
                }
                // Solo si pertenecia a un distrito se va reducir el nuemro de iglesia
                
                if (!is_null($iglesia->distrito_id)) {
                    $distrito = Distrito::find($iglesia->distrito_id);

                    if ($distrito) {
                        $distrito->nro_iglesias = $distrito->nro_iglesias - 1;
                        $distrito->save();
                    }
                }
                $iglesia ->estado = false;
                $iglesia ->save(); // lanza excepción en caso de fallo
                
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al Eliminar la Iglesia: ' . $e->getMessage()], 500);
        }
        return redirect()->route('iglesias.index')->with('success','Iglesia Inhabilitado Correctamente');
    
    }



     /**
     * ___________________________________________________________________OTRAS FUNCIONES________________________________________
     */

    public function index_eliminado() //permission 'ver eliminados -iglesias',
    {
        // Traemos todas las iglesias junto con su distrito
        $iglesias = Iglesia::leftJoin('distritos', 'iglesias.distrito_id', '=', 'distritos.id_distrito')
        ->where('iglesias.estado', false)
        ->select('iglesias.*', 'distritos.nombre as nombre_distrito', 'distritos.id_distrito' )
        ->get();


        return view('iglesias.index_eliminados', compact('iglesias'));
    }
    public function reactive(string $id) //permission 'reactivar -iglesias',
    {
        try {
            DB::beginTransaction();
            // Buscar la iglesia
            $iglesia = Iglesia::find($id);
            if (!$iglesia) {
                throw new Exception("No se encontró la iglesia con ID $id");
            }
            // Reactivar la iglesia
            $iglesia->estado = true;
            $iglesia->save();
            // Si la iglesia pertenece a un distrito, actualizar su número de iglesias
            if (!is_null($iglesia->distrito_id)) {
                $distrito = Distrito::find($iglesia->distrito_id);

                if ($distrito) {
                    $distrito->nro_iglesias = $distrito->nro_iglesias + 1;
                    $distrito->save();
                }
            }
            DB::commit();
            return redirect()
                ->route('iglesias.index')
                ->with('success', 'Iglesia habilitada correctamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al reactivar la iglesia: ' . $e->getMessage()
            ], 500);
        }
    }
    /**________________________________________ASIGNACIONES _______________________________________________*/
     public function index_asignaciones() // permission 'asignaciones-iglesias',
    {
        $iglesiasConDistrito = DB::select("
                                    SELECT xi.*, xd.id_distrito, xd.nombre AS nombre_distrito
                                    FROM iglesias xi
                                    JOIN distritos xd ON xi.distrito_id = xd.id_distrito
                                ");

        $iglesiasSinDistrito = DB::select("
                                        SELECT xi.*
                                        FROM iglesias xi
                                        WHERE xi.distrito_id IS NULL
                                    ");
        $distritos = Distrito::where('estado', true)->get();
        return view('iglesias.asignaciones', compact('iglesiasConDistrito', 'iglesiasSinDistrito', 'distritos'));
    }

    public function asignarDistrito(Request $request, $id) //permisoin 'asignaciones-iglesias',
    {
        DB::beginTransaction();
        try {
            $iglesia = Iglesia::findOrFail($id);
            $distrito = Distrito::findOrFail($request->distrito_id);

            // Asignar distrito a la iglesia
            $iglesia->distrito_id = $request->distrito_id;
            $iglesia->save();

            // Actualizar número de iglesias del distrito
            $distrito->nro_iglesias += 1;
            $distrito->save();

            DB::commit();

            return redirect()->back()->with('success', 'Iglesia asignada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al asignar iglesia: ' . $e->getMessage());
        }
    }

    public function cambiarDistrito(Request $request, $id) //permisoin 'asignaciones-iglesias',
    {
        DB::beginTransaction();
        try {
            
            $iglesia = Iglesia::findOrFail($id);
            $distritoAnterior = Distrito::find($iglesia->distrito_id);
            $distritoNuevo = Distrito::findOrFail($request->distrito_id);

            // Validar que haya distrito anterior
            if ($distritoAnterior) {
                $distritoAnterior->nro_iglesias -= 1;
                if ($distritoAnterior->nro_iglesias < 0) {
                    $distritoAnterior->nro_iglesias = 0; // Seguridad por si acaso
                }
                $distritoAnterior->save();
            }

            // Asignar nueva relación
            $iglesia->distrito_id = $request->distrito_id;
            $iglesia->save();

            // Aumentar en el nuevo distrito
            $distritoNuevo->nro_iglesias += 1;
            $distritoNuevo->save();

            DB::commit();

            return redirect()->back()->with('success', 'Distrito cambiado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al cambiar distrito: ' . $e->getMessage());
        }
    }

    public function liberar($id) //permisoin 'asignaciones-iglesias',
    {
        DB::beginTransaction();
        try {
            $iglesia = Iglesia::findOrFail($id);

            if (!$iglesia->distrito_id) {
                return redirect()->back()->with('info', 'Esta iglesia no tiene distrito asignado.');
            }

            $distrito = Distrito::find($iglesia->distrito_id);

            if ($distrito) {
                $distrito->nro_iglesias = max(0, $distrito->nro_iglesias - 1);
                $distrito->save();
            }

            $iglesia->distrito_id = null; // Liberamos
            $iglesia->save();

            DB::commit();

            return redirect()->back()->with('success', 'Iglesia liberada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al liberar iglesia: ' . $e->getMessage());
        }
    }

    /**________________________________________VISTA Y EDITAR PASTORES_______________________________________________*/
    //muestra las iglesias que perteneicntes al distrito del pastor
    public function index_pastores()// permision 'ver pastor-iglesias'
    {
        $persona = Auth::user(); 
        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;// todos los estudiantes del distrito Bolivar  

        $iglesias = Iglesia::leftJoin('distritos', 'iglesias.distrito_id', '=', 'distritos.id_distrito')
        ->where('iglesias.estado', true)
        ->where('iglesias.distrito_id', $id_distrito)
        ->select('iglesias.*', 'distritos.nombre as nombre_distrito')
        ->get();
        return view('iglesias.index_pastores', compact('iglesias'));
    }
    //muestra la view dibdes se podra editar todos los lideres de 1 pastor especifico 
    public function index_lideres_locales_pastores() // permision editar pastor iglesias-lideres locales
    {
        $persona = Auth::user(); 
        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();

        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }

        $iglesias = Iglesia::where('estado', true)
            ->where('distrito_id', $distrito->id_distrito)
            ->get();

        // Garantizar que cada iglesia tenga un registro 1:1 en lideres_local
        foreach ($iglesias as $ig) {
            LiderLocal::firstOrCreate(
                ['id_iglesia' => $ig->id_iglesia],
                ['tipo' => $ig->tipo] // o cualquier valor por defecto
            );
        }

        // Obtener iglesias unidas a sus líderes
        $iglesias = Iglesia::leftJoin('lideres_local', 'iglesias.id_iglesia', '=', 'lideres_local.id_iglesia')
            ->where('iglesias.estado', true)
            ->where('iglesias.distrito_id', $distrito->id_distrito)
            ->select(
                'iglesias.id_iglesia',
                'iglesias.codigo',
                'iglesias.nombre',
                'iglesias.tipo',
                'lideres_local.*'
            )
            ->get();

        return view('iglesias.edit_masivo_lideres', compact('iglesias'));
    }
    //actualizacion masiva de lideres de 1 iglesia
    public function updateMasivo(Request $request)// permision editar pastor iglesias-lideres locales
    {
        DB::beginTransaction();
        try {
            foreach ($request->lideres as $id => $data) {
                LiderLocal::where('id_lideres', $id)->update($data);
            }

            DB::commit();
            return back()->with('success', 'Líderes actualizados correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }
    //
    //para ver los a detalle sus los distritos y la sumatoria de sus iglesias
    public function resumenDistritos() //permission'ver x distritos-lideres locales'
    {
        $resumenes = Iglesia::leftJoin('lideres_local', 'iglesias.id_iglesia', '=', 'lideres_local.id_iglesia')
            ->leftjoin('distritos', 'iglesias.distrito_id', '=', 'distritos.id_distrito')
            ->where('iglesias.estado', true)
            ->select(
                'distritos.nombre',
                'iglesias.distrito_id',
                DB::raw('COUNT(iglesias.id_iglesia) as total_iglesias'),
                // 🐛 SOLUCIÓN: Citamos la columna con comillas dobles: "lideres_local"."Dir_Filial"
                DB::raw('SUM(COALESCE("lideres_local"."Dir_Filial",0)) as total_filial'),
                DB::raw('SUM(COALESCE("lideres_local"."Dir_congregacion",0)) as total_congregacion'),
                DB::raw('SUM(COALESCE("lideres_local"."Anciano",0)) as total_anciano'),
                DB::raw('SUM(COALESCE("lideres_local"."Diaconisas",0)) as total_diaconisas'),
                DB::raw('SUM(COALESCE("lideres_local"."Diaconos",0)) as total_diaconos'),
                DB::raw('SUM(COALESCE("lideres_local"."EESS_Adultos",0)) as total_adultos'),
                DB::raw('SUM(COALESCE("lideres_local"."EESS_Jovenes",0)) as total_jovenes'),
                DB::raw('SUM(COALESCE("lideres_local"."EESS_Niños",0)) as total_ninos'),
                DB::raw('SUM(COALESCE("lideres_local"."GP",0)) as total_gp'),
                DB::raw('SUM(COALESCE("lideres_local"."Parejas_misioneras",0)) as total_parejas')
            )
            ->groupBy('distritos.nombre', 'iglesias.distrito_id')
            ->get();

        return view('iglesias.index_lideres_x_distrito', compact('resumenes'));
    }
    //de 1 distrito ver a detalle sus lideres locales
    public function detallePorDistrito($id) // permission 'ver x iglesias-lideres locales'
    {
        $distrito = Distrito::where('id_distrito', $id)->first();

        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }

        $iglesias = Iglesia::leftJoin('lideres_local', 'iglesias.id_iglesia', '=', 'lideres_local.id_iglesia')
            ->where('iglesias.distrito_id', $id)
            ->get();

        return view('iglesias.detalle_lideres_x_distrito', compact('iglesias', 'distrito'));
    }
}
