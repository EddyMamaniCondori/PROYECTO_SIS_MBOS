<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pastor; 
use App\Models\Iglesia;
use App\Models\DesafioMensual; 
use App\Models\Distrito; 
use App\Http\Requests\DesafioMensualRequest;

use Illuminate\Support\Facades\DB;
use Exception;

class DesafioMensualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $desafios = DesafioMensual::join('iglesias as xi', 'desafio_mensuales.iglesia_id', '=', 'xi.id_iglesia')
                ->join('personas as xp', 'desafio_mensuales.pastor_id', '=', 'xp.id_persona')
                ->select(
                    'desafio_mensuales.*',
                    'xi.nombre as nombre_iglesia',
                    'xp.nombre as nombre_p',
                    'xp.ape_paterno as ape_paterno_p',
                    'xp.ape_materno as ape_materno_p'
                )
                ->get();

            return view('desafio_mensual.index', compact('desafios'));
    }


    public function index_mes()
    {
        $desafios = DesafioMensual::select('mes', 'anio')
            ->distinct()
            ->orderBy('anio', 'desc')
            ->get();


        //dd($desafios);
        return view('desafio_mensual.index_mes', compact('desafios'));
    }


    public function show_mes($mes, $anio)
    {
        $distritos = Distrito::join('pastors', 'distritos.id_pastor', '=', 'pastors.id_pastor')
                     ->join('personas', 'pastors.id_pastor', '=', 'personas.id_persona')
                     ->leftJoin('grupos', 'grupos.id_grupo', '=', 'distritos.id_grupo')
                     ->select(
                         'distritos.*',
                         'personas.nombre as nombre_pastor',
                         'personas.ape_paterno as ape_paterno_pastor',
                         'personas.ape_materno as ape_materno_pastor',
                         'grupos.nombre as dist_nombre'
                     )
                     ->get();
        //dd($desafios);
        return view('desafio_mensual.index_asignaciones', compact('distritos','mes','anio'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $iglesias = Iglesia::all();
        $pastores = Pastor::PastorDC();
        return view('desafio_mensual.create', compact('iglesias', 'pastores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DesafioMensualRequest $request)
    {
        try {
            DB::beginTransaction();
            $pastor = DesafioMensual::create($request->validated()); // se crea el registro 
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el Desafio Mensual: ' . $e->getMessage()], 500);
        }

        return redirect()->route('desafios.index')->with('success','Desafio Mensual  registrado Correctamente');
    }

    public function storeMes(Request $request)
    {
        // Validación
        $request->validate([
            'mes'  => 'required|string',
            'anio' => 'required|integer|min:2020|max:' . (date('Y') + 1),
        ], [
            'mes.required'  => 'Debe seleccionar un mes.',
            'anio.required' => 'Debe ingresar un año.',
            'anio.integer'  => 'El año debe ser un número.',
            'anio.min'      => 'El año debe ser mayor o igual a 2020.',
            'anio.max'      => 'El año no puede ser mayor al próximo año.',
        ]);

        // Obtenemos todas las iglesias junto con su distrito (y pastor del distrito)
        $iglesias = Iglesia::join('distritos as xd', 'iglesias.distrito_id', '=', 'xd.id_distrito')
            ->select('iglesias.id_iglesia', 'iglesias.nombre', 'iglesias.distrito_id', 'xd.id_pastor')
            ->get();

        $contador = 0;

        foreach ($iglesias as $iglesia) {
            // Verificar si ya existe ese desafío para esa iglesia
            $existe = DesafioMensual::where('mes', $request->mes)
                ->where('anio', $request->anio)
                ->where('iglesia_id', $iglesia->id_iglesia)
                ->exists();

            if (!$existe) {
                DesafioMensual::create([
                    'mes' => $request->mes,
                    'anio' => $request->anio,
                    'iglesia_id' => $iglesia->id_iglesia,
                    'pastor_id' => $iglesia->id_pastor,
                    'desafio_visitacion' => 0,
                    'desafio_bautiso' => 0,
                    'desafio_inst_biblicos' => 0,
                    'desafios_est_biblicos' => 0,
                    'visitas_alcanzadas' => 0,
                    'bautisos_alcanzados' => 0,
                    'instructores_alcanzados' => 0,
                    'estudiantes_alcanzados' => 0,
                ]);

                $contador++;
            }
        }

        if ($contador > 0) {
            return redirect()->route('desafios.mes')
                ->with('success', "Se crearon $contador desafíos mensuales para el mes de {$request->mes} del {$request->anio}.");
        } else {
            return back()->with('error', 'Ya existen desafíos mensuales para todas las iglesias en ese mes y año.');
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
}
