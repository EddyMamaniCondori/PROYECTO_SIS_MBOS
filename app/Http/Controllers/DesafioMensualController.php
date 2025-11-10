<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pastor; 
use App\Models\Iglesia;
use App\Models\Desafio; 
use App\Models\Distrito; 
use App\Models\Mensual; 
use App\Models\AnualIglesia; 
use Illuminate\Support\Facades\DB;
use App\Http\Requests\DesafioMensualRequest;
use Exception;

class DesafioMensualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
     public function index() 
    {
        try {
            $anioActual = now()->year;

            // Obtener el año configurado en la tabla distritos
            $anioDistritos = DB::table('distritos')
                ->where('estado', true)
                ->value('año');
            
            // Si no hay distritos activos
            if (!$anioDistritos) {
                return view('desafio.index_bautizo', [
                    'desafios' => collect([]),
                    'anioActual' => $anioActual,
                    'mensaje' => 'No hay distritos activos en el sistema.',
                    'ultimoMes' => null,
                    'siguienteMes' => 1
                ]);
            }
            
            // Determinar qué año usar
            $anio = ($anioDistritos < $anioActual) ? $anioDistritos : $anioActual;
            
            // Obtener todos los desafíos mensuales del año
            $mensuales = Mensual::select('mensuales.mes', 'mensuales.anio', 'mensuales.fecha_limite')
                ->distinct()
                ->join('desafios as xd', 'mensuales.id_desafio', '=', 'xd.id_desafio')
                ->where('mensuales.anio', $anio)
                ->orderBy('mensuales.mes', 'asc')
                ->get();
            
            // Determinar el último mes creado y el siguiente mes a crear
            $ultimoMes = null;
            $siguienteMes = 1; // Por defecto, enero
            
            if ($mensuales->isNotEmpty()) {
                // Obtener el mes más alto (último creado)
                $ultimoMes = $mensuales->max('mes');
                
                // Calcular el siguiente mes
                if ($ultimoMes < 12) {
                    $siguienteMes = $ultimoMes + 1;
                } else {
                    // Si ya llegamos a diciembre (12), el siguiente sería enero del próximo año
                    $siguienteMes = 13; // O podrías manejarlo de otra forma
                }
            }
            
            return view('desafio_mensuales.index_mes', compact('mensuales', 'anio', 'siguienteMes'));
            
        } catch (\Exception $e) {
            \Log::error('Error en index de DesafioMensualController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al cargar los desafíos mensuales.');
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DesafioMensualRequest $request) ///CREA UN MES DESAFIO PARA TODOS LOS DESAFIOS
    {
        try {
            // Los datos ya vienen validados
            $validated = $request->validated();

            // 1. Buscar todos los desafíos  del año
            $desafios = Desafio::where('anio', $validated['anio'])
                ->get();

            // Verificar si existen desafíos para ese año
            if ($desafios->isEmpty()) {
                return redirect()->back()->with('error', 'No existen desafíos para el año ' . $validated['anio']);
            }

            // Verificar si ya existe un mensual para este mes y año
            $existeMensual = Mensual::where('mes', $validated['mes'])
                ->where('anio', $validated['anio'])
                ->exists();

            if ($existeMensual) {
                return redirect()->back()->with('error', 'Ya existen registros mensuales para el mes ' . $validated['mes'] . ' del año ' . $validated['anio']);
            }

            $registrosCreados = 0;

            // 2. Recorrer cada desafío y crear un registro mensual
            foreach ($desafios as $desafio) {
                Mensual::create([
                    'mes' => $validated['mes'],
                    'anio' => $validated['anio'],
                    'fecha_limite' => $validated['fecha_limite'],
                    'id_desafio' => $desafio->id_desafio
                ]);
                
                $registrosCreados++;
            }

            return redirect()->back()->with('success', "Se crearon exitosamente {$registrosCreados} registros mensuales para el mes {$validated['mes']} del año {$validated['anio']}");

        } catch (\Exception $e) {
            \Log::error('Error en store de DesafioMensualController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al crear los registros mensuales: ' . $e->getMessage());
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
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(DesafioMensualRequest $request, string $id)
    {
        try {
            // Los datos ya vienen validados
            $validated = $request->validated();

            // 1. Buscar todos los mensuales que coincidan con el mes y año del request
            $mensuales = Mensual::where('mes', $validated['mes'])
                ->where('anio', $validated['anio'])
                ->get();

            // Verificar si existen registros
            if ($mensuales->isEmpty()) {
                return redirect()->back()->with('error', 'No se encontraron registros mensuales para el mes ' . $validated['mes'] . ' del año ' . $validated['anio']);
            }

            $registrosActualizados = 0;

            // 2. Recorrer todos los mensuales encontrados y actualizar solo la fecha_limite
            foreach ($mensuales as $mensual) {
                $mensual->update([
                    'fecha_limite' => $validated['fecha_limite']
                ]);
                
                $registrosActualizados++;
            }

            return redirect()->back()->with('success', "Se actualizó la fecha límite en {$registrosActualizados} registros mensuales.");

        } catch (\Exception $e) {
            \Log::error('Error en update de DesafioMensualController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar los registros mensuales: ' . $e->getMessage());
        }
    }


    public function update_desafios(Request $request, $id)
    {
        //dd($request, $id);
        try {
            DB::beginTransaction();

            // Validación simple
            $request->validate([
                'desafio_visitas' => 'required|integer|min:0',
            ]);

            // Buscar el registro mensual
            $mensual = Mensual::findOrFail($id);

            // Actualizar el campo
            $mensual->update([
                'desafio_visitas' => $request->desafio_visitas,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Desafío mensual actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar el desafío mensual: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
