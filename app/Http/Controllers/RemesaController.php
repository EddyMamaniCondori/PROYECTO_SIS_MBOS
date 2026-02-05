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
use App\Http\Controllers\RemesaController;
class RemesaController extends Controller
{
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
                xr.*
            FROM generas xg
            left JOIN iglesias xi ON xg.id_iglesia = xi.id_iglesia
            left JOIN distritos xd ON xi.distrito_id = xd.id_distrito
            left JOIN remesas xr ON xg.id_remesa = xr.id_remesa
            left JOIN personas xp ON xd.id_pastor = xp.id_persona
            WHERE xg.mes = :mes
            AND xg.anio = :anio
            AND xi.estado = true
            order by nombre_distrito
        ", [
            'mes' => $mes,
            'anio' => $anio
        ]);
        return view('remesas.index_mes', [
            'datos' => $resultados,
            'mes' => $mes,
            'anio' => $anio
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    //habilita un mes y crea para todas las igelsias activas su remesa del correspondientes mes y año
    public function crear(Request $request)  //permission 'crear meses - remesas',
    {
        //dd($request);
        $mes = $request->mes;
        $anio = $request->anio;
        $fecha_limite = $request->fecha_limite;
        //DB::beginTransaction();
        //try {
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

            //DB::commit();
           return redirect()->route('remesas.index')->with('success', 'Remesas y gastos generados correctamente para todas las iglesias.');


        /*} catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al generar remesas: ' . $e->getMessage());
        }*/
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

    public function actualizar_iglesias($mes, $anio, $fecha_limite)
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

    public function store(Request $request)
    {
        //
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

//muestra la vista de cada filiala

    public function llenar_filial(Request $request) //permision 'ver remesas filiales - remesas',
    {
        $idIglesia = $request->id_iglesia;
        $anio = $request->anio;
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
        return view('remesafiliales.index_unico', compact('resultados', 'distrito', 'iglesia'));
    }


    public function registrar_remesa_filial(Request $request, $id){ //permision 'llenar remesas filiales - remesas',
        DB::beginTransaction();
        try {
            $remesa = Remesa::find($id);
            $remesa_filial = RemesaFilial::find($id);

            if (!$remesa->id_remesa) {  // <-- Aquí estás usando $iglesia pero no está definido
                return redirect()->back()->with('info', 'Esta remesa no existe.');
            }

            $remesa->cierre = $request->cierre;
            $remesa->deposito = $request->deposito;
            $remesa->documentacion = $request->documentacion;
            $remesa->fecha_entrega = $request->fecha_entrega;
            $remesa->estado = 'ENTREGADO';
            $remesa->observacion = $request->observacion;

            // 🔹 Calcular diferencia de días
            $fechaEntrega = Carbon::parse($request->fecha_entrega);
            $fechaLimite = Carbon::parse($remesa->fecha_limite);
            $diferencia = $fechaEntrega->diffInDays($fechaLimite, false); 
            // false = diferencia negativa si entrega después de la fecha límite

            if ($diferencia === 0) {
                $remesa->estado_dias = 'Completado con 0 días de retraso (entrega puntual)';
            } elseif ($diferencia > 0) {
                $remesa->estado_dias = "Completado con {$diferencia} día(s) de adelanto";
            } else {
                $remesa->estado_dias = "Entregado con " . abs($diferencia) . " día(s) de retraso";
            }
            $remesa->save();            
            //guardar datos de remesa_filial
            $remesa_filial->ofrenda = $request->ofrenda;
            $remesa_filial->diezmo = $request->diezmo;
            $remesa_filial->pro_templo = $request->pro_templo;
            $remesa_filial->fondo_local = $request->fondo_local;
            $remesa_filial->monto_remesa = $request->monto_remesa;
            $remesa_filial->save();
            DB::commit();

            // Crear un nuevo request con los datos que llenar_filial necesita
            $nuevoRequest = new Request([
                'id_iglesia' => $request->id_iglesia,
                'anio' => 2026,
                'distrito' => $request->distrito,
            ]);
            // Instanciar el controlador
            $controller = new RemesaController();
            // Llamar al método normalmente
            return $controller->llenar_filial($nuevoRequest);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar la remesa: ' . $e->getMessage());
        }
    }

    public function registrar_remesa_iglesia(Request $request, $id){ //permision 'registra remesas filiales- remesas',
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

            // 🔹 Calcular diferencia de días
            $fechaEntrega = Carbon::parse($request->fecha_entrega);
            $fechaLimite = Carbon::parse($remesa->fecha_limite);

            $diferencia = $fechaEntrega->diffInDays($fechaLimite, false); 
            // false = diferencia negativa si entrega después de la fecha límite

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


            return redirect()->route('remesas.index_mes', ['mes' => $mes, 'anio' => $anio])->with('success', 'Registro Correcto');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar la remesa: ' . $e->getMessage());
        }
    }
    
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
            ORDER BY xi.nombre ASC
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
