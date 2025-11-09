<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distrito;
use App\Models\Pastor;
use App\Models\BlancoRemesa;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

use App\Http\Requests\DistritoRequest;
class BlancoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() 
    {
        $anioActual = now()->year;

        // 2 Verificar si existen blancos para este año
        $existenBlancos = DB::table('blanco_remesas')
            ->where('anio', $anioActual)
            ->exists();

        // 3️⃣ Si no existen, crear los blancos para cada distrito activo
        if (!$existenBlancos) {
            // Obtener todos los distritos activos con su pastor
            $distritosActivos = Distrito::where('estado', true)
                ->leftJoin('pastors', 'distritos.id_pastor', '=', 'pastors.id_pastor')
                ->select('distritos.id_distrito', 'pastors.id_pastor')
                ->get();

            foreach ($distritosActivos as $d) {
                DB::table('blanco_remesas')->insert([
                    'monto'       => 0,
                    'alcanzado'   => 0,
                    'diferencia'  => 0,
                    'anio'        => $anioActual,
                    'id_distrito' => $d->id_distrito,
                    'id_pastor'   => $d->id_pastor,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        // 4️⃣ Traer todos los años disponibles en distritos (o en blancos, si prefieres)
        $anios = DB::table('blanco_remesas')
            ->select(DB::raw('DISTINCT anio'))
            ->orderByDesc('anio')
            ->get();

        // 5️⃣ Traer los blancos con su distrito y pastor
        $blancos = DB::table('blanco_remesas as b')
            ->join('distritos as d', 'b.id_distrito', '=', 'd.id_distrito')
            ->leftJoin('pastors as p', 'b.id_pastor', '=', 'p.id_pastor')
            ->leftJoin('personas as per', 'p.id_pastor', '=', 'per.id_persona')
            ->select(
                'b.*',
                'd.nombre as nombre_distrito',
                'per.nombre as nombre_pastor',
                'per.ape_paterno',
                'per.ape_materno'
            )
            ->where('b.anio', $anioActual)
            ->orderBy('d.nombre')
            ->get();

        // 6️⃣ Devolver vista con los datos
        return view('blancos.index', compact('blancos', 'anios', 'anioActual'));
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
    public function store(Request $request)
    {
        //
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
   public function update(Request $request, $id)
    {
        $request->validate([
            'monto' => 'required|numeric|min:0',
        ]);

        $blanco = BlancoRemesa::findOrFail($id);
        $blanco->update([
            'monto' => $request->monto,
        ]);

        return redirect()->back()->with('success', 'Monto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
