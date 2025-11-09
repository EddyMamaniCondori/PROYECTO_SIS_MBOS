<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstructorBiblico;
use App\Models\Iglesia;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Requests\InstructorRequest;
use App\Http\Requests\UpdateInstructorRequest;
class InstructoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anioActual = now()->year; //muestro los estudiantes del año actual
        $id_distrito = 11; // todos los estudiantes del distrito Bolivar

        $instructores = InstructorBiblico::join('iglesias as xi', 'instructor_biblicos.id_iglesia', '=', 'xi.id_iglesia')
            ->select(
                'instructor_biblicos.*',
                'xi.nombre as nombre_iglesia' // alias para evitar conflicto con nombre del instructor
            )
            ->whereYear('instructor_biblicos.fecha_registro', $anioActual) // mismo año
            ->where('xi.estado', true) // iglesias activas
            ->where('xi.distrito_id', $id_distrito) // solo distrito 11
            ->get();

        return view('instructores.index', compact('instructores','anioActual'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $id_distrito = 11;

        // Obtenemos solo las iglesias activas del distrito 11
        $iglesias = Iglesia::where('estado', true)
            ->where('distrito_id', $id_distrito)
            ->orderBy('nombre') // opcional: para que salgan ordenadas alfabéticamente
            ->get();

        return view('instructores.create', compact('iglesias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InstructorRequest $request)
    {
        try {
            DB::beginTransaction();
            $fechaHoy = \Carbon\Carbon::now(); // puedes usar también now() si lo tienes importado
            
            $estudiante = InstructorBiblico::create(array_merge(
                $request->validated(),
                ['fecha_registro' => $fechaHoy]
            )); // se crea el registro 
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
        $id_distrito = 11;
        $instructor = InstructorBiblico::find($id);
        $iglesias = Iglesia::where('estado', true)
            ->where('distrito_id', $id_distrito)
            ->orderBy('nombre') // opcional: para que salgan ordenadas alfabéticamente
            ->get();
        return view('instructores.edit', compact('instructor','iglesias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstructorRequest $request, string $id)
    {
         try {
            DB::beginTransaction();  

            $instructor = InstructorBiblico::find($id);
            $instructor->update($request->validated());
            DB::commit();
            return redirect()->route('instructores.index')->with('success', 'Instructor actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            // Buscar estudiante, si no existe lanzar excepción o manejar error
            $instructor = InstructorBiblico::find($id);

            if (!$instructor) {
                return redirect()->route('instructores.index')
                    ->with('error', 'Instructor no encontrado');
            }

            $instructor->delete();

            DB::commit();
            return redirect()->route('instructores.index')->with('success', 'Instructor Eliminado Correctamente');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('instructores.index')
                ->with('error', 'Error al Eliminar: ' . $e->getMessage());
        }
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
