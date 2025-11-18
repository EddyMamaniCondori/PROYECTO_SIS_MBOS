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
     * 
     * 'ver-desafios eventos',
      *      'ver-eliminados-desafios eventos',
      *      'crear-desafios eventos',
      *      'editar-desafios eventos',
       *     'eliminar-desafios eventos',
       *     'reactivar-desafios eventos',
       * 
        *        'ver-asignacion desafios eventos',
       *     'ver desafio-asignacion desafios eventos',
       *     'asignar evento a distrito-asignacion desafios eventos',
       *     'actualizar desafios-asignacion desafios eventos',
     * Display a listing of the resource.
     */


    function __construct()
    {
        // index(): permision ver desafios eventos
        $this->middleware('permission:ver-desafios eventos', ['only' => ['index']]);
        // index_eliminado(): permision ver eliminados desafios eventos
        $this->middleware('permission:ver-eliminados-desafios eventos', ['only' => ['index_eliminado']]);
        
        // Nota: El comentario de la función dice 'ver asignaciones-desafios eventos', que no está en tu lista inicial, así que usé 'ver-desafios eventos' como un permiso general de lectura.
        // create() y store(): permision crear desafio eventos
        $this->middleware('permission:crear-desafios eventos', ['only' => ['create', 'store']]);
        // edit() y update(): permision editar desafio eventos
        $this->middleware('permission:editar-desafios eventos', ['only' => ['edit', 'update']]);
        // destroy(): permision eliminar desafio eventos (desactiva el registro)
        $this->middleware('permission:eliminar-desafios eventos', ['only' => ['destroy']]);
        // reactive(): permision reactivar-desafios eventos
        $this->middleware('permission:reactivar-desafios eventos', ['only' => ['reactive']]);
        
        // index_asignaciones(): Asumo que tiene un permiso relacionado con 'ver' la gestión de asignaciones, pero como no lo pasaste explícitamente en la lista, usaré el permiso relacionado con la vista de asignaciones.
        // Usaremos el permiso más cercano para la visualización: 'ver-desafios eventos' | 'ver desafio - asignacion desafios eventos'
        $this->middleware('permission:ver-asignacion desafios eventos', ['only' => ['index_asignaciones']]); 
        // mostrar_asignaciones_evento(): permision 'ver desafio - asignacion desafios eventos'
        $this->middleware('permission:ver desafio-asignacion desafios eventos', ['only' => ['mostrar_asignaciones_evento']]);
        // asignar_evento_distrito(): permision 'asignar evento a distrito - asignacion desafios eventos'
        $this->middleware('permission:asignar evento a distrito-asignacion desafios eventos', ['only' => ['asignar_evento_distrito']]);
        // update_asignacion_desafio(): permisoin 'actualizar desafios - asignacion desafios eventos'
        $this->middleware('permission:actualizar desafios-asignacion desafios eventos', ['only' => ['update_asignacion_desafio']]);
        $this->middleware('permission:ver-dashboards desafios eventos|ver por evento-dashboards desafios eventos', ['only' => ['index_dasboards_eventos']]);
     $this->middleware('permission:ver por evento-dashboards desafios eventos', ['only' => ['dashboard_bautizos_evento']]);
    }


    public function index() //permision ver desafios eventos
    {
        $eventos = DesafioEvento::where('estado', true)
                    ->get();
        
        return view('desafio_eventos.index',['desafio_eventos'=>$eventos]);
    }
    public function index_eliminado() //permision ver eliminados desafios eventos
    {
        $eventos = DesafioEvento::where('estado', false)
                    ->get();
        
        return view('desafio_eventos.indexdelete',['desafio_eventos'=>$eventos]);
    }

    public function index_asignaciones()//permision 'ver asignaciones-desafios eventos'
    {
        $eventos = DesafioEvento::where('estado', true)
                    ->get();
        return view('desafio_eventos.index_asignaciones',['desafio_eventos'=>$eventos]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create() //permision crear desafio eventos
    {
        return view('desafio_eventos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DesafioEventoRequest $request) //permision crear desafio eventos
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
    public function edit(string $id) //permision editar desafio eventos
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
    public function update(DesafioEventoRequest $request, string $id)//permision editar desafio eventos
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
    public function destroy(string $id)  //permision eliminar desafio eventos
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

//entrando a un evento muestra los distritos que estan asignados a este evento

    public function mostrar_asignaciones_evento($id) //permision 'ver desafio - asignacion desafios eventos',
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

        // asigna el evento seleecinado atodos lños distritos y los actualiza 
    public function asignar_evento_distrito(string $id) //permision  'asignar evento a distrito - asignacion desafios eventos',
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

    public function reactive(string $id) //permision 'reactivar-desafios eventos',
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

    public function update_asignacion_desafio(Request $request, string $id) //permisoin 'actualizar desafios - asignacion desafios eventos', 
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

    public function index_dasboards_eventos()//permision ver-dashboards desafios eventos
    {
        $eventos = DesafioEvento::where('estado', true)
                    ->get();
        return view('desafio_eventos.index_dashboard_eventos',['desafio_eventos'=>$eventos]);
    }

    public function dashboard_bautizos_evento($id) //permision ver por evento-dashboards desafios eventos
    {
        $evento = DesafioEvento::findOrFail($id);
        $resultados = DB::table('asigna_desafios as xad')
            ->join('desafio_eventos as xde', 'xad.id_desafio_evento', '=', 'xde.id_desafio_evento')
            ->join('desafios as xd', 'xd.id_desafio', '=', 'xad.id_desafio')
            ->join('distritos as xdd', 'xd.id_distrito', '=', 'xdd.id_distrito')
            ->select(
                'xdd.id_distrito',
                'xdd.nombre',
                DB::raw('SUM(xad.desafio) AS total_desafio'),
                DB::raw('SUM(xad.alcanzado) AS total_alcanzado')
            )
            ->where('xad.id_desafio_evento', $evento->id_desafio_evento)
            ->groupBy('xdd.id_distrito', 'xdd.nombre')
            ->get();

        // Generar dataset para gráficos
        $graficos = $resultados->map(function ($d) {
            return [
                'id_distrito' => $d->id_distrito,
                'nombre' => $d->nombre,
                'desafio' => (int)$d->total_desafio,
                'alcanzado' => (int)$d->total_alcanzado,
                'diferencia' => (int)($d->total_desafio - $d->total_alcanzado),
            ];
        });

        // Totales globales
        $totales = [
            'desafio' => $graficos->sum('desafio'),
            'alcanzado' => $graficos->sum('alcanzado'),
        ];
        $totales['diferencia'] = $totales['desafio'] - $totales['alcanzado'];
        // Porcentaje general
        $porcentajeGeneral = $totales['desafio'] > 0
            ? round(($totales['alcanzado'] / $totales['desafio']) * 100, 2)
            : 0;

        return view('desafio_eventos.dashboard_x_evento_mbos', compact(
            'evento',
            'resultados',
            'graficos',
            'totales',
            'porcentajeGeneral'
        ));
    }

    
}
