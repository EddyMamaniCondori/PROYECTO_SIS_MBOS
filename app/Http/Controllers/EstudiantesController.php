<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\EstudianteBiblico; 
use App\Models\Iglesia; 
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Requests\EstudianteRequest;
use App\Http\Requests\UpdateEstudianteRequest;

class EstudiantesController extends Controller
{
    /**
     * Display a listing of the resource. //TODO HECHO YA PARA LE DISTRITO 11
     */
    public function index()
    {
        // 1️⃣ Definir el año actual y el distrito
        $anioActual = now()->year;
        $id_distrito = 11; // todos los estudiantes del distrito Bolivar

        // 2️⃣ Consultar estudiantes bíblicos con los filtros
        $estudiantes = EstudianteBiblico::join('iglesias as xi', 'estudiante_biblicos.id_iglesia', '=', 'xi.id_iglesia')
            ->select(
                'estudiante_biblicos.*',
                'xi.nombre as nombre_iglesia'
            )
            ->whereYear('estudiante_biblicos.fecha_registro', $anioActual) // mismo año
            ->where('xi.estado', true) // iglesias activas
            ->where('xi.distrito_id', $id_distrito) // solo distrito 11
            ->get();

        // 3️⃣ Enviar datos a la vista
        return view('estudiantes.index', compact('estudiantes', 'anioActual'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Definimos el distrito actual
        $id_distrito = 11;

        // Obtenemos solo las iglesias activas del distrito 11
        $iglesias = Iglesia::where('estado', true)
            ->where('distrito_id', $id_distrito)
            ->orderBy('nombre') // opcional: para que salgan ordenadas alfabéticamente
            ->get();

        // Retornamos la vista con los datos
        return view('estudiantes.create', compact('iglesias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EstudianteRequest $request)
    {
        try {
            DB::beginTransaction();

            // Obtenemos la fecha actual, por ejemplo con Carbon:
            $fechaHoy = \Carbon\Carbon::now(); // puedes usar también now() si lo tienes importado
            
            // Creamos el registro incluyendo la fecha_registro
            $estudiante = EstudianteBiblico::create(array_merge(
                $request->validated(),
                ['fecha_registro' => $fechaHoy]
            ));
            
            DB::commit();
            return redirect()->route('estudiantes.index')->with('success', 'Estudiante creado correctamente.');

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el estudiante biblico: ' . $e->getMessage()], 500);
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
        $id_distrito = 11;
        $estudiante = EstudianteBiblico::find($id);
        $iglesias = Iglesia::where('estado', true)
            ->where('distrito_id', $id_distrito)
            ->orderBy('nombre') // opcional: para que salgan ordenadas alfabéticamente
            ->get();
        return view('estudiantes.edit', compact('estudiante','iglesias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstudianteRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $estudiante = EstudianteBiblico::findOrFail($id);
            $estudiante->update($request->validated());

            DB::commit();
            return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado correctamente.');
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
            $estudiante = EstudianteBiblico::find($id);

            if (!$estudiante) {
                return redirect()->route('estudiantes.index')
                    ->with('error', 'Estudiante no encontrado');
            }

            $estudiante->delete();

            DB::commit();
            return redirect()->route('estudiantes.index')->with('success', 'Estudiante Eliminado Correctamente');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('estudiantes.index')
                ->with('error', 'Error al Eliminar al Estudiante: ' . $e->getMessage());
        }
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
