<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distrito;
use App\Models\Pastor;
use App\Models\Grupo;
use App\Models\Dirige;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

use App\Http\Requests\DistritoRequest;

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
                     ->where('distritos.estado', true)
                     ->get();

        return view('distritos.index', compact('distritos', 'anios'));
    }

    public function index_eliminado()
    {

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
                     ->where('distritos.estado', false)
                     ->get();

        return view('distritos.index_eliminados', compact('distritos'));
    }


    public function index_historial()
    {
        $distritos = Distrito::all();
        return view('distritos.index_historial', ['distritos' => $distritos]);
    }


    public function historial($id_distrito)
    {
        // Usamos Query Builder para ejecutar la consulta
        $distrito = Distrito::leftJoin('personas as xp', 'distritos.id_pastor', '=', 'xp.id_persona')
            ->select(
                'distritos.*',
                'xp.nombre as nombre_p',
                'xp.ape_paterno',
                'xp.ape_materno'
            )
            ->where('distritos.id_distrito', $id_distrito)
            ->first();

        $historial = \DB::select("
            SELECT xd.*, xp.nombre, xp.ape_paterno, xp.ape_materno
            FROM diriges xd
            JOIN personas xp ON xd.id_pastor = xp.id_persona
            WHERE xd.id_distrito = ?
            ORDER BY xd.año DESC
        ", [$id_distrito]);

        //dd($distrito, $historial);
        // Retornar la vista con los datos
        return view('distritos.historial', compact('historial', 'distrito'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $pastores = DB::select("
                        SELECT xp.id_pastor, xpp.nombre, xpp.ape_paterno, xpp.ape_materno
                        FROM pastors xp
                        JOIN personas xpp ON xp.id_pastor = xpp.id_persona
                        LEFT JOIN distritos d ON xp.id_pastor = d.id_pastor
                        WHERE d.id_pastor IS NULL
                        and xpp.estado = true
                    ");

        $grupos = Grupo::all();
        return view('distritos.create', compact('pastores', 'grupos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DistritoRequest $request)
    {
        $anio = DB::table('distritos')
            ->where('estado', true)
            ->value('año'); 
        $sw_cambio = DB::table('distritos')
            ->where('estado', true)
            ->value('sw_cambio');
       DB::beginTransaction();
        try {
            $data = $request->validated();

            $distrito = Distrito::create([
                'nombre'       => $data['nombre'],
                'nro_iglesias' => $data['nro_iglesias'] ?? 0,
                'id_pastor'    => $data['id_pastor'],
                'id_grupo'     => $data['id_grupo'],
                'sw_cambio'     => $sw_cambio,
                'año'         => $anio, 
            ]);

            if($sw_cambio){
                    //creamois en el cambio
                    $anio_cambio = DB::table('asignacion_distritos')
                    ->value('año'); 

                    DB::insert(
                            'INSERT INTO asignacion_distritos (id_distrito_asignaciones, nombre, sw_estado, año, id_pastor )
                            VALUES (?, ?, ?, ?, ?)',
                            [
                                $distrito->id_distrito,
                                $distrito->nombre,
                                FALSE,
                                $anio_cambio,
                                $distrito->id_pastor
                            ]
                    );
            }
            DB::commit();

            return redirect()->route('distritos.index')
                            ->with('success', 'Distrito creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error al crear distrito: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al registrar el distrito.');
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
     * 
     * 
     * 
     */
    public function update(Request $request, string $id)
    {

        try {
        // 🔹 Validar nombre único sin importar mayúsculas/minúsculas
            $request->validate([
                'nombre' => [
                    'required',
                    'string',
                    'max:150',
                    function ($attribute, $value, $fail) use ($id) {
                        $exists = DB::table('distritos')
                            ->whereRaw('LOWER(nombre) = ?', [mb_strtolower($value)])
                            ->where('id_distrito', '!=', $id)
                            ->exists();

                        if ($exists) {
                            $fail('Ya existe un distrito con ese nombre (sin importar mayúsculas o minúsculas).');
                        }
                    },
                ],
            ]);

            // 🔹 Buscar y actualizar el distrito
            $distrito = Distrito::findOrFail($id);
            $distrito->nombre = $request->nombre; // Se guarda tal cual
            $distrito->save();

            // 🔹 Redirigir con éxito
            return redirect()->route('distritos.index')
                            ->with('success', 'Distrito actualizado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // ⚠️ Errores de validación
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // ⚠️ Errores generales (por ejemplo, de base de datos)
            \Log::error('Error al actualizar distrito: '.$e->getMessage());
            return back()->with('error', 'Ocurrió un error al actualizar el distrito.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //dd($id);
        try {
            DB::beginTransaction();

                //generamos histroriak
                $distrito = Distrito::find($id);

                if($distrito->sw_cambio){
                    
                    DB::table('asignacion_distritos')
                    ->where('id_distrito_asignaciones', $id)
                    ->delete();
                }

                // Validar que exista
                if (!$distrito) {
                    throw new \Exception("El distrito con ID {$id} no existe.");
                }
                // Solo si el distrito tenía un pastor anterior, guardamos en historial, y se lieberara al pastor
                if (!is_null($distrito->id_pastor)) {
                    
                    Dirige::create([
                        'id_distrito' => $distrito->id_distrito,
                        'id_pastor' => $distrito->id_pastor,
                        'fecha_asignacion' => $distrito->fecha_asignacion,
                        'fecha_finalizacion' => now(),
                        'año' => $distrito->año ?? date('Y'),
                    ]);  
                }
                $distrito->id_pastor = null;                // liberamos al pastor
                $distrito->id_grupo = null;                // ldebe de vincularse al grupo
                $distrito->fecha_asignacion = null;
                $distrito->estado = false;
                $distrito->save(); // lanza excepción en caso de fallo
                
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al Eliminar el Distrito: ' . $e->getMessage()], 500);
        }
        return redirect()->route('distritos.index')->with('success','Distrito Inhabilitado Correctamente');
    }


    
    public function reactive(string $id)
    {
        try {
            $anio = DB::table('distritos')
            ->where('estado', true)
            ->value('año');

            DB::beginTransaction();
            
            // buscamos al distrito
            $distrito = Distrito::find($id);
            //si esta cambio anual esta activado
                if($distrito->sw_cambio){
                    //creamois en el cambio
                    $anio = DB::table('asignacion_distritos')
                    ->value('año'); 

                    DB::insert(
                            'INSERT INTO asignacion_distritos (id_distrito_asignaciones, nombre, sw_estado, año, id_pastor )
                            VALUES (?, ?, ?, ?, ?)',
                            [
                                $distrito->id_distrito,
                                $distrito->nombre,
                                FALSE,
                                $anio,
                                $distrito->id_pastor
                            ]
                    );
                    $distrito -> sw_cambio = true;
                }

            $distrito -> estado = true;
            $distrito -> $anio;
            $distrito -> save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al Reactivar el distrito: ' . $e->getMessage()], 500);
        }
        return redirect()->route('distritos.index')->with('success','Distrito Habilitado Correctamente');
    }

    //_______________________________FUNCIONES PARA ASIGNACIONES*_______________________________*/
    // normal para cambiar durante todo el año (este si se reflejara en la tabla de disrtito)
    public function index_asignaciones()
    {
        $anios = DB::select('SELECT DISTINCT xd.año FROM distritos xd');

        $distritos = Distrito::leftJoin('pastors', 'distritos.id_pastor', '=', 'pastors.id_pastor')
                     ->leftJoin('personas', 'pastors.id_pastor', '=', 'personas.id_persona')
                     ->leftJoin('grupos', 'grupos.id_grupo', '=', 'distritos.id_grupo')
                     ->where('distritos.estado', true)
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
                        and xpp.estado = true
                    ");

        return view('distritos.index_asignaciones', compact('distritos', 'anios', 'pastores_libres'));
    }
    /*cambiar asignacion en año en curso */  
    public function cambiarAsignacion(Request $request, $id_distrito)
    {
        $request->validate([
            'id_pastor' => 'required|exists:pastors,id_pastor',
        ]);
        //buscamos al distrito
        $distrito = Distrito::find($id_distrito);

         // Solo si el distrito tenía un pastor anterior, guardamos en historial
        if (!is_null($distrito->id_pastor)) {
            Dirige::create([
                'id_distrito' => $distrito->id_distrito,
                'id_pastor' => $distrito->id_pastor,
                'fecha_asignacion' => $distrito->fecha_asignacion,
                'fecha_finalizacion' => now(),
                'año' => $distrito->año ?? date('Y'),
            ]);
        }

        // Actualizamos el nuevo pastor y la nueva fecha de asignación
        $distrito->update([
            'id_pastor' => $request->id_pastor,
            'fecha_asignacion' => now(),
        ]);
        return redirect()->back()->with('success', 'Distrito actualizado correctamente.');
    }

    /*liberar al pastor em gestion en curso */
    public function liberarAsignacion($id_distrito)
    {
        //buscamos al distrito
        $distrito = Distrito::find($id_distrito);
 
         // Solo si el distrito tenía un pastor anterior, guardamos en historial, y se lieberara al pastor
        if (!is_null($distrito->id_pastor)) {
            
            Dirige::create([
                'id_distrito' => $distrito->id_distrito,
                'id_pastor' => $distrito->id_pastor,
                'fecha_asignacion' => $distrito->fecha_asignacion,
                'fecha_finalizacion' => now(),
                'año' => $distrito->año ?? date('Y'),
            ]);

            $distrito->update([
                'id_pastor' => null,
                'fecha_asignacion' => null,
            ]);
        }
        

        return redirect()->back()->with('success', 'Pastor liberado correctamente.');
    }




    

    /*** _________________________ASIGNACIONES ANAULES_______________________ */
    public function copiarADiriges()
    {   
        $anio = DB::table('distritos')
            ->where('estado', true)
            ->value('año'); // devuelve el primer año encontrado
            
        $anio = $anio+1;
        DB::statement("
            INSERT INTO asignacion_distritos (
                id_distrito_asignaciones, nombre, sw_estado, año, id_pastor, created_at, updated_at
            )
            SELECT 
                id_distrito, nombre, FALSE, {$anio},  id_pastor, created_at, updated_at
            FROM distritos d
            WHERE d.estado = true
        ");

        DB::table('distritos')
            ->update(['sw_cambio' => true]);

        return redirect()->back()->with('success', 'Habilitacion Correcta, para asignacion de Pastores Distritales');
    }

     public function mantenerAsignacion($id_distrito)
    {
        DB::table('asignacion_distritos')
            ->where('id_distrito_asignaciones', $id_distrito)
            ->update(['sw_estado' => true]);

        return redirect()->back()->with('success', 'Asignación mantenida.');
    }

    public function liberarAsignacionAnual($id_distrito)
    {
        try {
            // 🔹 Ejecutar el UPDATE
            DB::update("
                UPDATE asignacion_distritos
                SET id_pastor = NULL
                WHERE id_distrito_asignaciones = :id
            ", [
                'id' => $id_distrito
            ]);

            // 🔹 Redirigir con mensaje de éxito
            return redirect()->back()->with('success', 'Pastor liberado correctamente.');
        } catch (\Exception $e) {
            // 🔹 Registrar el error en el log
            \Log::error("Error al liberar pastor: " . $e->getMessage());

            // 🔹 Redirigir con mensaje de error
            return redirect()->back()->with('error', 'Ocurrió un error al liberar al pastor.');
        }
    }

    public function indexanual()
    {

        
        $sw_cambio = DB::table('distritos')
            ->where('estado', true)
            ->value('sw_cambio');

        $anio = DB::table('distritos')
            ->where('estado', true)
            ->value('año');
        if($sw_cambio){
            $anio = DB::table('asignacion_distritos')
            ->value('año');
        }
        
        $distritos = DB::select('SELECT 
                                        d.*,
                                        p.nombre AS nombre_pastor,
                                        p.ape_paterno AS ape_paterno_pastor,
                                        p.ape_materno AS ape_materno_pastor
                                    FROM asignacion_distritos d
                                    LEFT JOIN personas p ON d.id_pastor = p.id_persona
                                    ');
         $pastores_libres = DB::select("
                        SELECT xpp.id_persona, xpp.nombre, xpp.ape_paterno, xpp.ape_materno
                        FROM pastors xp
                        JOIN personas xpp ON xp.id_pastor = xpp.id_persona
                        LEFT JOIN asignacion_distritos d ON xp.id_pastor = d.id_pastor
                        WHERE d.id_pastor IS NULL
                        and xpp.estado = true
                    ");

        return view('distritos.asignacion_anual', compact('distritos', 'anio', 'pastores_libres'));
    }

    public function cambiarAsignacionAnual(Request $request, $id_distrito)
    {
        $request->validate([
            'id_pastor' => 'required|exists:pastors,id_pastor',
        ]);

         DB::update("
                UPDATE asignacion_distritos
                SET id_pastor = :id_pastor,
                    sw_estado = TRUE
                WHERE id_distrito_asignaciones = :id
            ", [
                'id' => $id_distrito,
                'id_pastor' => $request->id_pastor
            ]);
        return redirect()->back()->with('success', 'Distrito actualizado correctamente.');
    }
    

    public function Finalizar_Asignaciones($anio)
    {
        try {
            //  1: ACTULIZAR HISTORIAL
            // Obtener todos los distritos activos
            $distritos = Distrito::where('estado', true)->get();
            $fechaFinalizacion = Carbon::create($anio - 1, 12, 31)->endOfDay();
            foreach ($distritos as $distrito) {
                // Solo si tenía un pastor asignado
                if (!is_null($distrito->id_pastor)) {
                    // Guardar en historial
                    Dirige::create([
                        'id_distrito' => $distrito->id_distrito,
                        'id_pastor' => $distrito->id_pastor,
                        'fecha_asignacion' => $distrito->fecha_asignacion,
                        'fecha_finalizacion' => $fechaFinalizacion,
                        'año' => $distrito->año,
                    ]);
                }
            }

           
            // Actualizar todos los registros de distritos con datos de asignacion_distritos
            //2. para evitar errores todos las tuplas distrtito se cambia a falso
            DB::update('UPDATE distritos SET sw_cambio = ?, año = ?', [false, $anio]);

            // 2. ACTUALIZAR TODOS LOS REGISTROS CON LAS MODIFICACIONES
            DB::update("
                UPDATE distritos d
                SET 
                    nombre = ad.nombre,
                    id_pastor = ad.id_pastor,
                    sw_cambio = FALSE,
                    año = :anio,
                    fecha_asignacion = TO_DATE(:fecha_asignacion, 'DD/MM/YYYY')
                FROM asignacion_distritos ad
                WHERE d.id_distrito = ad.id_distrito_asignaciones
            ", [
                'anio' => $anio,
                'fecha_asignacion' => "01/01/$anio"
            ]);
            // 3. VASIAR LA TABLA ASIGNACIONES_DISRTITALERS
            DB::statement('DELETE FROM asignacion_distritos');


            // Redirigir con mensaje de éxito
            return redirect()
                ->route('distritos.index')
                ->with('success', "Todas las asignaciones del año $anio terminaron correctamente.");
        
        } catch (\Exception $e) {
            // Captura errores SQL o de conexión
            return redirect()
                ->route('distritos.index')
                ->with('error', 'Error al finalizar asignaciones: ' . $e->getMessage());
        }
    }


}
