<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Iglesia;
use App\Models\Remesa;
use App\Models\Genera;
use App\Models\Puntualidad;
use App\Models\RemesaFilial;
use App\Models\RemesaIglesia;
use App\Models\Mes;
use Carbon\Carbon;
use App\Traits\CalculaSaldoTrait;
use App\Http\Controllers\RemesaController;


class RemesaController extends Controller
{
    use CalculaSaldoTrait;
    /**
     * 
     * // 🔹 Remesas// SATISFECHOS
     *       'ver meses-remesas',
     *       'crear meses-remesas',
     *       'ver remesas mes-remesas',
*
      *      'ver remesas filiales-remesas',
      *      'llenar remesas filiales-remesas',
      *      'registra remesas filiales-remesas',

     * Display a listing of the resource.
     */

    function __construct()
    {
        // index(): 'ver meses - remesas'
        $this->middleware('permission:ver meses-remesas', ['only' => ['index']]);

        // crear(): 'crear meses - remesas' (Asumo que esta es la función que genera los registros)
        $this->middleware('permission:crear meses-remesas', ['only' => ['crear']]);

        // index_mes(): 'ver remesas mes - remesas'
        $this->middleware('permission:ver remesas mes-remesas', ['only' => ['index_mes', 'registrar_remesa_iglesia']]); 
        // Nota: Agrupé registrar_remesa_iglesia con la vista, asumo que al registrar la remesa de iglesia regresa al index_mes.

        // llenar_filial(): 'ver remesas filiales - remesas'
        $this->middleware('permission:ver remesas filiales-remesas', ['only' => ['llenar_filial']]);

        // registrar_remesa_filial(): 'llenar remesas filiales - remesas'
        $this->middleware('permission:llenar remesas filiales-remesas', ['only' => ['registrar_remesa_filial']]);

        // registrar_remesa_iglesia() (ya cubierta arriba, pero le aplica el permiso de registro de filiales por la lista que proporcionaste)
        // Usamos el permiso que parece más aplicable a la acción de registro, aunque el nombre dice 'filiales'.
        $this->middleware('permission:registra remesas filiales-remesas', ['only' => ['registrar_remesa_iglesia']]);
        
        // El método 'registrar_remesa_iglesia' tiene dos permisos aplicados en la lista, priorizaremos la más específica (registro).

        // Los métodos store, show, edit, update, destroy y asignar_puntualidad() se dejan sin protección por no tener etiqueta.
    }

    public function index() //'ver meses - remesas',
    {
        $meses = DB::select("
                SELECT DISTINCT xg.mes, xg.anio, xr.fecha_limite
                FROM generas xg
                join remesas xr on xg.id_remesa = xr.id_remesa
                ORDER BY xg.anio DESC, xg.mes DESC
        ");

        $nombreMes = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
            4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
            10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            
        ];

        foreach ($meses as $m) {
            $m->nombre_mes = $nombreMes[$m->mes] ?? 'Desconocido';
        }
        $ultimo = $meses[0] ?? null;
        //dd($ultimo);
        return view('remesas.index', compact('meses', 'ultimo'));
    }

    public function index_mes($mes, $anio) //permission 'ver remesas mes - remesas',
    {
        $resultados = DB::select("
        SELECT 
                xd.nombre AS nombre_distrito,
                xi.codigo,
                xi.id_iglesia,
                xp.nombre AS nombre_pas,
                xp.ape_paterno,
                xp.ape_materno,
                xi.nombre AS nombre_igle,
                xi.tipo AS tipo_igle,
                xi.lugar AS lugar_igle,
                xr.*,
				xperso.nombre as nombre_per,
				xperso.ape_paterno as ape_paterno_per
            FROM generas xg
            left JOIN iglesias xi ON xg.id_iglesia = xi.id_iglesia
            left JOIN distritos xd ON xi.distrito_id = xd.id_distrito
            left JOIN remesas xr ON xg.id_remesa = xr.id_remesa
            left JOIN personas xp ON xd.id_pastor = xp.id_persona
            left JOIN personas xperso on xr.id_personal = xperso.id_persona
            WHERE xg.mes = :mes
            AND xg.anio = :anio
            AND xi.estado = true
            order by nombre_distrito
        ", [
            'mes' => $mes,
            'anio' => $anio
        ]);

        $personal = DB::select("select xp.id_persona, xp.nombre, xp.ape_paterno, xr.name
                            from personas xp
                            join personales xpp on xp.id_persona = xpp.id_personal
                            join model_has_roles xm on xp.id_persona = xm.model_id 
                            join roles xr on xm.role_id = xr.id
                            where xr.name like 'Tesorero'
                    ");


        return view('remesas.index_mes', [
            'datos' => $resultados,
            'mes' => $mes,
            'anio' => $anio,
            'personal' =>$personal,
        ]);
    }


