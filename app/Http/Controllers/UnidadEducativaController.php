<?php

namespace App\Http\Controllers;

use App\Models\UnidadEducativa;
use App\Models\Desafio;
use Illuminate\Http\Request;

use App\Http\Requests\UnidadEducativaRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Pastor;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Route;

class UnidadEducativaController extends Controller
{
    
    public function index()
    {
        $ues = DB::select('select xu.id_ue, xu.nombre, xu.año,
                        xpp.id_persona, xpp.nombre as nombre_c, xpp.ape_paterno, xpp.ape_materno, xpp.celular
                        from unidad_educativas xu
                        left join capellan xc on xu.id_ue = xc.id_ue
                        left join personas xpp on xc.id_pastor = xpp.id_persona
                        where xu.estado = true');

        // Convertimos a colección y agrupamos por el nombre de la Unidad Educativa
        $resultado = DB::select('select max(xu.año) as max_anio from unidad_educativas xu');
        $anio = $resultado[0]->max_anio;
        return view('unidades_educativas.index', compact('anio', 'ues'));
    }
    public function index_eliminados()
    {
        $ues = DB::select('select xu.id_ue, xu.nombre as nombre_ue, xu.año,
                    xpp.id_persona, xpp.nombre as nombre_c, xpp.ape_paterno, xpp.ape_materno, xpp.celular
                    from unidad_educativas xu
                    left join capellan xc on xu.id_ue = xc.id_ue
                    left join personas xpp on xc.id_pastor =xpp.id_persona
                    where xu.estado = false');
        $resultado = DB::select('select max(xu.año) as max_anio from unidad_educativas xu');
        $anio = $resultado[0]->max_anio ?? date('Y');
        return view('unidades_educativas.indexeliminados', compact('anio', 'ues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    
        $pastores_libres = DB::select("
                        SELECT xpp.id_persona, xpp.nombre, xpp.ape_paterno, xpp.ape_materno, xp.*
                        FROM pastors xp
                        JOIN personas xpp ON xp.id_pastor = xpp.id_persona
                        WHERE cargo like 'capellan'
                        and xpp.estado = true
						and xpp.id_persona not in (select xc.id_pastor
													from capellan xc)
                    ");
        return view('unidades_educativas.create', compact('pastores_libres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnidadEducativaRequest $request)
    {
        $anio = DB::table('unidad_educativas')
            ->where('estado', true)
            ->value('año'); 
        try {
            DB::beginTransaction();
            $datos = array_merge($request->validated(), ['año' => $anio]);
            $unidadEducativa = UnidadEducativa::create($datos);
            $id_pastor = $request->input('id_pastor');
            if (!empty($id_pastor)) {
                // Usamos una sentencia que inserta solo si no existe la combinación (evita errores de duplicados)
                //dd($unidadEducativa, $id_pastor, $anio);
                DB::insert("
                    INSERT INTO capellan (id_pastor, id_ue, año, created_at, updated_at)
                    SELECT ?, ?, ?, NOW(), NOW()
                ", [
                    $id_pastor, 
                    $unidadEducativa->id_ue, // El ID de la UE que acabas de crear/editar
                    $anio
                ]);
            }
            DB::commit();
            return redirect()->route('asea.index')
                            ->with('success', 'Unidad Educativa creado correctamente');
        } catch (\Exception $e) {
           DB::rollBack();

            \Log::error('Error al crear distrito: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al registrar la Unidad Educativa.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UnidadEducativa $unidadEducativa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnidadEducativa $unidadEducativa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
         try {
        // 🔹 Validar nombre único sin importar mayúsculas/minúsculas
            $request->validate([
                'nombre' => [
                    'required',
                    'string',
                    'max:150',
                    function ($attribute, $value, $fail) use ($id) {
                        $exists = DB::table('unidad_educativas')
                            ->whereRaw('LOWER(nombre) = ?', [mb_strtolower($value)])
                            ->where('id_ue', '!=', $id)
                            ->exists();

                        if ($exists) {
                            $fail('Ya existe una U.E. con ese nombre.');
                        }
                    },
                ],
            ]);

            // 🔹 Buscar y actualizar el distrito
            $ue = UnidadEducativa::findOrFail($id);
            $ue->nombre = $request->nombre; // Se guarda tal cual
            $ue->save();
            //AuditoriaHelper::registrar('UPDATE', 'Distritos', $distrito->id_distrito);
            // 🔹 Redirigir con éxito
            return redirect()->route('asea.index')
                            ->with('success', 'U.E. actualizado correctamente.');
        } catch (ValidationException $e) {
            // ⚠️ Errores de validación
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // ⚠️ Errores generales (por ejemplo, de base de datos)
            \Log::error('Error al actualizar Unidad Educativa: '.$e->getMessage());
            return back()->with('error', 'Ocurrió un error al actualizar el U.E.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
                // Validar que exista
                $unidadEducativa = UnidadEducativa::findOrFail($id);
                if (!$unidadEducativa) {
                    throw new \Exception("El distrito con ID {$id} no existe.");
                }
                $unidadEducativa->estado = false;
                $unidadEducativa->save(); // lanza excepción en caso de fallo
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al Eliminar la Unidad Educativa: ' . $e->getMessage()], 500);
        }
        return redirect()->route('asea.index')->with('success','Unidad Educativa Inhabilitado Correctamente');
    }

    public function reactive(string $id) //permissions reactivar distritos 
    {
        try {
            DB::beginTransaction();
                $unidadEducativa = UnidadEducativa::findOrFail($id);
                $unidadEducativa->estado = true;
                $unidadEducativa->save(); // lanza excepción en caso de fallo
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al Reahabilitar la Unidad Educativa: ' . $e->getMessage()], 500);
        }
        return redirect()->route('asea.indexdelete')->with('success','Unidad Educativa habilitado Correctamente');
    }

    /*************************************************************
     *                  ASIGNACIONES
     *************************************************************/

    public function index_asignaciones($id) //permissions 'cambiar asignaciones ACT - distritos',
    {
        $colegio = UnidadEducativa::findOrFail($id);
        $resultado = DB::select('select max(xu.año) as max_anio from capellan xu');
        $anio = $resultado[0]->max_anio;
         $pastores_libres = DB::select("
                        SELECT xpp.id_persona, xpp.nombre, xpp.ape_paterno, xpp.ape_materno, xp.*
                        FROM pastors xp
                        JOIN personas xpp ON xp.id_pastor = xpp.id_persona
                        WHERE cargo like 'capellan'
                        and xpp.estado = true
						and xpp.id_persona not in (select xc.id_pastor
													from capellan xc)
                    ");
        return view('unidades_educativas.index_asignaciones', compact('anio', 'pastores_libres', 'colegio'));
    }


    public function asignar_cape($id) //permissions 'cambiar asignaciones ACT - distritos',
    {
        $colegio = UnidadEducativa::findOrFail($id);
        $resultado = DB::select('select max(xu.año) as max_anio from capellan xu');
        $anio = $resultado[0]->max_anio ?? date('Y');
         $pastores_libres = DB::select("
                        SELECT xpp.id_persona, xpp.nombre, xpp.ape_paterno, xpp.ape_materno, xp.*
                        FROM pastors xp
                        JOIN personas xpp ON xp.id_pastor = xpp.id_persona
                        WHERE cargo like 'capellan'
                        and xpp.estado = true
						and xpp.id_persona not in (select xc.id_pastor
													from capellan xc)
                    ");
        $capellanes = DB::select('select xc.id_pastor, xc.created_at, xp.nombre, xp.ape_paterno, xp.ape_materno, xp.celular
						from capellan xc
						join personas xp on xc.id_pastor = xp.id_persona
						where xc.año = ?
						and id_ue = ?', [$anio, $colegio->id_ue]);
        //dd($anio, $pastores_libres, $colegio, $capellanes);
        return view('unidades_educativas.index_asignaciones', compact('anio', 'pastores_libres', 'colegio', 'capellanes'));
    }

    public function add_cape(Request $request){
        $request->validate([
            'id_pastor' => 'required',
            'id_ue' => 'required',
            'anio' => 'required'
        ]);
        DB::table('capellan')->insert([
            'id_ue' => $request->id_ue,
            'id_pastor' => $request->id_pastor,
            'año' => $request->anio,
            'created_at' => now(), // Opcional, si tu tabla tiene estas columnas
            'updated_at' => now()
        ]);
        /*
        Para volver ala ruta post
        */

        $colegio = UnidadEducativa::findOrFail($request->id_ue);
        $resultado = DB::select('select max(xu.año) as max_anio from capellan xu');
        $anio = $resultado[0]->max_anio ?? date('Y');
         $pastores_libres = DB::select("
                        SELECT xpp.id_persona, xpp.nombre, xpp.ape_paterno, xpp.ape_materno, xp.*
                        FROM pastors xp
                        JOIN personas xpp ON xp.id_pastor = xpp.id_persona
                        WHERE cargo like 'capellan'
                        and xpp.estado = true
						and xpp.id_persona not in (select xc.id_pastor
													from capellan xc)
                    ");
        $capellanes = DB::select('select xc.id_pastor, xc.created_at, xp.nombre, xp.ape_paterno, xp.ape_materno, xp.celular
						from capellan xc
						join personas xp on xc.id_pastor = xp.id_persona
						where xc.año = ?
						and id_ue = ?', [$anio, $colegio->id_ue]);
        //dd($anio, $pastores_libres, $colegio, $capellanes);
        return view('unidades_educativas.index_asignaciones', compact('anio', 'pastores_libres', 'colegio', 'capellanes'))->with('success','Capellan agregado correctamente');
    }

    public function liberarAsignacion($id_ue,$id_pastor, $anio)//permissions 'cambiar asignaciones ACT - distritos',
    {
        //buscamos al distrito
        DB::table('capellan')
        ->where('id_ue', $id_ue)
        ->where('id_pastor', $id_pastor)
        ->where('año', $anio)
        ->delete();
        /*
        Para volver ala ruta post
        */

        $colegio = UnidadEducativa::findOrFail($id_ue);
        $resultado = DB::select('select max(xu.año) as max_anio from capellan xu');
        $anio = $resultado[0]->max_anio ?? date('Y');
         $pastores_libres = DB::select("
                        SELECT xpp.id_persona, xpp.nombre, xpp.ape_paterno, xpp.ape_materno, xp.*
                        FROM pastors xp
                        JOIN personas xpp ON xp.id_pastor = xpp.id_persona
                        WHERE cargo like 'capellan'
                        and xpp.estado = true
						and xpp.id_persona not in (select xc.id_pastor
													from capellan xc)
                    ");
        $capellanes = DB::select('select xc.id_pastor, xc.created_at, xp.nombre, xp.ape_paterno, xp.ape_materno, xp.celular
						from capellan xc
						join personas xp on xc.id_pastor = xp.id_persona
						where xc.año = ?
						and id_ue = ?', [$anio, $colegio->id_ue]);
        //dd($anio, $pastores_libres, $colegio, $capellanes);
        return view('unidades_educativas.index_asignaciones', compact('anio', 'pastores_libres', 'colegio', 'capellanes'))->with('success','Capellan liberado correctamente');
    }


    public function habilitar_desafios(Request $request)//permissions 'cambiar asignaciones ACT - distritos',
    {
        //dd($request);
        // 1. Validar los datos que vienen del formulario/API
        $validated = $request->validate([
            'anio'      => 'required|integer',
            'id_pastor' => 'required|exists:pastors,id_pastor',
            'id_ue'     => 'required|exists:unidad_educativas,id_ue',
        ]);

        // 2. Insertar en la base de datos
        $desafio = Desafio::create($validated);
        return redirect()->route('asea.index')->with('success', 'Se habilitó correctamente, puede asignar el desafío en el apartado de DESAFÍOS.');
    }
    

    public function update_desafio(Request $request, $id_ue)
    {
        // 1. Validar que sea un número positivo
        $request->validate([
            'desafios_bautismos' => 'required|integer|min:0',
        ]);

        //dd($request->validate(), $id_ue);

        // 2. Buscar y actualizar
        // Nota: Asegúrate de usar el nombre correcto de la llave primaria si no es 'id'
        $ue = UnidadEducativa::where('id_ue', $id_ue)->firstOrFail();
        
        $ue->update([
            'desafios_bautismos' => $request->desafios_bautismos
        ]);

        $ue->save();
        //ddd($ue);
        // 3. Redireccionar con mensaje de éxito
        return redirect()->back()->with('success', 'El desafío de bautismos ha sido actualizado.');
    }


}
