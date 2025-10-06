<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iglesia; 
use App\Models\Visita; 
use App\Http\Requests\VisitaRequest;

use Illuminate\Support\Facades\DB;
use Exception;


class VisitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visitas = Visita::join('iglesias as xi', 'visitas.iglesia_id', '=', 'xi.id_iglesia')
                ->join('personas as xp', 'visitas.pastor_id', '=', 'xp.id_persona')
                ->select(
                    'visitas.*',
                    'xp.nombre as nombre_p',
                    'xp.ape_paterno as ape_paterno_p',
                    'xp.ape_materno as ape_materno_p',
                    'xi.nombre as nombre_iglesia'
                )
                ->get();
        return view('visitas.index', ['visitas' => $visitas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $iglesias = Iglesia::all();
        return view('visitas.create', compact('iglesias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VisitaRequest $request)
    {
        try {
            DB::beginTransaction();
            $visita = Visita::create($request->validated()); // se crea el registro 
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear la visita: ' . $e->getMessage()], 500);

        }

        return redirect()->route('visitas.index')->with('success','Visita registrado Correctamente');
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

    public function dashboard()
    {
        $result = DB::table('desafio_mensuales')
                ->select('mes', 'desafio_visitacion', 'visitas_alcanzadas')
                ->where('iglesia_id', 1)
                ->where('pastor_id', 1)
                ->where('anio', 2025)
                ->orderByRaw("
                    CASE mes
                        WHEN 'enero' THEN 1
                        WHEN 'febrero' THEN 2
                        WHEN 'marzo' THEN 3
                        WHEN 'abril' THEN 4
                        WHEN 'mayo' THEN 5
                        WHEN 'junio' THEN 6
                        WHEN 'julio' THEN 7
                        WHEN 'agosto' THEN 8
                        WHEN 'septiembre' THEN 9
                        WHEN 'octubre' THEN 10
                        WHEN 'noviembre' THEN 11
                        WHEN 'diciembre' THEN 12
                    END
                ")
                ->get();


        // Convertimos a arrays separados
        $meses = $result->pluck('mes');                 // ['enero','febrero',...]
        $desafios = $result->pluck('desafio_visitacion'); // [28,48,40,...]
        $alcanzados = $result->pluck('visitas_alcanzadas'); // [65,59,80,...]
        //dd($meses, $desafios, $alcanzados);
        return view('visitas.dashboard', compact('meses','desafios','alcanzados'));
    }
}
