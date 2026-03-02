<?php

namespace App\Http\Controllers;

use App\Models\VisitaCapellan;
use Illuminate\Http\Request;
use App\Models\Desafio; 
use App\Models\Mensual;
use App\Http\Requests\VisitaCapellanRequest;
use App\Http\Requests\UpdateVisitaCapellanRequest;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;


class VisitaCapellanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() //muestra todas las visitas del año actual
    {
        $anioActual = now()->year; //muestro los estudiantes del año actual
        $persona = Auth::user(); 
        $ue = DB::table('capellan')
                ->select('id_ue')
                ->where('id_pastor', $persona->id_persona)
                ->first();
        if (!$ue) {
            return redirect()->route('panel')->with('success', 'No tienes un ue asignado.');
        }
        $id_ue = $ue->id_ue;

        $visitas = DB::table('visita_capellans as xv')
                    ->select('xv.*', 'xue.nombre')
                    ->leftJoin('unidad_educativas as xue', 'xv.id_ue', '=', 'xue.id_ue')
                    ->where('xv.id_pastor', $persona->id_persona)
                    ->where('xv.id_ue', $id_ue)
                    ->whereRaw("to_char(xv.fecha_visita, 'YYYY') = ?", [$anioActual])
                    ->get();
        return view('visitas_capellanes.index', compact('visitas', 'anioActual'));
    }

    public function index_mes() //obtenemos los meses desafiados de la UE //permision 'ver meses - visitas',
    {
        $anioActual = now()->year; //muestro los estudiantes del año actual
        $persona = Auth::user(); 
        $ue = DB::table('capellan')
                ->select('id_ue')
                ->where('id_pastor', $persona->id_persona)
                ->first();
        if (!$ue) {
            return redirect()->route('panel')->with('success', 'No tienes un ue asignado.');
        }
        $id_ue = $ue->id_ue;
        //dd($id_ue, $anioActual, $persona->id_persona);
        $desafio = Desafio::where('anio', $anioActual)
                    ->where('id_ue', $id_ue)
                    ->where('id_pastor', $persona->id_persona)
                    ->first();
        if (!$desafio) {
            return back()->with('error', 'No se encontró un desafío para la UE. seleccionado.');
        }
        $visitas = Mensual::where('id_desafio', $desafio->id_desafio)->get();
        return view('visitas_capellanes.index_mes', compact('visitas', 'anioActual'));
    }

    public function index_llenar_visitas_mes($id) 
    {
        $mensual = Mensual::find($id);
        if (!$mensual) {
            return redirect()->back()->with('error', 'Desafío mensual no encontrado.');
        }
        $anioActual = now()->year;
        $persona = Auth::user(); 
        $ue = DB::table('capellan')
                ->select('id_ue')
                ->where('id_pastor', $persona->id_persona)
                ->first();
        if (!$ue) {
            return redirect()->route('panel')->with('success', 'No tienes un ue asignado.');
        }
        $id_ue = $ue->id_ue;
        // Obtener visitas del mes específico del desafío
        $visitas = VisitaCapellan::whereYear('fecha_visita', $anioActual)
            ->whereMonth('fecha_visita', $mensual->mes)
            ->where('id_pastor', $persona->id_persona)
            ->where('id_ue', $id_ue)
            ->get();

        return view('visitas_capellanes.index_visitas_mensuales', compact('visitas', 'mensual', 'anioActual'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create_v($id_mensual)
    {
        $persona = Auth::user(); 

        $ue = DB::table('capellan')
                ->select('id_ue')
                ->where('id_pastor', $persona->id_persona)
                ->first();
        if (!$ue) {
            return redirect()->route('panel')->with('success', 'No tienes un ue asignado.');
        }
        $id_ue = $ue->id_ue;

        $mensual = Mensual::find($id_mensual);
        if (!$mensual) {
            return redirect()->back()->with('error', 'Desafío mensual no encontrado.');
        }
        return view('visitas_capellanes.create', compact( 'mensual', 'id_ue'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VisitaCapellanRequest $request)
    {
        //dd($request);
        try {
            //dd($request->validated());
            DB::beginTransaction();
            $persona = Auth::user(); 
            $datos = array_merge($request->validated(), [
                'id_pastor' => $persona->id_persona,
            ]);
            $visita = VisitaCapellan::create($datos); // se crea el registro 

            $id_mensual = $request->id_mensual; // asumiendo que viene en el request
            $mensual = Mensual::find($id_mensual);

            // Incrementar visitas_alcanzadas
            $mensual->increment('visitas_alcanzadas');
            
            DB::commit();
            return redirect()->route('visita_cape.llenar_mes', $mensual->id_mensual)->with('success','Visita registrado Correctamente');

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear la visita: ' . $e->getMessage()], 500);
        }  
    }

    /**
     * Display the specified resource.
     */
    public function show(VisitaCapellan $visitaCapellan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_v($id_mensual, $id_visita)
    {
        $persona = Auth::user(); 

        $ue = DB::table('capellan')
                ->select('id_ue')
                ->where('id_pastor', $persona->id_persona)
                ->first();
        if (!$ue) {
            return redirect()->route('panel')->with('success', 'No tienes un ue asignado.');
        }
        $id_ue = $ue->id_ue;

        $mensual = Mensual::find($id_mensual);
        if (!$mensual) {
            return redirect()->back()->with('error', 'Desafío mensual no encontrado.');
        }

        $visita = VisitaCapellan::find($id_visita);
        return view('visitas_capellanes.edit', compact('visita', 'mensual', 'id_ue'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVisitaCapellanRequest $request, string $id)
    {
        //dd($request);
        try {
            DB::beginTransaction();
            $visita = VisitaCapellan::findOrFail($id);
            $visita->update($request->validated());
            $id_mensual = $request->id_mensual;
            DB::commit();
            return redirect()->route('visita_cape.llenar_mes', $id_mensual)->with('success','Visita registrado Correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_v($id_mensual, $id_visita_cape)
    {
        //dd($id_mensual, $id_visita);
        try {
            DB::beginTransaction();
            
            $visita = VisitaCapellan::find($id_visita_cape);
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
}
