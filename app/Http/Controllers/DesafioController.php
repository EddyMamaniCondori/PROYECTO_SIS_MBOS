<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AuditoriaHelper;
use App\Models\Pastor; 
use App\Models\Iglesia;
use App\Models\Desafio; 
use App\Models\Distrito; 
use App\Models\Mensual; 
use App\Models\AnualIglesia; 
use Illuminate\Support\Facades\DB;
use Exception;

class DesafioController extends Controller
{
    /**
     * 
     * 'ver-desafios',
    *   'ver-desafios distrital anuales',
        *    'ver-desafios bautisos mbos anuales',
        *    'editar-desafio bautisos anuales',

     * Display a listing of the resource.
     */
    //MUESTRA TODOS LOS DESAFIOS ASIGNADOS A 1 DISTRITO ESPECIOFICO 
    
    function __construct()
    {
        // index(): permision ver desafios
        $this->middleware('permission:ver-desafios', ['only' => ['index']]);
        // index_distrital(): permision ver desafios distrital anuales
        $this->middleware('permission:ver-desafios distrital anuales', ['only' => ['index_distrital']]);
        // index_desafio_bautizos(): permision ver desafios bautisos mbos anuales
        $this->middleware('permission:ver-desafios bautisos mbos anuales', ['only' => ['index_desafio_bautizos']]);
        // update() y update_2(): permision editar desafio bautisos anuales
        $this->middleware('permission:editar-desafio bautisos anuales', ['only' => ['update', 'update_2']]);
        // Los métodos store(), create(), edit(), destroy(), show() se dejan sin protección por no tener etiqueta de permiso explícita.
    }

   
    public function index_distrital(string $id) // permission  'ver desafios distrital anuales'
    {
        $desafio = Desafio::find($id); // mejor usar findOrFail
        //dd($desafio); //bien
        $distrito = DB::table('distritos as xd')
            ->leftJoin('personas as xp', 'xd.id_pastor', '=', 'xp.id_persona')
            ->select(
                'xd.nombre as nombre_d',
                'xd.año',
                'xd.id_distrito',
                'xd.nro_iglesias',
                'xp.nombre as nombre_p',
                'xp.ape_paterno',
                'xp.ape_materno'
            )
            ->where('xd.id_distrito', $desafio->id_distrito)
            ->first(); // si esperas un solo resultado
        $visitas = Mensual::where('id_desafio', $desafio->id_desafio)
            ->get(); // todas las visitas asociadas al desafío

        $desafio_iglesias = AnualIglesia::join('iglesias as xi', 'anual_iglesias.id_iglesia', '=', 'xi.id_iglesia')
            ->where('anual_iglesias.id_desafio', $desafio->id_desafio)
            ->select(
                'anual_iglesias.*',
                'xi.nombre as nombre_iglesia',
                'xi.tipo as tipo_iglesia'
            )
            ->get();
        return view('desafio.desafio_distrital', compact('distrito','desafio', 'visitas', 'desafio_iglesias'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * ES UNA FUNCION AUTOMATICA QUE CREA Y ACTUALIZA LOS DESAFIOS PARA CADA DISTRITO ACTIVO
     */
    public function store()
    {
        try {
            $anioActual = now()->year;

            // Distritos que ya tienen desafío este año
            $distritosConDesafio = DB::table('desafios')
                ->where('anio', $anioActual)
                ->pluck('id_distrito')
                ->toArray();

            // Distritos activos sin desafío
            $distritosFaltantes = Distrito::where('estado', true)
                ->whereNotIn('id_distrito', $distritosConDesafio)
                ->leftJoin('pastors', 'distritos.id_pastor', '=', 'pastors.id_pastor') //AUNQUE NO TENGAN PASTOR ASIGNADO
                ->select('distritos.id_distrito', 'pastors.id_pastor')
                ->get();

            if ($distritosFaltantes->isEmpty()) {
                return redirect()->route('desafios.index')
                    ->with('info', 'Todos los distritos ya tienen desafío para este año.');
            }

            // Preparar datos
            $insertData = $distritosFaltantes->map(function ($d) use ($anioActual) {
                return [
                    'desafio_bautizo' => 0,
                    'bautizos_alcanzados' => 0,
                    'anio' => $anioActual,
                    'estado' => false,
                    'id_distrito' => $d->id_distrito,
                    'id_pastor' => $d->id_pastor,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            DB::beginTransaction();
            DB::table('desafios')->insert($insertData);
            DB::commit();

            $total = count($insertData);

            return redirect()->route('desafios.index')
                ->with('success', "Se crearon {$total} desafío(s) nuevo(s) correctamente.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear desafíos: ' . $e->getMessage());
            return redirect()->route('desafios.index')
                ->with('error', 'Ocurrió un error al crear los desafíos.');
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

    
    //actualiza el desafio bautizos desde una tabla con solo desafios
    public function update(Request $request, string $id) // permision 'editar desafio bautisos anuales'
    {
        try {
            // Validar los datos primero
            $validated = $request->validate([
                'desafio_bautizo' => 'required|integer|min:0',
            ]);

            DB::beginTransaction();

            $desafio = Desafio::find($id);
            
            $desafio->update($validated);
            
            $id_desafio = $desafio->id_desafio;
            
            DB::commit();
            //AuditoriaHelper::registrar('UPDATE', 'Desafio', $desafio->id_desafio);
            
            return redirect()->route('desafios.index_distrital', ['id' => $id_desafio])
                ->with('success', 'Desafío Bautizos actualizado correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el Desafío de Bautizos: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
/***
     * 
     * 
     * ____________________PARA DESAFIO BAUTISOS_________________________________
     * 
     */
    //muestra todos desafios y sus alcanzados en bautisos de todos los distrito es MBOS
    
    public function index_desafio_bautizos()  //permission 'ver desafios bautisos mbos anuales',
    {
        try {

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
            // CASO 1: El año de distritos es menor que el año actual
            // Significa que aún no se ha habilitado el nuevo año
            if ($anioDistritos < $anioActual) {
                // Mostrar desafíos del año de los distritos (año anterior)
                $desafios = Desafio::leftjoin('distritos as xd', 'desafios.id_distrito', '=', 'xd.id_distrito')
                        ->leftjoin('personas as xp', 'desafios.id_pastor', '=', 'xp.id_persona')
                        ->where('desafios.anio', $anioDistritos) 
                        ->where('xd.estado', true) 
                        ->select(
                            'desafios.*',
                            'xd.nombre as nombre_distrito',
                            'xp.nombre as nombre_p',
                            'xp.ape_paterno as ape_paterno_p',
                            'xp.ape_materno as ape_materno_p'
                        )
                        ->get();

                $ues = DB::select("
                                    SELECT 
                                        xu.id_ue, 
                                        xu.nombre, 
                                        xu.año, 
                                        -- Concatenamos nombres y apellidos en una sola celda
                                        STRING_AGG(CONCAT(xpp.nombre, ' ', xpp.ape_paterno, ' ', xpp.ape_materno), ' / ') AS nombres_capellanes,
                                        xu.desafios_bautismos, 
                                        xu.bautismos_alcanzados
                                    FROM unidad_educativas xu
                                    LEFT JOIN capellan xc ON xu.id_ue = xc.id_ue
                                    LEFT JOIN personas xpp ON xc.id_pastor = xpp.id_persona
                                    WHERE xu.estado = true
                                    AND xu.año = ?
                                    AND xu.id_ue IN (SELECT DISTINCT xd.id_ue FROM desafios xd WHERE xd.id_ue IS NOT NULL)
                                    GROUP BY 
                                        xu.id_ue, 
                                        xu.nombre, 
                                        xu.año, 
                                        xu.desafios_bautismos, 
                                        xu.bautismos_alcanzados", [$anioDistritos] 
                                );

                $anioActual = $anioDistritos;
                $anioMen = $anioActual+1; 
                return view('bautisos.index_bautizo', compact('desafios', 'anioActual', 'ues'))
                        ->with('info', "Desafíos del {$anioMen} pendientes. Habilita el nuevo período en ADMINISTRAR DESAFÍOS.");
            }
            // Obtener todos los desafíos del año actual
            $desafios = Desafio::leftjoin('distritos as xd', 'desafios.id_distrito', '=', 'xd.id_distrito')
                    ->leftjoin('personas as xp', 'desafios.id_pastor', '=', 'xp.id_persona')
                    ->where('desafios.anio', $anioActual) 
                    ->where('xd.estado', true) 
                    ->select(
                        'desafios.*',
                        'xd.nombre as nombre_distrito',
                        'xp.nombre as nombre_p',
                        'xp.ape_paterno as ape_paterno_p',
                        'xp.ape_materno as ape_materno_p'
                    )
                    ->get();
            $ues = DB::select("
                                    SELECT 
                                        xu.id_ue, 
                                        xu.nombre, 
                                        xu.año, 
                                        -- Concatenamos nombres y apellidos en una sola celda
                                        STRING_AGG(CONCAT(xpp.nombre, ' ', xpp.ape_paterno, ' ', xpp.ape_materno), ' / ') AS nombres_capellanes,
                                        xu.desafios_bautismos, 
                                        xu.bautismos_alcanzados
                                    FROM unidad_educativas xu
                                    LEFT JOIN capellan xc ON xu.id_ue = xc.id_ue
                                    LEFT JOIN personas xpp ON xc.id_pastor = xpp.id_persona
                                    WHERE xu.estado = true
                                    AND xu.año = ?
                                    AND xu.id_ue IN (SELECT DISTINCT xd.id_ue FROM desafios xd WHERE xd.id_ue IS NOT NULL)
                                    GROUP BY 
                                        xu.id_ue, 
                                        xu.nombre, 
                                        xu.año, 
                                        xu.desafios_bautismos, 
                                        xu.bautismos_alcanzados", [$anioActual] 
                                );
           
            return view('bautisos.index_bautizo', compact('desafios', 'anioActual', 'ues'));
            
        } catch (\Exception $e) {
            \Log::error('Error en index_bautisos de DesafioController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al cargar los desafíos.');
        }
    }
    
    //actualiza el desafio bautizos desde la todos los desafios de los distritos
    public function update_2(Request $request, string $id) // permision 'editar desafio bautisos anuales'
    {
        try {
            // Validar los datos primero
            $validated = $request->validate([
                'desafio_bautizo' => 'required|integer|min:0',
            ]);

            DB::beginTransaction();

            $desafio = Desafio::find($id);
            
            $desafio->update($validated);
            
            $id_desafio = $desafio->id_desafio;
            
            DB::commit();

            return redirect()->route('desafios.bautizos')
                ->with('success', 'Desafío Bautizos actualizado correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el Desafío de Bautizos: ' . $e->getMessage());
        }
    }
    
    /***
     * 
     * 
     * __________________________________________________________________________________________________________
     * 
     */
     // ES EL ACTUALIZADOR Y CREAD0R DE SAFIOS PARA TODOS 
    public function index()  //ver desafios
    {
        try {
            $anioActual = now()->year; //sacamos el año actual

             // Obtener el año configurado en la tabla distritos
            $anioDistritos = DB::table('distritos')
                ->where('estado', true)
                ->value('año'); // Obtiene el primer año encontrado
            // Si no hay distritos activos
            if (!$anioDistritos) {
                return view('desafio.index', [
                    'desafios' => collect([]),
                    'anioActual' => $anioActual,
                    'mensaje' => 'No hay distritos activos en el sistema.'
                ]);
            }
            // CASO 1: El año de distritos es menor que el año actual
            // Significa que aún no se ha habilitado el nuevo año
            if ($anioDistritos < $anioActual) {
                // Mostrar desafíos del año de los distritos (año anterior)
                $desafios = Desafio::leftjoin('distritos as xd', 'desafios.id_distrito', '=', 'xd.id_distrito')
                        ->leftjoin('personas as xp', 'desafios.id_pastor', '=', 'xp.id_persona')
                        ->where('desafios.anio', $anioDistritos) 
                        ->where('xd.estado', true) 
                        ->select(
                            'desafios.*',
                            'xd.nombre as nombre_distrito',
                            'xp.nombre as nombre_p',
                            'xp.ape_paterno as ape_paterno_p',
                            'xp.ape_materno as ape_materno_p'
                        )
                        ->get();
                
                return view('desafio.index', compact('desafios', 'anioDistritos'));
            }
            // CASO 2: Los años coinciden (anioDistritos == anioActual)
            // Proceder con la lógica normal de crear desafíos faltantes

            // IDs de distritos que ya tienen desafío este año
            $distritosConDesafio = DB::table('desafios')
                ->where('anio', $anioActual)
                ->pluck('id_distrito')
                ->toArray();
                
            // Distritos activos sin desafío para este año
            $distritosFaltantes = Distrito::where('estado', true)
                ->whereNotIn('id_distrito', $distritosConDesafio)
                ->leftJoin('pastors', 'distritos.id_pastor', '=', 'pastors.id_pastor')
                ->select('distritos.id_distrito', 'pastors.id_pastor')
                ->get();
                
            // Preparar datos para insertar desafios    
            $insertData = $distritosFaltantes->map(function ($d) use ($anioActual) {
                return [
                    'desafio_bautizo' => 0,
                    'bautizos_alcanzados' => 0,
                    'anio' => $anioActual,
                    'estado' => false,
                    'id_distrito' => $d->id_distrito,
                    'id_pastor' => $d->id_pastor,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();
            
            // 🆕 NUEVO: Variables para el resumen
            $desafiosCreados = 0;
            $iglesiasAsignadas = 0;
            $erroresAsignacion = [];

            if (!empty($insertData)) {
                DB::beginTransaction();
                try {
                    // Insertar desafíos
                    DB::table('desafios')->insert($insertData);
                    $desafiosCreados = count($insertData);
                    DB::commit(); // ✅ Commit de los desafíos primero
                } catch (\Exception $e) { //si no crea desafios entonces error
                    DB::rollBack();
                    \Log::error('Error al crear desafíos: ' . $e->getMessage());
                    session()->flash('error', 'No se pudieron crear los desafíos.');
                    return view('desafio.index', compact('desafios', 'anioActual'));
                }

                // 🆕 Obtener los IDs de los desafíos recién creados
                $nuevosDesafios = Desafio::where('anio', $anioActual)
                    ->whereIn('id_distrito', collect($insertData)->pluck('id_distrito'))
                    ->select('id_desafio', 'id_distrito')
                    ->get();
                
                // 🆕 Asignar iglesias DESPUÉS del commit (cada uno independiente)
                foreach ($nuevosDesafios as $desafio) {
                    // Cad en su propia transaca asignaciónción
                    DB::beginTransaction();
                    
                    try {
                        $resultado = $this->asignarAnualIglesiasFaltantes($desafio->id_desafio);
                        if ($resultado['success']) {
                            DB::commit();
                            $iglesiasAsignadas += $resultado['registros_creados'];
                        } else {
                            DB::rollBack();
                            $erroresAsignacion[] = "Desafío {$desafio->id_desafio}: {$resultado['message']}";
                            \Log::warning("No se pudieron asignar iglesias al desafío {$desafio->id_desafio}: {$resultado['message']}");
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $erroresAsignacion[] = "Desafío {$desafio->id_desafio}: Error inesperado";
                        \Log::error("Error al asignar iglesias al desafío {$desafio->id_desafio}: " . $e->getMessage());
                    }
                }
                
                // 🆕 Mensajes informativos
                if ($desafiosCreados > 0) {
                    $mensaje = "Se crearon {$desafiosCreados} desafío(s) nuevo(s) y se asignaron {$iglesiasAsignadas} iglesia(s).";
                    
                    if (!empty($erroresAsignacion)) {
                        $mensaje .= " Sin embargo, hubo problemas en " . count($erroresAsignacion) . " desafío(s).";
                        session()->flash('warning', $mensaje);
                    } else {
                        session()->flash('success', $mensaje);
                    }
                }
            }

            // Obtener todos los desafíos del año actual
            $desafios = Desafio::leftjoin('distritos as xd', 'desafios.id_distrito', '=', 'xd.id_distrito')
                    ->leftjoin('personas as xp', 'desafios.id_pastor', '=', 'xp.id_persona')
                    ->where('desafios.anio', $anioActual) 
                    ->where('xd.estado', true) 
                    ->select(
                        'desafios.*',
                        'xd.nombre as nombre_distrito',
                        'xp.nombre as nombre_p',
                        'xp.ape_paterno as ape_paterno_p',
                        'xp.ape_materno as ape_materno_p'
                    )
                    ->get();

            return view('desafio.index', compact('desafios', 'anioActual'));
            
        } catch (\Exception $e) {
            \Log::error('Error en index de DesafioController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al cargar los desafíos.');
        }
    }

    /**
         */ /*## 📋 **Flujo lógico del código:**
 
        1. Obtener año actual del sistema (2025)
        2. Obtener año configurado en distritos (puede ser 2024, 2025, etc.)

        3. ¿Hay distritos activos?
        NO → Mostrar mensaje "No hay distritos activos"
        SÍ → Continuar

        4. ¿anioDistritos < anioActual? (ej: 2024 < 2025)
        SÍ → El nuevo año NO está habilitado aún
                → Mostrar desafíos del año de los distritos (2024)
                → FIN
        
        5. ¿anioDistritos == anioActual? (ej: 2025 == 2025)
        SÍ → El nuevo año está habilitado
                → Crear desafíos faltantes
                → Asignar iglesias
                → Mostrar desafíos del año actual
                → FIN
        ```
        ---
        ## 🎯 **Escenarios de uso:**

        ### **Escenario 1: Año NO habilitado**
        ```
        Año actual: 2025
        Año distritos: 2024
        Resultado: Muestra desafíos de 2024 (no crea nada nuevo)
        ```

        ### **Escenario 2: Año habilitado**
        ```
        Año actual: 2025
        Año distritos: 2025
        Resultado: Crea desafíos faltantes para 2025 y muestra todos
    */

    /**
     * Crear registros anuales para TODAS las iglesias de un desafío
     */

    private function asignarAnualIglesias($id_desafio)
    {
        try {
            $desafio = Desafio::find($id_desafio);
            
            if (!$desafio) {
                return [
                    'success' => false,
                    'message' => 'Desafío no encontrado.',
                    'registros_creados' => 0
                ];
            }

            $iglesias = DB::table('iglesias')
                ->where('distrito_id', $desafio->id_distrito)
                ->where('estado', true)
                ->select('id_iglesia')
                ->get();

            if ($iglesias->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No hay iglesias asignadas a este distrito.',
                    'registros_creados' => 0
                ];
            }

            $insertData = $iglesias->map(function ($iglesia) use ($id_desafio) {
                return [
                    'id_iglesia' => $iglesia->id_iglesia,
                    'id_desafio' => $id_desafio,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            DB::table('anual_iglesias')->insert($insertData);

            return [
                'success' => true,
                'message' => 'Se crearon ' . count($insertData) . ' registros anuales.',
                'registros_creados' => count($insertData)
            ];

        } catch (\Exception $e) {
            \Log::error('Error en asignarAnualIglesias: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al asignar iglesias: ' . $e->getMessage(),
                'registros_creados' => 0
            ];
        }
    }

    /**
     * Crear registros anuales SOLO para iglesias que NO tienen registro
     */
    private function asignarAnualIglesiasFaltantes($id_desafio)
    {
        try {
            $desafio = Desafio::find($id_desafio);
            
            if (!$desafio) {
                return [
                    'success' => false,
                    'message' => 'Desafío no encontrado.',
                    'registros_creados' => 0
                ];
            }

            // IDs de iglesias que YA tienen registro
            $iglesiasConRegistro = DB::table('anual_iglesias')
                ->where('id_desafio', $id_desafio)
                ->pluck('id_iglesia')
                ->toArray();

            // Iglesias faltantes
            $iglesiasFaltantes = DB::table('iglesias')
                ->where('distrito_id', $desafio->id_distrito)
                ->where('estado', true)
                ->whereNotIn('id_iglesia', $iglesiasConRegistro)
                ->select('id_iglesia')
                ->get();

            if ($iglesiasFaltantes->isEmpty()) {
                return [
                    'success' => true,
                    'message' => 'Todas las iglesias ya tienen su registro.',
                    'registros_creados' => 0
                ];
            }

            $insertData = $iglesiasFaltantes->map(function ($iglesia) use ($id_desafio) {
                return [
                    'id_iglesia' => $iglesia->id_iglesia,
                    'id_desafio' => $id_desafio,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            DB::table('anual_iglesias')->insert($insertData);

            return [
                'success' => true,
                'message' => 'Se crearon ' . count($insertData) . ' registros faltantes.',
                'registros_creados' => count($insertData)
            ];

        } catch (\Exception $e) {
            \Log::error('Error en asignarAnualIglesiasFaltantes: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al completar iglesias: ' . $e->getMessage(),
                'registros_creados' => 0
            ];
        }
    }


    /**
     * Función 2: Crear registros anuales SOLO para iglesias que NO tienen registro
     * Útil cuando agregas iglesias nuevas al distrito y quieres completar los faltantes
     */
    public function asignarAnualIglesiasFaltantes_botom($id_desafio)
    {
        //DD('llego para actualizar iglesias');
        try {
            // 1. Buscar el desafío
            $desafio = Desafio::find($id_desafio);
            
            if (!$desafio) {
                return redirect()->back()->with('error', 'Desafío no encontrado.');
            }

            // 2. IDs de iglesias que YA tienen registro anual para este desafío
            $iglesiasConRegistro = DB::table('anual_iglesias')
                ->where('id_desafio', $id_desafio)
                ->pluck('id_iglesia')
                ->toArray();

            // 3. Buscar iglesias del distrito que NO tienen registro anual
            $iglesiasFaltantes = DB::table('iglesias')
                ->where('distrito_id', $desafio->id_distrito)
                ->where('estado', true) // Solo activas (opcional)
                ->whereNotIn('id_iglesia', $iglesiasConRegistro)
                ->select('id_iglesia')
                ->get();

            if ($iglesiasFaltantes->isEmpty()) {
                return redirect()->back()->with('success', 
                    'Todas las iglesias del distrito ya tienen su registro anual.');
            }

            // 4. Preparar datos para inserción masiva
            $insertData = $iglesiasFaltantes->map(function ($iglesia) use ($id_desafio) {
                return [
                    'id_iglesia' => $iglesia->id_iglesia,
                    'id_desafio' => $id_desafio,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            // 5. Insertar solo los registros faltantes
            DB::table('anual_iglesias')->insert($insertData);

            $id_desafio = $desafio->id_desafio;
            return redirect()->route('desafios.index_distrital', ['id' => $id_desafio])
                ->with('success', 'Se crearon ' . count($insertData) . ' registros faltantes para el distrito.');

        } catch (\Exception $e) {
            \Log::error('Error en asignarAnualIglesiasFaltantes: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al completar las iglesias faltantes.');
        }
    }

    
}
