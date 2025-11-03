<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Iglesia;
use App\Models\Remesa;
use App\Models\RemesaFilial;
use App\Models\RemesaIglesia;
use Carbon\Carbon;
class RemesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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

    public function index_mes($mes, $anio)
    {
         $resultados = DB::select("
        SELECT 
                xd.nombre AS nombre_distrito,
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
    
    public function crear(Request $request)
    {
        $mes = $request->mes;
        $anio = $request->anio;
        $fecha_limite = $request->fecha_limite;
        DB::beginTransaction();
        try {
            // Solo iglesias activas (estado = true)
            $iglesias = DB::table('iglesias')->where('estado', true)->get();

            foreach ($iglesias as $iglesia) {
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
            }

            DB::commit();
            return redirect()->back()->with('success', 'Remesas y gastos generados correctamente para todas las iglesias.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al generar remesas: ' . $e->getMessage());
        }
    }
    /**
     * Store a newly created resource in storage.
     */
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



    public function llenar_filial(Request $request)
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
    public function registrar_remesa_filial(Request $request, $id){
        DB::beginTransaction();
        try {
            $remesa = Remesa::findOrFail($id);
            $remesa_filial = RemesaFilial::findOrFail($id);

            if (!$remesa->id_remesa) {  // <-- Aquí estás usando $iglesia pero no está definido
                return redirect()->back()->with('info', 'Esta remesa no existe.');
            }

            $remesa->cierre = $request->cierre;
            $remesa->deposito = $request->deposito;
            $remesa->documentacion = $request->documentacion;
            $remesa->fecha_entrega = $request->fecha_entrega;
            $remesa->estado = 'ENTREGADO';
            $remesa->observacion = $request->observacion;
            $remesa->save();
            
            $remesa_filial->ofrenda = $request->ofrenda;
            $remesa_filial->diezmo = $request->diezmo;
            $remesa_filial->pro_templo = $request->pro_templo;
            $remesa_filial->fondo_local = $request->fondo_local;
            $remesa_filial->monto_remesa = $request->monto_remesa;
            $remesa_filial->save();
            DB::commit();

            return redirect()->back()->with('success', 'Remesa registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar la remesa: ' . $e->getMessage());
        }
    }
}
