<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iglesia; 
use App\Models\Visita; 
use App\Http\Requests\VisitaRequest;
use App\Http\Requests\UpdateVisitasRequest;
use Illuminate\Support\Facades\DB;
use Exception;


class VisitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anioActual = now()->year; //muestro los estudiantes del año actual

        $id_distrito = 11; // todos los estudiantes del distrito Bolivar

        $visitas = Visita::join('iglesias as xi', 'visitas.id_iglesia', '=', 'xi.id_iglesia')
                ->select(
                    'visitas.*',
                    'xi.nombre as nombre_iglesia'
                )
                ->whereYear('visitas.fecha_visita', $anioActual) // mismo año
                ->where('xi.estado', true) // iglesias activas
                ->where('xi.distrito_id', $id_distrito) // solo distrito 11
                ->get();
        return view('visitas.index', compact('visitas', 'anioActual'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $id_distrito = 11;

        $iglesias = Iglesia::where('estado', true)
            ->where('distrito_id', $id_distrito)
            ->orderBy('nombre') // opcional: para que salgan ordenadas alfabéticamente
            ->get();

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
        $id_distrito = 11;
        $visita = Visita::find($id);
        $iglesias = Iglesia::where('estado', true)
            ->where('distrito_id', $id_distrito)
            ->orderBy('nombre') // opcional: para que salgan ordenadas alfabéticamente
            ->get();
        return view('visitas.edit', compact('visita','iglesias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVisitasRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $visita = Visita::findOrFail($id);
            $visita->update($request->validated());
            DB::commit();
            return redirect()->route('visitas.index')->with('success', 'Visita actualizado correctamente.');
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
            $visita = Visita::find($id);

            if (!$visita) {
                return redirect()->route('visitas.index')
                    ->with('error', 'Visita no encontrado');
            }

            $visita->delete();

            DB::commit();
            return redirect()->route('visitas.index')->with('success', 'Visita Eliminado Correctamente');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('visitas.index')
                ->with('error', 'Error al Eliminar la visita: ' . $e->getMessage());
        }
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









    public function index_asignacion_desafios()
    {
        $anioActual = now()->year; //muestro los estudiantes del año actual

        $id_distrito = 11; // todos los estudiantes del distrito Bolivar

        $visitas = Visita::join('iglesias as xi', 'visitas.id_iglesia', '=', 'xi.id_iglesia')
                ->select(
                    'visitas.*',
                    'xi.nombre as nombre_iglesia'
                )
                ->whereYear('visitas.fecha_visita', $anioActual) // mismo año
                ->where('xi.estado', true) // iglesias activas
                ->where('xi.distrito_id', $id_distrito) // solo distrito 11
                ->get();
        return view('visitas.index', compact('visitas', 'anioActual'));
    }

}
