<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\DesafioEvento; 
use App\Models\AsignaDesafio;
use App\Models\Desafio;
use App\Http\Requests\DesafioEventoRequest;
class DesafioEventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eventos = DesafioEvento::where('estado', true)
                    ->get();
        
        return view('desafio_eventos.index',['desafio_eventos'=>$eventos]);
    }
    public function index_eliminado()
    {
        $eventos = DesafioEvento::where('estado', false)
                    ->get();
        
        return view('desafio_eventos.indexdelete',['desafio_eventos'=>$eventos]);
    }

    public function index_asignaciones()
    {
        $eventos = DesafioEvento::where('estado', true)
                    ->get();
        return view('desafio_eventos.index_asignaciones',['desafio_eventos'=>$eventos]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('desafio_eventos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DesafioEventoRequest $request)
    {
        try {
            DB::beginTransaction();
            $evento = DesafioEvento::create($request->validated());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el Desafio: ' . $e->getMessage()], 500);
        }
        return redirect()->route('desafio_eventos.index')->with('success','Evento registrado Correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $desafio = DesafioEvento::findOrFail($id);
            return view('desafio_eventos.edit', compact('desafio'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar el evento: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DesafioEventoRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            
            $desafio = DesafioEvento::findOrFail($id);
            $desafio->update($request->validated());
            
            DB::commit();
            return redirect()->route('desafio_eventos.index')->with('success', 'Evento actualizado correctamente.');
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
            $desafio = DesafioEvento::findOrFail($id);
            $desafio->estado = false;
            $desafio->save();
            DB::commit();
            
            return redirect()->route('desafio_eventos.index')
                ->with('success', 'Evento desactivado correctamente');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al desactivar el evento: ' . $e->getMessage());
        }
    }


    public function mostrar_asignaciones_evento($id)
    {
        $desafio = DesafioEvento::findOrFail($id);
        // Consulta uniendo todas las tablas relacionadas
        $asignaciones = DB::table('asigna_desafios as xad')
            ->join('desafio_eventos as xde', 'xad.id_desafio_evento', '=', 'xde.id_desafio_evento')
            ->join('desafios as xd', 'xd.id_desafio', '=', 'xad.id_desafio')
            ->join('distritos as xdd', 'xd.id_distrito', '=', 'xdd.id_distrito')
            ->select(
                'xad.id_asigna_desafio',
                'xde.nombre as nombre_evento',
                'xd.anio as anio_desafio',
                'xd.id_distrito',
                'xdd.nombre as nombre_distrito',
                'xad.desafio',
                'xad.alcanzado'
            )
            ->where('xad.id_desafio_evento', $desafio->id_desafio_evento)
            ->get();

        // Retornar los datos a la vista
        return view('desafio_eventos.mostrar_asignaciones', compact('asignaciones', 'desafio'));
    }

    public function asignar_evento_distrito(string $id)
    {
        try {
            DB::beginTransaction();
            // Validar que el desafío evento existe
            $desafioEvento = DesafioEvento::findOrFail($id);
            
            // Validar que el evento esté activo
            if (!$desafioEvento->estado) {
                return redirect()
                    ->back()
                    ->with('error', 'No se pueden asignar desafíos a un evento inactivo.');
            }
            
            // Obtener el año del evento
            $anioEvento = $desafioEvento->anio;
            
            // Obtener todos los desafíos del mismo año del evento
            $desafiosDelAnio = Desafio::where('anio', $anioEvento)
                ->get();
            
            // Validar que existan desafíos para ese año
            if ($desafiosDelAnio->isEmpty()) {
                return redirect()
                    ->back()
                    ->with('error', "No hay desafíos activos para el año {$anioEvento}.");
            }
            
            // Obtener los IDs de desafíos que ya fueron asignados al desafio_evento
            $desafiosYaAsignados = AsignaDesafio::where('id_desafio_evento', $desafioEvento->id_desafio_evento)
                ->pluck('id_desafio')
                ->toArray();
            
            // Filtrar solo los desafíos que NO están asignados
            $desafiosPorAsignar = $desafiosDelAnio->whereNotIn('id_desafio', $desafiosYaAsignados);
            
            // Si no hay nuevos desafíos por asignar
            if ($desafiosPorAsignar->isEmpty()) {
                return redirect()
                    ->back()
                    ->with('success', 'Todos los desafíos ya están asignados a este evento.');
            }
            
            // Crear solo los registros que faltan
            $registrosCreados = 0;
            foreach ($desafiosPorAsignar as $desafio) {
                AsignaDesafio::create([
                    'id_desafio' => $desafio->id_desafio,
                    'id_desafio_evento' => $desafioEvento->id_desafio_evento,
                ]);
                $registrosCreados++;
            }
            
            DB::commit();
            
            $totalAsignados = count($desafiosYaAsignados) + $registrosCreados;
            
            return redirect()
                ->back()
                ->with('success', "Se asignaron {$registrosCreados} nuevos desafíos. Total: {$totalAsignados} desafíos en el evento '{$desafioEvento->nombre}'.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error al asignar los desafíos: ' . $e->getMessage());
        }
    }

    public function reactive(string $id)
    {
        try {
            DB::beginTransaction();
            $desafio = DesafioEvento::findOrFail($id);
            $desafio->estado = true;
            $desafio->save();
            DB::commit();
            
            return redirect()->route('desafio_eventos.index')
                ->with('success', 'Evento reactivado correctamente');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al reactivar el evento: ' . $e->getMessage());
        }
    }

   public function update_asignacion_desafio(Request $request, string $id)
    {   
        //dd($request, $id);
        // Validar que el campo 'desafio' venga y sea entero
        $validated = $request->validate([
            'desafio' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $asigna = AsignaDesafio::findOrFail($id);

            // Actualizar campo
            $asigna->desafio = $validated['desafio'];
            $asigna->save();

            DB::commit();

            return redirect()
                ->route('desafio_eventos.mostrar_asignaciones', $asigna->id_desafio_evento)
                ->with('success', 'Desafío actualizado correctamente.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'No se encontró la asignación solicitada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

}
