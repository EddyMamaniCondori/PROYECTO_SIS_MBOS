<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstructorBiblico;
use App\Models\Iglesia;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Requests\InstructorRequest;

class InstructoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instructores = InstructorBiblico::join('iglesias as xi', 'instructor_biblicos.iglesia_id', '=', 'xi.id_iglesia')
            ->select(
                'instructor_biblicos.*',
                'xi.nombre as nombre_iglesia' // alias para evitar conflicto con nombre del instructor
            )
            ->get();

        return view('instructores.index', ['instructores' => $instructores]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $iglesias = Iglesia::all();
        return view('instructores.create', compact('iglesias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InstructorRequest $request)
    {
        try {
            DB::beginTransaction();
            $estudiante = InstructorBiblico::create($request->validated()); // se crea el registro 
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el instructor biblico: ' . $e->getMessage()], 500);
        }

        return redirect()->route('instructores.index')->with('success','Instructor registrado Correctamente');
    
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
                ->select('mes', 'desafio_inst_biblicos', 'instructores_alcanzados')
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
        $desafios = $result->pluck('desafio_inst_biblicos'); // [28,48,40,...]
        $alcanzados = $result->pluck('instructores_alcanzados'); // [65,59,80,...]

        return view('instructores.dashboard', compact('meses','desafios','alcanzados'));
    }
}
