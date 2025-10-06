<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iglesia;
use App\Models\Distrito;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;


class IglesiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    

    public function index()
    {
        // Traemos todas las iglesias junto con su distrito
        $iglesias = Iglesia::with('distrito')->get();

        return view('iglesias.index', compact('iglesias'));
    }

   
   
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $distritos = Distrito::all();

    // Pasamos a la vista
        return view('iglesias.create', compact('distritos'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        $request->validate([
            'nombre' => 'required|string|max:255',
            'feligresia' => 'required|integer|min:0',
            'feligrasia_asistente' => 'required|integer|min:0',
            'ciudad' => 'nullable|string|max:255',
            'zona' => 'nullable|string|max:255',
            'calle' => 'nullable|string|max:255',
            'nro' => 'nullable|string|max:255',
            'distrito_id' => 'nullable|exists:distritos,id_distrito',
        ]);
        
       if (empty($request->distrito_id)) {
            Iglesia::create([
                'nombre' => $request->nombre,
                'feligresia' => $request->feligresia,
                'feligrasia_asistente' => $request->feligrasia_asistente,
                'ciudad' => $request->ciudad,
                'zona' => $request->zona,
                'calle' => $request->calle,
                'nro' => $request->nro,
                'distrito_id' => null, // no se asigna distrito
            ]);
        } else {
            Iglesia::create([
                'nombre' => $request->nombre,
                'feligresia' => $request->feligresia,
                'feligrasia_asistente' => $request->feligrasia_asistente,
                'ciudad' => $request->ciudad,
                'zona' => $request->zona,
                'calle' => $request->calle,
                'nro' => $request->nro,
                'distrito_id' => $request->distrito_id, // se asigna el distrito seleccionado
            ]);
        }


        return redirect()->route('iglesias.index')
            ->with('success', 'Iglesia creada correctamente.');
    }

    /**
     * Muestra una iglesia específica.
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
        return view('iglesias.edit', compact('iglesia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $iglesia->update($request->all());

        return redirect()->route('iglesias.index')
                         ->with('success', 'Iglesia actualizada correctamente.');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }



     /**
     * Iglesias sin asignar.
     */


     public function dashboard_general()
    {
        
        $result = DB::table('desafio_mensuales')
                ->select('mes', 
                'desafio_bautiso', 'bautisos_alcanzados', 
                'desafios_est_biblicos', 'estudiantes_alcanzados',
                'desafio_inst_biblicos', 'instructores_alcanzados',
                'desafio_visitacion', 'visitas_alcanzadas')
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


        // bautisos
        $meses = $result->pluck('mes');                 // ['enero','febrero',...]
        $desafios_bau = $result->pluck('desafio_bautiso'); // [28,48,40,...]
        $alcanzados_bau = $result->pluck('bautisos_alcanzados'); // [65,59,80,...]
        //ESTUDIANTES

        $desafios_est = $result->pluck('desafios_est_biblicos'); // [28,48,40,...]
        $alcanzados_est = $result->pluck('estudiantes_alcanzados'); // [65,59,80,...]

        //INST4RUCTORES
        $desafios_ins = $result->pluck('desafio_inst_biblicos'); // [28,48,40,...]
        $alcanzados_ins = $result->pluck('instructores_alcanzados'); // [65,59,80,...]

        /// VISTAS

        $desafios_vis = $result->pluck('desafio_visitacion'); // [28,48,40,...]
        $alcanzados_vis = $result->pluck('visitas_alcanzadas'); // [65,59,80,...]
        // RETURN

        return view('iglesias.dashboard', compact('meses','desafios_bau','alcanzados_bau','desafios_est','alcanzados_est','desafios_ins','alcanzados_ins','desafios_vis','alcanzados_vis'));
    }
   
}
