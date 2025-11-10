<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iglesia; 
use App\Models\Visita; 
use App\Models\Desafio; 
use App\Models\Mensual;
use App\Http\Requests\VisitaRequest;
use App\Http\Requests\UpdateVisitasRequest;
use Illuminate\Support\Facades\DB;
use Exception;


class VisitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() //PUEDES VER TODAS TUS VISITAS DE ESE
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

    public function index_mes() //obtenemos los meses desafiados al distrito 11
    {
        $anioActual = now()->year; //muestro los estudiantes del año actual
        $id_distrito = 11; // todos los estudiantes del distrito Bolivar
        $desafio = Desafio::where('anio', $anioActual)
                    ->where('id_distrito', $id_distrito)
                    ->first();
        if (!$desafio) {
            return back()->with('error', 'No se encontró un desafío para el distrito seleccionado.');
        }
        $visitas = Mensual::where('id_desafio', $desafio->id_desafio)->get();
        return view('visitas.index_mes', compact('visitas', 'anioActual'));
    }

    public function index_llenar_visitas_mes($id)
    {
        $mensual = Mensual::find($id);
        if (!$mensual) {
            return redirect()->back()->with('error', 'Desafío mensual no encontrado.');
        }
        $anioActual = now()->year;
        $id_distrito = 11;
        
        // Obtener visitas del mes específico del desafío
        $visitas = Visita::join('iglesias as xi', 'visitas.id_iglesia', '=', 'xi.id_iglesia')
            ->select(
                'visitas.*',
                'xi.nombre as nombre_iglesia'
            )
            ->whereYear('visitas.fecha_visita', $anioActual) // Año actual
            ->whereMonth('visitas.fecha_visita', $mensual->mes) // Mes específico del desafío
            ->where('xi.estado', true) // Iglesias activas
            ->where('xi.distrito_id', $id_distrito) // Solo distrito 11
            ->get();

        return view('visitas.index_visitas_mensuales', compact('visitas', 'mensual', 'anioActual'));
    }
             // todas las visitas asociadas al desafío
    /**
     * Show the form for creating a new resource.
     */
    public function create( $id_mensual)
    {
        $id_distrito = 11;

        $mensual = Mensual::find($id_mensual);
        if (!$mensual) {
            return redirect()->back()->with('error', 'Desafío mensual no encontrado.');
        }

        $iglesias = Iglesia::where('estado', true)
            ->where('distrito_id', $id_distrito)
            ->orderBy('nombre') // opcional: para que salgan ordenadas alfabéticamente
            ->get();

        return view('visitas.create', compact('iglesias', 'mensual'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VisitaRequest $request)
    {
        try {
            //dd($request->validated());
            DB::beginTransaction();
            $visita = Visita::create($request->validated()); // se crea el registro 

            $id_mensual = $request->id_mensual; // asumiendo que viene en el request
            $mensual = Mensual::find($id_mensual);

            // Incrementar visitas_alcanzadas
            $mensual->increment('visitas_alcanzadas');
            
            DB::commit();
            return redirect()->route('visitas.llenar_mes', $mensual->id_mensual)->with('success','Visita registrado Correctamente');

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear la visita: ' . $e->getMessage()], 500);

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
    public function edit($id_mensual, $id_visita)
    {
        $id_distrito = 11;

        $mensual = Mensual::find($id_mensual);
        if (!$mensual) {
            return redirect()->back()->with('error', 'Desafío mensual no encontrado.');
        }

        $visita = Visita::find($id_visita);

        $iglesias = Iglesia::where('estado', true)
            ->where('distrito_id', $id_distrito)
            ->orderBy('nombre') // opcional: para que salgan ordenadas alfabéticamente
            ->get();

        return view('visitas.edit', compact('visita','iglesias', 'mensual'));
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
            $id_mensual = $request->id_mensual;
            DB::commit();
            return redirect()->route('visitas.llenar_mes', $id_mensual)->with('success','Visita registrado Correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_mensual, $id_visita)
    {
        //dd($id_mensual, $id_visita);
        try {
            DB::beginTransaction();
            
            $visita = Visita::find($id_visita);
            $mensual = Mensual::find($id_mensual);
            if (!$visita) {
                return redirect()->back()->with('error', 'Visita no encontrada');
            }
            if (!$mensual) {
                return redirect()->back()->with('error', 'Desafío mensual no encontrado');
            }

            $visita->delete();
            if ($mensual->visitas_alcanzadas > 0) {
                $mensual->decrement('visitas_alcanzadas');
            }
            DB::commit();
            return redirect()->back()->with('success', 'Visita eliminada correctamente');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al eliminar la visita: ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        $anioActual = now()->year;
        $id_distrito = 11;

        // Obtener el desafío anual del distrito
        $desafio = Desafio::where('anio', $anioActual)
            ->where('id_distrito', $id_distrito)
            ->first();

        if (!$desafio) {
            return redirect()->back()->with('error', 'Desafío anual no encontrado.');
        }

        // Obtener los registros mensuales relacionados
        $mensuales = Mensual::where('id_desafio', $desafio->id_desafio)
            ->orderBy('mes') // importante para que los meses estén en orden
            ->get();
        // 🔹 OPCIONAL: mostrar solo los meses hasta el actual
        $mensuales = $mensuales->filter(fn($m) => $m->mes <= now()->month);
        // Preparar los datos para el gráfico
        $meses = [];
        $desafios = [];
        $alcanzados = [];

        // Mapa para traducir número de mes → nombre
        $nombresMeses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        foreach ($mensuales as $m) {
            $meses[] = $nombresMeses[$m->mes] ?? 'Desconocido';
            $desafios[] = (int) $m->desafio_visitas;
            $alcanzados[] = (int) $m->visitas_alcanzadas;
        }

        // Enviar datos a la vista
        return view('visitas.dashboard', compact('meses', 'desafios', 'alcanzados'));
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
