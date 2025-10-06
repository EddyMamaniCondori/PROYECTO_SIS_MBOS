<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\EstudianteBiblico; 
use App\Models\Iglesia; 
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Requests\EstudianteRequest;


class EstudiantesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estudiantes = EstudianteBiblico::join('iglesias as xi', 'estudiante_biblicos.iglesia_id', '=', 'xi.id_iglesia')
                ->select(
                    'estudiante_biblicos.*',
                    'xi.nombre as nombre_iglesia' // alias para que no choque con bautisos.nombre
                )
                ->get();
        return view('estudiantes.index', ['estudiantes' => $estudiantes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $iglesias = Iglesia::all();
        return view('estudiantes.create', compact('iglesias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EstudianteRequest $request)
    {
        try {
            DB::beginTransaction();
            $estudiante = EstudianteBiblico::create($request->validated()); // se crea el registro 
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el estudiante biblico: ' . $e->getMessage()], 500);
        }

        return redirect()->route('estudiantes.index')->with('success','Estudiante registrado Correctamente');
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
                ->select('mes', 'desafios_est_biblicos', 'estudiantes_alcanzados')
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
        $desafios = $result->pluck('desafios_est_biblicos'); // [28,48,40,...]
        $alcanzados = $result->pluck('estudiantes_alcanzados'); // [65,59,80,...]

        return view('estudiantes.dashboard', compact('meses','desafios','alcanzados'));
    }
}
