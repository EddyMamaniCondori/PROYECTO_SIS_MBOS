<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distrito;
use App\Models\Pastor;
use App\Models\Grupo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;


class DistritoController extends Controller
{
    public function index()
    {
        $anios = DB::select('SELECT DISTINCT xd.año FROM distritos xd');

        $distritos = Distrito::leftJoin('pastors', 'distritos.id_pastor', '=', 'pastors.id_pastor')
                     ->leftJoin('personas', 'pastors.id_pastor', '=', 'personas.id_persona')
                     ->leftJoin('grupos', 'grupos.id_grupo', '=', 'distritos.id_grupo')
                     ->select(
                         'distritos.*',
                         'personas.nombre as nombre_pastor',
                         'personas.ape_paterno as ape_paterno_pastor',
                         'personas.ape_materno as ape_materno_pastor',
                         'grupos.nombre as dist_nombre'
                     )
                     ->get();

        return view('distritos.index', compact('distritos', 'anios'));
    }

    

    public function index_historial()
    {
        $distritos = Distrito::all();
        return view('distritos.index_historial', ['distritos' => $distritos]);
    }


    public function historial($id_distrito)
    {
        // Usamos Query Builder para ejecutar la consulta
        $distrito = Distrito::find($id_distrito);

        $historial = \DB::select("
            SELECT xd.*, xp.nombre, xp.ape_paterno, xp.ape_materno
            FROM diriges xd
            JOIN personas xp ON xd.id_pastor = xp.id_persona
            WHERE xd.id_distrito = ?
            ORDER BY xd.año DESC
        ", [$id_distrito]);

        // Retornar la vista con los datos
        return view('distritos.historial', compact('historial', 'distrito'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pastores = Pastor::PastorDC();
        $grupos = Grupo::all();
        return view('distritos.create', compact('pastores', 'grupos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1️⃣ Validar los datos
        //dd($request);
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:150',
            'nro_iglesias' => 'nullable|integer|min:0',
            'id_grupo' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        
        // 2️⃣ Guardar el distrito
        $distrito = Distrito::create([
            'nombre'       => $request->nombre,
            'nro_iglesias' => $request->nro_iglesias ?? 0,
            'id_pastor'    => $request->id_pastor,
            'id_grupo'     => $request->id_grupo,
        ]);

        // 3️⃣ Redirigir con mensaje de éxito
        return redirect()->route('distritos.index')
                        ->with('success', 'Distrito creado correctamente');
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    //*****************FUNCIONES PARA ASIGNACIONES***************************** */



    public function copiarADiriges()
    {
        // Ejecutar SQL directo
        DB::statement("
            INSERT INTO diriges (id_distrito, id_pastor, fecha_asignacion, fecha_finalizacion, año, created_at, updated_at)
            SELECT 
                d.id_distrito,
                d.id_pastor,
                TO_DATE('01/01/' || d.año, 'DD/MM/YYYY') AS fecha_asignacion,
                TO_DATE('20/12/' || d.año, 'DD/MM/YYYY') AS fecha_finalizacion,
                d.año,
                NOW() AS created_at,
                NOW() AS updated_at
            FROM distritos d
            WHERE d.id_pastor IS NOT NULL
        ");

        DB::statement("
            UPDATE distritos 
            SET 
                sw_cambio = true,
                año = (año::integer + 1)
        ");

        return redirect()->back()->with('success', 'Habilitacion Correcta, para asignacion de Pastores Distritales');
    }

    
    public function index_asignaciones()
    {
        $anios = DB::select('SELECT DISTINCT xd.año FROM distritos xd');

        $distritos = Distrito::leftJoin('pastors', 'distritos.id_pastor', '=', 'pastors.id_pastor')
                     ->leftJoin('personas', 'pastors.id_pastor', '=', 'personas.id_persona')
                     ->leftJoin('grupos', 'grupos.id_grupo', '=', 'distritos.id_grupo')
                     ->where('distritos.sw_cambio', true)
                     ->select(
                         'distritos.*',
                         'personas.nombre as nombre_pastor',
                         'personas.ape_paterno as ape_paterno_pastor',
                         'personas.ape_materno as ape_materno_pastor',
                         'grupos.nombre as dist_nombre'
                     )
                     ->get();
         $pastores_libres = DB::select("
                        SELECT xpp.*
                        FROM pastors xp
                        JOIN personas xpp ON xp.id_pastor = xpp.id_persona
                        LEFT JOIN distritos d ON xp.id_pastor = d.id_pastor
                        WHERE d.id_pastor IS NULL
                    ");

        return view('distritos.index_asignaciones', compact('distritos', 'anios', 'pastores_libres'));
    }
    /*MANTENER EL PASTOR DISTRITAL Y EL PASTOR */
    public function mantenerAsignacion($id_distrito)
    {
        DB::table('distritos')
            ->where('id_distrito', $id_distrito)
            ->update(['sw_estado' => true]);

        return redirect()->back()->with('success', 'Asignación mantenida correctamente.');
    }
      /*liberar al pastor */
    public function liberarAsignacion($id_distrito)
    {
        DB::table('distritos')
            ->where('id_distrito', $id_distrito)
            ->update(['id_pastor' => null]);

        return redirect()->back()->with('success', 'Pastor liberado correctamente.');
    }

    public function cambiarAsignacion(Request $request, $id_distrito)
    {
        $request->validate([
            'id_pastor' => 'required|exists:pastors,id_pastor',
        ]);

        DB::table('distritos')
            ->where('id_distrito', $id_distrito)
            ->update([
                'id_pastor' => $request->id_pastor,
                'sw_estado' => true]);

        return redirect()->back()->with('success', 'Distrito actualizado correctamente.');
    }

    public function Finalizar_Asignaciones()
    {
        // Actualizar todos los registros de distritos
        DB::statement("
            UPDATE distritos
            SET sw_cambio = false
        ");

        // Redirigir a la vista distritos.index con mensaje
        return redirect()->route('distritos.index')
                 ->with('success', 'Todas las asignaciones terminaron');

    }

}
