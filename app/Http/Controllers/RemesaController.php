<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;
class RemesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meses = DB::select("
            SELECT DISTINCT xg.mes, xg.anio,
                CASE xg.mes
                    WHEN 'Enero' THEN 1
                    WHEN 'Febrero' THEN 2
                    WHEN 'Marzo' THEN 3
                    WHEN 'Abril' THEN 4
                    WHEN 'Mayo' THEN 5
                    WHEN 'Junio' THEN 6
                    WHEN 'Julio' THEN 7
                    WHEN 'Agosto' THEN 8
                    WHEN 'Septiembre' THEN 9
                    WHEN 'Octubre' THEN 10
                    WHEN 'Noviembre' THEN 11
                    WHEN 'Diciembre' THEN 12
                END AS orden_mes
            FROM genera xg
            JOIN remesas xr ON xg.id_remesa = xr.id_remesa
            WHERE xg.mes = xr.mes
            AND xg.anio = xr.anio
            ORDER BY orden_mes
        ");

        $ultimo = collect($meses)->sortByDesc('orden_mes')->first();
        //dd($ultimo);

        return view('remesas.index', [
            'meses' => $meses,
            'ultimo' => $ultimo
        ]);
    }

    public function index_mes($mes, $anio)
    {
         $resultados = DB::select("
        SELECT 
                xd.nombre AS nombre_distrito,
                xp.nombre AS nombre_pas,
                xp.ape_paterno,
                xp.ape_materno,
                xi.nombre AS nombre_igle,
                xi.tipo AS tipo_igle,
                xi.lugar AS lugar_igle,
                xr.*
            FROM genera xg
            JOIN iglesias xi ON xg.id_iglesia = xi.id_iglesia
            JOIN distritos xd ON xi.distrito_id = xd.id_distrito
            JOIN remesas xr ON xg.id_remesa = xr.id_remesa
            JOIN personas xp ON xd.id_pastor = xp.id_persona
            WHERE xg.mes = :mes
            AND xg.anio = :anio
            AND xr.mes = :mes
            AND xr.anio = :anio
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
    
    public function generarRemesasYGastos($mes, $anio)
    {
        DB::transaction(function() use ($mes, $anio) {

            // Obtener todas las iglesias
            //dd($mes, $anio);
            $iglesias = DB::table('iglesias')->get();

            foreach($iglesias as $iglesia) {

                // 1️⃣ Crear Remesa
                $id_remesa = DB::table('remesas')->insertGetId([
                            'mes' => $mes,
                            'anio' => $anio,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ], 'id_remesa'); // <- aquí le indicas el nombre correcto de la PK


                // 2️⃣ Crear Gasto (puedes ajustar los valores según tu estructura)
                $id_gasto = DB::table('gastos')->insertGetId([
                        'mes' => $mes,
                        'anio' => $anio,
                        'monto' => 0,
                        'observacion' => 'Gasto inicial',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ], 'id_gasto');


                // 3️⃣ Llenar tabla genera
                DB::table('genera')->insert([
                    'id_iglesia' => $iglesia->id_iglesia,
                    'id_remesa' => $id_remesa,
                    'id_gasto' => $id_gasto,
                    'mes' => $mes,
                    'anio' => $anio,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        });

        return redirect()->back()->with('success', 'Remesas y gastos generados correctamente para todas las iglesias.');
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
}