    public function get_remesas_json(Request $request, $mes, $anio) {
        $query = DB::table('generas as xg')
        ->select([
            'xd.nombre as nombre_distrito',
            'xi.codigo',
            'xi.id_iglesia',
            'xp.nombre as nombre_pas',
            'xp.ape_paterno',
            'xp.ape_materno',
            'xi.nombre as nombre_igle',
            'xi.tipo as tipo_igle',
            'xi.lugar as lugar_igle',
            'xr.*',
            'xperso.nombre as nombre_per',
            'xperso.ape_paterno as ape_paterno_per'
        ])
        ->leftJoin('iglesias as xi', 'xg.id_iglesia', '=', 'xi.id_iglesia')
        ->leftJoin('distritos as xd', 'xi.distrito_id', '=', 'xd.id_distrito')
        ->leftJoin('remesas as xr', 'xg.id_remesa', '=', 'xr.id_remesa')
        ->leftJoin('personas as xp', 'xd.id_pastor', '=', 'xp.id_persona')
        ->leftJoin('personas as xperso', 'xd.id_responsable_remesa', '=', 'xperso.id_persona')
        ->where('xg.mes', $mes)
        ->where('xg.anio', $anio)
        ->where('xi.estado', true);

        $personal = DB::select("select xp.id_persona, xp.nombre, xp.ape_paterno, xr.name
                            from personas xp
                            join personales xpp on xp.id_persona = xpp.id_personal
                            join model_has_roles xm on xp.id_persona = xm.model_id 
                            join roles xr on xm.role_id = xr.id
                            where xr.name like 'Tesorero'
                    ");
        
        // Filtro: Personal Responsable
        if ($request->filled('id_personal') && $request->id_personal != -1) {
            $query->where('xd.id_responsable_remesa', $request->id_personal);
        }

        // Filtro: Tipo (Múltiple con Checkboxes)
        if ($request->has('tipo') && !in_array('TODOS', $request->tipo)) {
            $query->whereIn('xi.tipo', $request->tipo);
        }

        // Filtro: Lugar (Múltiple con Checkboxes)
        if ($request->has('lugar') && !in_array('TODOS', $request->lugar)) {
            $query->whereIn('xi.lugar', $request->lugar);
        }

        // Filtro: Estado (Múltiple: PENDIENTE, ENTREGADO, etc.)
        if ($request->has('estado') && !in_array('TODOS', $request->estado)) {
            $query->whereIn('xr.estado', $request->estado);
        }

        // 2. Obtenemos los resultados ordenados
        $resultados = $query->orderBy('nombre_distrito')->get();

        // 3. (Opcional) Si aún necesitas la lista de personal para algo en el JSON
        $personal = DB::table('personas as xp')
            ->join('personales as xpp', 'xp.id_persona', '=', 'xpp.id_personal')
            ->join('model_has_roles as xm', 'xp.id_persona', '=', 'xm.model_id')
            ->join('roles as xr_rol', 'xm.role_id', '=', 'xr_rol.id')
            ->where('xr_rol.name', 'like', 'Tesorero')
            ->select('xp.id_persona', 'xp.nombre', 'xp.ape_paterno')
            ->get();

        return response()->json([
            'data' => $resultados,
            'mes' => $mes,
            'anio' => $anio,
            'personal' => $personal
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    
    //habilita un mes y crea para todas las igelsias activas su remesa del correspondientes mes y año
    public function crear(Request $request)  //permission 'crear meses - remesas',
    {
        //dd($request);
        $mes = $request->mes;
        $anio = $request->anio;
        $fecha_limite = $request->fecha_limite;
        DB::beginTransaction();
        try {
            // Solo iglesias activas (estado = true)
            $iglesias = DB::table('iglesias')->where('estado', true)->get();
            
            foreach ($iglesias as $iglesia) {
                /*ESTA PARTE CREA PUNTUALIDAD SI NO LA TIENE*/
                $puntualidad = Puntualidad::where('id_iglesia', $iglesia->id_iglesia)
                    ->where('anio', $anio)
                    ->first();
                if (!$puntualidad) {
                    // No existe, crear puntualidad
                    $puntualidad = Puntualidad::create([
                        'id_iglesia' => $iglesia->id_iglesia,
                        'anio' => $anio,
                    ]);
                }
                /*ESTA PARTE ES PARA REMESAS*/
                $id_remesa = DB::table('remesas')->insertGetId([
                    'fecha_limite' => $fecha_limite,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ], 'id_remesa');

                DB::table('generas')->insert([
                    'id_iglesia' => $iglesia->id_iglesia,
                    'id_remesa' => $id_remesa,
                    'mes' => $mes,
                    'anio' => $anio,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                if (strtolower($iglesia->tipo) == 'filial') {
                    DB::table('remesas_filiales')->insert([
                        'id_remesa' => $id_remesa,
                        'ofrenda' => 0,
                        'diezmo' => 0,
                        'pro_templo' => 0,
                        'fondo_local' => 0,
                        'monto_remesa' => 0,
                        'gasto' => 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                } else {
                    DB::table('remesas_iglesias')->insert([
                        'id_remesa' => $id_remesa,
                        'monto' => 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }

                // se crea el mes para esa puntualidad
                $mesRegistro = Mes::create([
                    'id_puntualidad' => $puntualidad->id_puntualidad,
                    'mes' => $mes,
                    'tipo' => '0',
                ]);

                // se crea la justificacion
                DB::table('justificas')->insert([
                        'id_puntualidad' => $puntualidad->id_puntualidad,
                        'mes' => $mesRegistro->mes,
                        'id_remesa' => $id_remesa,
                        'created_at' => now(),
                        'updated_at' => now(),
                        // agrega otros campos si existen
                ]);

            }

            DB::commit();
            return redirect()->route('remesas.index')->with('success', 'Remesas y gastos generados correctamente para todas las iglesias.');


        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al generar remesas: ' . $e->getMessage());
        }
    }

    //edita la fecha limite de un mes
    public function editarFechMes(Request $request) 
    {
        $mes = $request->mes;
        $anio = $request->anio;
        $nueva_fecha_limite = $request->fecha_limite;

        DB::beginTransaction();
        try {
            // Realizamos la actualización masiva basada en tu lógica SQL
            $actualizados = DB::table('remesas')
                ->whereIn('id_remesa', function ($query) use ($mes, $anio) {
                    $query->select('id_remesa')
                        ->from('generas')
                        ->where('mes', $mes)
                        ->where('anio', $anio);
                })
                ->update([
                    'fecha_limite' => $nueva_fecha_limite,
                    'updated_at'   => Carbon::now(),
                ]);

            DB::commit();
            return redirect()->route('remesas.index')
                ->with('success', "Se actualizaron $actualizados registros para el periodo $mes/$anio.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar la fecha límite: ' . $e->getMessage());
        }
    }
    /**
     * Store a newly created resource in storage.
     */

    public function actualizar_iglesias($mes, $anio, $fecha_limite) //este metodo actualiza las remesas, en un mes y anio y compelta las iglesias que faltan.
    {
        //dd($mes, $anio, $fecha_limite);
        // 1. Obtener IDs de iglesias que YA tienen remesa en este mes/año
        $iglesiasConRegistro = DB::table('generas')
            ->where('mes', $mes)
            ->where('anio', $anio)
            ->pluck('id_iglesia');

        // 2. Obtener solo las iglesias activas que NO están en esa lista
        $iglesiasFaltantes = DB::table('iglesias')
            ->where('estado', true)
            ->whereNotIn('id_iglesia', $iglesiasConRegistro)
            ->get();

        if ($iglesiasFaltantes->isEmpty()) {
            return redirect()->back()->with('info', 'Todas las iglesias ya están al día.');
        }

        // Usamos una transacción para asegurar la integridad de los datos
        DB::beginTransaction();
        //dd($iglesiasFaltantes);
        try {

            foreach ($iglesiasFaltantes as $iglesia) {
                // --- LÓGICA DE CREACIÓN (Igual a tu método crear) ---
                
                 /*ESTA PARTE CREA PUNTUALIDAD SI NO LA TIENE*/
                $puntualidad = Puntualidad::where('id_iglesia', $iglesia->id_iglesia)
                    ->where('anio', $anio)
                    ->first();
                if (!$puntualidad) {
                    // No existe, crear puntualidad
                    $puntualidad = Puntualidad::create([
                        'id_iglesia' => $iglesia->id_iglesia,
                        'anio' => $anio,
                    ]);
                }
                /*ESTA PARTE ES PARA REMESAS*/
                $id_remesa = DB::table('remesas')->insertGetId([
                    'fecha_limite' => $fecha_limite,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ], 'id_remesa');

                DB::table('generas')->insert([
                    'id_iglesia' => $iglesia->id_iglesia,
                    'id_remesa' => $id_remesa,
                    'mes' => $mes,
                    'anio' => $anio,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                if (strtolower($iglesia->tipo) == 'filial') {
                    DB::table('remesas_filiales')->insert([
                        'id_remesa' => $id_remesa,
                        'ofrenda' => 0,
                        'diezmo' => 0,
                        'pro_templo' => 0,
                        'fondo_local' => 0,
                        'monto_remesa' => 0,
                        'gasto' => 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                } else {
                    DB::table('remesas_iglesias')->insert([
                        'id_remesa' => $id_remesa,
                        'monto' => 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }

                // se crea el mes para esa puntualidad
                $mesRegistro = Mes::create([
                    'id_puntualidad' => $puntualidad->id_puntualidad,
                    'mes' => $mes,
                    'tipo' => '0',
                ]);

                // se crea la justificacion
                DB::table('justificas')->insert([
                        'id_puntualidad' => $puntualidad->id_puntualidad,
                        'mes' => $mesRegistro->mes,
                        'id_remesa' => $id_remesa,
                        'created_at' => now(),
                        'updated_at' => now(),
                        // agrega otros campos si existen
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Se actualizaron ' . $iglesiasFaltantes->count() . ' iglesias nuevas.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**********************************************************************************
     * FUNCIONES DE ALERTAS
     ***********************************************************************************/
    public function getAlertas($id_iglesia)
    {
        $resultados = DB::select("
            select xr.id_remesa, xr.alerta 
            from generas xg
            join remesas xr on xg.id_remesa = xr.id_remesa
            where xg.id_iglesia = ?
            and xr.alerta IS NOT NULL
            order by xg.anio DESC, xg.mes DESC
        ", [$id_iglesia]);

        $alertasFinales = [];

        foreach ($resultados as $fila) {
            $alertasDeEstaRemesa = json_decode($fila->alerta, true) ?? [];

            // ¡OJO AQUÍ! Agregamos $key => para que no de error
            foreach ($alertasDeEstaRemesa as $key => $alerta) { 
                if (isset($alerta['estado']) && $alerta['estado'] !== 'ANULADO') {
                    $alerta['id_remesa'] = $fila->id_remesa;
                    $alerta['index'] = $key; // Ahora $key ya existe
                    $alertasFinales[] = $alerta;
                }
            }
        }
        //dd($alertasDeEstaRemesa);
        return response()->json($alertasFinales);
    }
    public function updateAlerta(Request $request) {
        $remesa = Remesa::find($request->id_remesa);
        $alertas = json_decode($remesa->alerta, true);

        // Modificamos solo el índice que nos enviaron
        $index = $request->index;
        if (isset($alertas[$index])) {
            $alertas[$index]['estado'] = $request->estado;
            $alertas[$index]['mensaje'] = $request->mensaje;
        }

        $remesa->alerta = json_encode($alertas);
        $remesa->save();

        return response()->json(['success' => true]);
    }
    public function deleteAlerta(Request $request) {
        $remesa = Remesa::find($request->id_remesa);
        $alertas = json_decode($remesa->alerta, true);

        // Quitamos el elemento del array
        unset($alertas[$request->index]);

        // Reindexamos el array para que no queden huecos (0, 1, 2...)
        // y lo guardamos. Si queda vacío, ponemos NULL.
        $nuevoArray = array_values($alertas);
        $remesa->alerta = empty($nuevoArray) ? null : json_encode($nuevoArray);
        
        $remesa->save();

        return response()->json(['success' => true]);
    } 
    public function storeAlerta(Request $request) 
    {
        $remesa = DB::table('remesas')->where('id_remesa', $request->id_remesa)->first();
        
        // Decodificar alertas existentes o crear un array vacío si no hay
        $alertas = json_decode($remesa->alerta, true) ?? [];

        // Creamos el nuevo objeto de alerta
        $nuevaAlerta = [
            'estado'  => $request->estado,
            'mensaje' => $request->mensaje,
            'periodo' => $request->periodo
        ];

        // LA AGREGAMOS al final del array
        $alertas[] = $nuevaAlerta;

        // Guardamos de nuevo en la base de datos
        DB::table('remesas')
            ->where('id_remesa', $request->id_remesa)
            ->update(['alerta' => json_encode($alertas)]);

        return response()->json(['status' => 'success']);
    }
    /**********************************************************************************/

    public function updateRemesa(Request $request)
    {
        $request->validate([
            'id_remesa' => 'required',
            'fecha_entrega' => 'required|date',
        ]);

        $id = $request->input('id_remesa');
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        $remesa = Remesa::find($id);

        if (!$remesa) {
            return response()->json(['success' => false, 'message' => 'La remesa no existe.'], 404);
        }
        $fechaEntrega = Carbon::parse($request->fecha_entrega);
        $fechaLimite = Carbon::parse($remesa->fecha_limite);
        
        DB::beginTransaction();  
        try {
            // Validación
            

            // Actualización
            // 2. Asignar valores del formulario (Switches y otros)
            $remesa->cierre = $request->cierre === 'true';
            $remesa->deposito = $request->deposito === 'true';
            $remesa->documentacion = $request->documentacion === 'true';
            $remesa->escaneado = $request->escaneado === 'true'; // El nuevo campo que agregamos
            $remesa->arqueo = $request->arqueo === 'true'; 
            $remesa->fecha_entrega = $request->fecha_entrega;
            $remesa->observacion = $request->observacion;
            $remesa->estado = 'ENTREGADO';
            //CALCULO DE ESTADO DE DIAS
            $fechaEntrega = Carbon::parse($request->fecha_entrega);
            $fechaLimite = Carbon::parse($remesa->fecha_limite);

            // 2. CREAMOS COPIAS para el cálculo de puntualidad (reseteando la hora)
            // Usamos ->copy() para no afectar la fecha original que guardaremos en la DB
            $entregaSoloFecha = $fechaEntrega->copy()->startOfDay();
            $limiteSoloFecha = $fechaLimite->copy()->startOfDay();

            // 3. Calculamos la diferencia en días (ahora será SIEMPRE un entero)
            // El segundo parámetro 'false' permite que sea negativo si se pasó de la fecha
            $diferencia = $entregaSoloFecha->diffInDays($limiteSoloFecha, false);

            if ($diferencia === 0) {
                $remesa->estado_dias = 'Completado con 0 días de retraso (entrega puntual)';
            } elseif ($diferencia > 0) {
                $remesa->estado_dias = "Completado con {$diferencia} día(s) de adelanto";
            } else {
                $remesa->estado_dias = "Entregado con " . abs($diferencia) . " día(s) de retraso";
            }
            //
            // 4. 🔹 CALCULAR ESTRELLAS (Según lugar)
            // Buscamos la iglesia relacionada
            $iglesia = Genera::where('id_remesa', $remesa->id_remesa)
                        ->with('iglesia')
                        ->first()?->iglesia;
            if ($iglesia) {
                $lugar = strtoupper($iglesia->lugar ?? ''); 
                $estrella = "0"; // Valor por defecto

                if ($lugar === 'EL ALTO') {
                    if ($diferencia >= 0) {
                        $estrella = "2";
                    } elseif ($diferencia < 0 && abs($diferencia) <= 2) {
                        $estrella = "1";
                    } else {
                        $estrella = "0";
                    }
                } 
                elseif ($lugar === 'ALTIPLANO') {
                    // Hasta 5 días de retraso → 2, sino → 0
                    if ($diferencia >= 0 || abs($diferencia) <= 5) {
                        $estrella = "2";
                    } else {
                        $estrella = "0";
                    }
                }  
                // 5. 🔹 GUARDAR EN TABLA PUNTUALIDAD
                $puntualidad = Puntualidad::where('id_iglesia', $iglesia->id_iglesia)
                                        ->where('anio', $anio) // Usamos el año que viene del request
                                        ->first();

                if ($puntualidad) {
                    // Actualizamos el mes correspondiente en la tabla de puntos/estrellas
                    DB::table('mes') // Ajusta el nombre de la tabla si es 'meses' o 'mes'
                        ->where('id_puntualidad', $puntualidad->id_puntualidad)
                        ->where('mes', $mes)
                        ->update(['tipo' => $estrella]);
                }
                
                // Guardamos la estrella calculada también en la remesa para tenerlo a mano
                
            }     
            $remesa->save();
            DB::commit();     
            // Retornamos respuesta JSON exitosa
            return response()->json([
                
                'status' => 'success',
                'message' => 'Remesa actualizada correctamente'
            ], 200);

        } catch (\Exception $e) {
            // Retornamos error en JSON
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    //REVERTIR REMESA SI EL TESOREERO SE EQUIVOCO

    public function revertirRegistro(Request $request)
    {
        $id = $request->input('id_remesa');
        
        DB::beginTransaction();
        try {
            $remesa = Remesa::find($id);

            if (!$remesa) {
                return response()->json(['success' => false, 'message' => 'La remesa no existe.'], 404);
            }

            if ($remesa->sw_det_semana == 1 || $remesa->sw_det_semana == 2) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Acción denegada: La remesa ya tiene semanas registradas. Debe eliminar el detalle de semanas antes de revertir el estado principal.'
                ], 403); // Usamos 403 (Prohibido) o 422 para errores de lógica de negocio
            }

            // 1. Obtener el tipo de Iglesia (Filial, Iglesia o Grupo)
            $iglesiaInfo = DB::table('generas')
                ->join('iglesias', 'generas.id_iglesia', '=', 'iglesias.id_iglesia')
                ->where('generas.id_remesa', $id)
                ->select('iglesias.tipo')
                ->first();

            if (!$iglesiaInfo) {
                return response()->json(['success' => false, 'message' => 'No se encontró información de la iglesia asociada.'], 404);
            }

            $nuevoEstado = "PENDIENTE";

            // 2. Lógica de validación según tipo
            if ($iglesiaInfo->tipo == 'Iglesia' || $iglesiaInfo->tipo == 'Grupo') {
                $montoIglesia = DB::table('remesas_iglesias')
                    ->where('id_remesa', $id)
                    ->value('monto');

                if ($montoIglesia > 0) {
                    $nuevoEstado = "REGISTRADO EN ACMS";
                }
            } else { 
                // Caso Filial
                $filialInfo = DB::table('remesas_filiales')
                    ->where('id_remesa', $id)
                    ->select('fondo_local', 'monto_remesa', 'gasto')
                    ->first();

                if ($filialInfo) {
                    if ($filialInfo->fondo_local > 0 || $filialInfo->monto_remesa > 0 || $filialInfo->gasto > 0) {
                        return response()->json([
                            'success' => false, 
                            'message' => 'No se puede revertir: Existen montos (Fondo/Remesa/Gasto) registrados para esta filial.'
                        ], 200); // Usamos 200 con success false para manejarlo como aviso
                    }
                }
            }

            // 3. Resetear Atributos
            $remesa->cierre = false;
            $remesa->deposito = false;
            $remesa->documentacion = false;
            $remesa->escaneado = false;
            $remesa->fecha_entrega = null;
            $remesa->estado_dias = "0";
            $remesa->observacion = null;
            $remesa->estado = $nuevoEstado;

            $remesa->save();

            // 4. Resetear Puntualidad en la tabla meses si existe
            // Necesitas el id_puntualidad asociado a la iglesia de esta remesa
            // (Opcional, dependiendo de si quieres borrar la estrella del reporte anual)

            DB::commit();
            return response()->json([
                'success' => true, 
                'message' => 'El registro ha sido restablecido a ' . $nuevoEstado
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }


    //muestra los datos de la iglesia y las remesas estados de los ultimos 12 remesas
    public function index_remesa_iglesia(Request $request, $id_iglesia) 
    {
        $anio = $request->query('anio'); // o $request->anio
        $mes = $request->query('mes');
        $iglesias = DB::table('iglesias')
                ->where('estado', true)
                ->select('id_iglesia','codigo', 'nombre')
                ->get();
        
        // 1. Validar la iglesia (esto se mantiene igual)
        $iglesia = Iglesia::where('id_iglesia', $id_iglesia)->where('estado', true)->first();
        if (!$iglesia) {
            return response()->json(['error' => 'No encontrada'], 404);
        }

        $dato = DB::table('iglesias as xi')
            ->leftJoin('distritos as xd', 'xi.distrito_id', '=', 'xd.id_distrito')
            ->leftJoin('personas as xres', 'xd.id_responsable_remesa', '=', 'xres.id_persona')
            ->leftJoin('personas as xpas', 'xd.id_pastor', '=', 'xpas.id_persona')
            ->select([
                'xd.nombre as nombre_distrito',
                DB::raw("xres.nombre ||' '|| xres.ape_paterno ||' '|| xres.ape_materno as nombre_resp"),
                DB::raw("xpas.nombre ||' '|| xpas.ape_paterno ||' '|| xpas.ape_materno as nombre_pas")
            ])
            ->where('xi.id_iglesia', $id_iglesia)
            ->first();
        // 2. Si la petición es AJAX, mandamos los datos
        if ($request->ajax()) {

            if (trim(strtoupper($iglesia->tipo)) === 'FILIAL') {
                $resultados = DB::select("
                    select xg.*, xr.*, xri.*
                    from generas xg
                    join remesas xr on xg.id_remesa = xr.id_remesa
                    join remesas_filiales xri on xg.id_remesa = xri.id_remesa
                    where xg.id_iglesia = ?
                    order by xg.anio DESC, xg.mes DESC
                    limit 12
                ", [$id_iglesia]);
            }else{
                $resultados = DB::select("
                    select xg.*, xr.*, xri.*
                    from generas xg
                    join remesas xr on xg.id_remesa = xr.id_remesa
                    join remesas_iglesias xri on xg.id_remesa = xri.id_remesa
                    where xg.id_iglesia = ?
                    order by xg.anio DESC, xg.mes DESC
                    limit 12
                ", [$id_iglesia]);
            }

            $nombreMes = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];

            foreach ($resultados as $r) {
                $r->nombre_mes = $nombreMes[$r->mes] ?? 'Desconocido';
            }
            //dd($resultados);

            return response()->json(['data' => $resultados]);
        }

        if (trim(strtoupper($iglesia->tipo)) === 'FILIAL') {
            return view('remesas_llenado.index_filial', compact('iglesia', 'dato', 'mes', 'anio', 'iglesias'));    
        }else{
            return view('remesas_llenado.index_iglesia', compact('iglesia', 'dato', 'mes', 'anio', 'iglesias'));
        }  
    }
     /**********************************************************************************
     * FUNCIONES DE FILIALES
     ***********************************************************************************/
    public function llenar_filial(Request $request) //permision 'ver remesas filiales - remesas',
    {
        //dd($request);
        $idIglesia = $request->id_iglesia;
        $anio = $request->anio;
        $mes = $request->mes;
        $distrito = $request->distrito;
        // Validar entrada
        $iglesia = Iglesia::where('id_iglesia', $idIglesia)
        ->where('estado', true)
        ->whereRaw('UPPER(tipo) = ?', ['FILIAL'])
        ->first();

        if (!$iglesia) {
            return back()->withErrors('La iglesia no existe, no está activa o no es de tipo FILIAL.');
        }

        $iglesias = DB::select("
            SELECT xi.*
            FROM iglesias xi
            WHERE xi.id_iglesia = ?
        ", [$idIglesia]);

        // ✅ Tomar solo el primer registro (si existe)
        $iglesia = $iglesias[0] ?? null;

         $resultados = DB::select("
            select xg.*,xr.*, xrf.*
            from generas xg
            join remesas xr on xg.id_remesa = xr.id_remesa
            join remesas_filiales xrf on xg.id_remesa = xrf.id_remesa
            where xg.id_iglesia = ?
            and xg.anio = ?
        ", [$idIglesia, $anio]);
        $nombreMes = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
            4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
            10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        // Recorremos cada resultado y agregamos el campo calculado
        foreach ($resultados as $r) {
            $r->nombre_mes = $nombreMes[$r->mes] ?? 'Desconocido';
        }
        return view('remesafiliales.index_unico', compact('resultados', 'distrito', 'iglesia', 'anio', 'mes'));
    }


    public function registrar_remesa_filial(Request $request, $id){ //permision 'llenar remesas filiales - remesas',
        DB::beginTransaction();
        //dd($request);
        try {
            $mes = $request->mes;
            $anio = $request->anio;

            $remesa = Remesa::find($id);
            $remesa_filial = RemesaFilial::find($id);

            $remesa->cierre         = $request->boolean('cierre');
            $remesa->deposito       = $request->boolean('deposito');
            $remesa->documentacion  = $request->boolean('documentacion');
            $remesa->escaneado      = $request->boolean('escaneado');
            $remesa->fecha_entrega  = $request->fecha_entrega;
            $remesa->estado         = 'ENTREGADO';
            $remesa->observacion    = $request->observacion;
            

            // 🔹 Calcular diferencia de días
            $fechaEntrega = Carbon::parse($request->fecha_entrega);
            $fechaLimite = Carbon::parse($remesa->fecha_limite);

            $entregaSoloFecha = $fechaEntrega->copy()->startOfDay();
            $limiteSoloFecha = $fechaLimite->copy()->startOfDay();

            $diferencia = $entregaSoloFecha->diffInDays($limiteSoloFecha, false);

            if ($diferencia === 0) {
                $remesa->estado_dias = 'Completado con 0 días de retraso (entrega puntual)';
            } elseif ($diferencia > 0) {
                $remesa->estado_dias = "Completado con {$diferencia} día(s) de adelanto";
            } else {
                $remesa->estado_dias = "Entregado con " . abs($diferencia) . " día(s) de retraso";
            }
            $remesa->save();   

            //guardar datos de remesa_filial
            $remesa_filial->ofrenda         = $request->ofrenda;
            $remesa_filial->diezmo          = $request->diezmo;
            $remesa_filial->pro_templo      = $request->pro_templo;
            $remesa_filial->fondo_local     = $request->fondo_local;
            $remesa_filial->monto_remesa    = $request->monto_remesa;
            $remesa_filial->gasto           = $request->gasto;

            $remesa_filial->save();
            
            // 4. LOGICA DE SALDOS (Efecto Dominó)
            $this->recalcularSaldosDesde($request->id_iglesia, $request->anio, $request->mes);
            /******************************************/
            //  LLENAMOS LA PUNTUALIDAD
            /******************************************/
            $iglesia = Genera::where('id_remesa', $remesa->id_remesa)
                 ->with('iglesia')
                 ->first()?->iglesia;

                if ($iglesia) {
                    $lugar = strtoupper($iglesia->lugar ?? ''); // Normalizar el texto por seguridad
                    $estrella = "0"; // Valor por defecto

                    // Calcular la estrella según el tipo de iglesia
                    if ($lugar === 'EL ALTO') {
                        // Si entregó antes o el mismo día → 2
                        if ($diferencia >= 0) {
                            $estrella = "2";
                        }
                        // Si entregó con hasta 2 días de retraso → 1
                        elseif ($diferencia < 0 && abs($diferencia) <= 2) {
                            $estrella = "1";
                        }
                        // Más de 2 días de retraso → 0
                        else {
                            $estrella = "0";
                        }
                    } 
                    elseif ($lugar === 'ALTIPLANO') {
                        // Hasta 5 días de retraso → 2, sino → 0
                        if ($diferencia >= 0 || abs($diferencia) <= 5) {
                            $estrella = "2";
                        } else {
                            $estrella = "0";
                        }
                    }
                    // buscamos la puntualidad
                    $puntualidad = Puntualidad::where('id_iglesia', $iglesia->id_iglesia)
                          ->where('anio', now()->year)
                          ->first();

                    // 🔹 Guardar estrella en la remesa
                    Mes::where('id_puntualidad', $puntualidad->id_puntualidad)
                    ->where('mes', $mes)
                    ->update(['tipo' => $estrella]);

                }
            
            DB::commit();
            // Crear un nuevo request con los datos que llenar_filial necesita
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Remesa y saldos actualizados correctamente.']);
            }

            $nuevoRequest = new Request($request->only(['id_iglesia', 'anio', 'mes', 'distrito']));
            return (new RemesaController())->llenar_filial($nuevoRequest);

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }

    
    public function obtenerHistorialFondo($id)
    {
        $iglesia = DB::table('iglesias')->where('id_iglesia', $id)->first();
        $saldoTotalActual = $iglesia->fondo_local ?? 0;

        $historial = DB::table('generas as xg')
            ->join('remesas_filiales as xf', 'xg.id_remesa', '=', 'xf.id_remesa')
            ->where('xg.id_iglesia', $id)
            ->orderBy('xg.anio', 'desc')
            ->orderBy('xg.mes', 'desc')
            ->limit(4)
            ->select('xg.mes', 'xg.anio', 'xf.fondo_l_anterior', 'xf.fondo_local', 'xf.gasto', 'xf.fondo_l_final')
            ->get();

        // Mapeo de meses a nombres cortos
        $mesesCortos = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
        ];

        $formateado = $historial->map(function($item) use ($mesesCortos) {
            return [
                'mes' => $mesesCortos[$item->mes] ?? $item->mes,
                'anterior' => number_format($item->fondo_l_anterior, 2),
                'ingreso'  => number_format($item->fondo_local, 2),
                'gasto'    => number_format($item->gasto, 2),
                'final'    => number_format($item->fondo_l_final, 2)
            ];
        });


        return response()->json([
            'saldo_actual' => number_format($saldoTotalActual, 2, '.', ','),
            'raw_saldo'    => $saldoTotalActual,
            'historial'    => $formateado
        ]);
        
    }

    public function recalcularTodo2026()
    {
        // 1. Obtener todas las iglesias que son de tipo Filial
        // Usamos pluck para tener solo una lista de IDs
        $idsFiliales = DB::table('iglesias')
            ->where('tipo', 'Filial')
            ->where('estado', true)
            ->pluck('id_iglesia');

        $total = count($idsFiliales);
        $procesados = 0;

        // 2. Iniciar una transacción global para seguridad (opcional, pero recomendado)
        DB::beginTransaction();

        try {
            foreach ($idsFiliales as $id) {
                // Llamamos a tu función verificada para cada iglesia
                // Iniciando en el año 2026, mes 1 (Enero)
                $this->recalcularSaldosDesde($id, 2026, 1);
                $procesados++;
            }

            DB::commit();
            return response()->json([
                'res' => true,
                'message' => "Se recalcularon con éxito $procesados de $total filiales desde Enero 2026."
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'res' => false,
                'message' => "Error en el proceso: " . $e->getMessage()
            ], 500);
        }
    }

     /**********************************************************************************/

    /*public function registrar_remesa_iglesia(Request $request, $id){ //permision 'registra remesas filiales- remesas',
        //dd($id, $request);
        DB::beginTransaction();
        $mes = $request->input('mes');
        $anio = $request->input('anio');
        try {
            $remesa = Remesa::find($id);
            if (!$remesa->id_remesa) {  // <-- Aquí estás usando $iglesia pero no está definido
                return redirect()->back()->with('info', 'Esta remesa no existe.');
            }

            $remesa->cierre = $request->cierre;
            $remesa->deposito = $request->deposito;
            $remesa->documentacion = $request->documentacion;
            $remesa->fecha_entrega = $request->fecha_entrega;
            $remesa->estado = 'ENTREGADO';
            $remesa->observacion = $request->observacion;



            $fechaEntrega = Carbon::parse($request->fecha_entrega);
            $fechaLimite = Carbon::parse($remesa->fecha_limite);

            // Forzamos tres métodos distintos para comparar
            $diff1 = $fechaEntrega->diffInDays($fechaLimite, false);
            $diff2 = $fechaEntrega->diffInCalendarDays($fechaLimite, false);
            $diff3 = (int) $fechaEntrega->copy()->startOfDay()->diffInDays($fechaLimite->copy()->startOfDay(), false);

            return response()->json([
                'success' => true,
                'debug' => [
                    'con_diffInDays' => $diff1,
                    'con_diffInCalendarDays' => $diff2,
                    'forzado_entero' => $diff3,
                    'tipo_de_dato' => gettype($diff1)
                ]
            ]);


            // 🔹 Calcular diferencia de días
            $soloFechaEntrega = Carbon::parse($request->fecha_entrega)->toDateString(); // "2026-04-22"
            $soloFechaLimite = Carbon::parse($remesa->fecha_limite)->toDateString();   // "2026-04-20"

            $diferencia = (int) Carbon::parse($soloFechaEntrega->toDateString())
                ->diffInDays($soloFechaLimite->toDateString(), false);
            
            dump($diferencia, $soloFechaEntrega, $soloFechaLimite);
            if ($diferencia === 0) {
                $remesa->estado_dias = 'Completado con 0 días de retraso (entrega puntual)';
            } elseif ($diferencia > 0) {
                $remesa->estado_dias = "Completado con {$diferencia} día(s) de adelanto";
            } else {
                $remesa->estado_dias = "Entregado con " . abs($diferencia) . " día(s) de retraso";
            }
            $remesa->save();
            // empesamos la asignacion de punti va hasat comiit

            $iglesia = Genera::where('id_remesa', $remesa->id_remesa)
                 ->with('iglesia')
                 ->first()?->iglesia;

                if ($iglesia) {
                    $lugar = strtoupper($iglesia->lugar ?? ''); // Normalizar el texto por seguridad
                    $estrella = "0"; // Valor por defecto

                    // Calcular la estrella según el tipo de iglesia
                    if ($lugar === 'EL ALTO') {
                        // Si entregó antes o el mismo día → 2
                        if ($diferencia >= 0) {
                            $estrella = "2";
                        }
                        // Si entregó con hasta 2 días de retraso → 1
                        elseif ($diferencia < 0 && abs($diferencia) <= 2) {
                            $estrella = "1";
                        }
                        // Más de 2 días de retraso → 0
                        else {
                            $estrella = "0";
                        }
                    } 
                    elseif ($lugar === 'ALTIPLANO') {
                        // Hasta 5 días de retraso → 2, sino → 0
                        if ($diferencia >= 0 || abs($diferencia) <= 5) {
                            $estrella = "2";
                        } else {
                            $estrella = "0";
                        }
                    }
                    // buscamos la puntualidad
                    $puntualidad = Puntualidad::where('id_iglesia', $iglesia->id_iglesia)
                          ->where('anio', now()->year)
                          ->first();

                    // 🔹 Guardar estrella en la remesa
                    Mes::where('id_puntualidad', $puntualidad->id_puntualidad)
                    ->where('mes', $mes)
                    ->update(['tipo' => $estrella]);

                }


            
            DB::commit();
            return response()->json([
                'success' => true, 
                'message' => 'Remesa registrada correctamente en el sistema.'
            ]);

            return redirect()->route('remesas.index_mes', ['mes' => $mes, 'anio' => $anio])->with('success', 'Registro Correcto');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }*/
    
    public function asignar_puntualidad(){

        // 🔹 Buscar la iglesia asociada a la remesa --- PARA ASIGNAR PUNTUAIDAD
            $iglesia = Genera::where('id_remesa', $remesa->id)->with('iglesia')->first()?->iglesia;

                if ($iglesia) {
                    $lugar = strtoupper($iglesia->lugar ?? ''); // Normalizar el texto por seguridad
                    $estrella = 0; // Valor por defecto

                    // 🔹 Calcular la estrella según el tipo de iglesia
                    if ($lugar === 'EL ALTO') {
                        // Si entregó antes o el mismo día → 2
                        if ($diferencia >= 0) {
                            $estrella = 2;
                        }
                        // Si entregó con hasta 2 días de retraso → 1
                        elseif ($diferencia < 0 && abs($diferencia) <= 2) {
                            $estrella = 1;
                        }
                        // Más de 2 días de retraso → 0
                        else {
                            $estrella = 0;
                        }
                    } 
                    elseif ($lugar === 'ALTIPLANO') {
                        // Hasta 5 días de retraso → 2, sino → 0
                        if ($diferencia >= 0 || abs($diferencia) <= 5) {
                            $estrella = 2;
                        } else {
                            $estrella = 0;
                        }
                    }
                    // buscamos la puntualidad
                    $puntualidad = Puntualidad::where('id_iglesia', $iglesia->id_iglesia)
                          ->where('anio', now()->year)
                          ->first();

                    // 🔹 Guardar estrella en la remesa
                    $mes = Mes::where('id_puntualidad', $puntualidad->id_puntualidad)
                        ->where('mes', $numero_mes)
                        ->first();

                    if ($mes) {
                        $mes->tipo = '2';
                        $mes->save();
                    }

                    $remesa->estrella = $estrella;
                }
    }

    /**________________________________________________EXPORTACIONES______________________________________ */
    public function exportRemesaMensualPDF($anio, $mes)
    {
        // VALIDAR PARÁMETROS
        if ($mes < 1 || $mes > 12) {
            abort(404, "Mes inválido");
        }

        // CONSULTA SQL
        $result = DB::select("
            SELECT 
                xi.codigo,
                xi.nombre,
                xi.tipo,
                d.nombre AS distrito,
                xr.cierre,
                xr.deposito,
                xr.documentacion,
                xr.fecha_entrega,
                xr.estado_dias,
                xr.estado
            FROM generas xg
            JOIN iglesias xi ON xg.id_iglesia = xi.id_iglesia
            JOIN distritos d ON d.id_distrito = xi.distrito_id
            JOIN remesas xr ON xr.id_remesa = xg.id_remesa
            WHERE xg.anio = ?
            AND xg.mes = ?
            ORDER BY d.nombre ASC
        ", [$anio, $mes]);

        // Hora y fecha
        $fecha = now()->format('d/m/Y');
        $hora  = now()->format('H:i:s');

        // Nombre del mes
        $mesNombre = [
            1=>"Enero",2=>"Febrero",3=>"Marzo",4=>"Abril",
            5=>"Mayo",6=>"Junio",7=>"Julio",8=>"Agosto",
            9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre"
        ][$mes];

        // GENERAR PDF
        $pdf = \PDF::loadView('pdf.remesas_mensual', [
            'result' => $result,
            'anio' => $anio,
            'mes' => $mesNombre,
            'fecha' => $fecha,
            'hora' => $hora
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("Reporte_Remesas_{$mesNombre}_{$anio}.pdf");
    }

}
