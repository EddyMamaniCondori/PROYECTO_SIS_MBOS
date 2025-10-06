<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distrito;
use App\Models\Pastor;
use App\Models\Grupo;
use Illuminate\Support\Facades\Validator;

class DistritoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
    public function index()
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
        return view('distritos.index', ['distritos' => $distritos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pastores = Pastor::PastorDC();
        $grupos = Grupo::all();
        return view('distritos.create', compact('pastores', 'grupos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1️⃣ Validar los datos
        //dd($request);
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:150',
            'nro_iglesias' => 'nullable|integer|min:0',
            'id_grupo' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        
        // 2️⃣ Guardar el distrito
        $distrito = Distrito::create([
            'nombre' => $request->nombre,
            'nro_iglesias' => $request->nro_iglesias ?? 0,
            'id_pastor' => $request->id_pastor, 
            'id_grupo' => $request->id_grupo,
        ]);

        // 3️⃣ Redirigir con mensaje de éxito
        return redirect()->route('distritos.index')
                        ->with('success', 'Distrito creado correctamente');
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
