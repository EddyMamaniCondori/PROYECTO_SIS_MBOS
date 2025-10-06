<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\Administrativo;
use Illuminate\Support\Facades\Validator;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $grupos = Grupo::leftJoin('administrativos as xa', 'grupos.administrativo_id', '=', 'xa.id_persona')
                ->leftJoin('personas as xp', 'xa.id_persona', '=', 'xp.id_persona')
                ->select(
                    'grupos.*',
                    'xa.cargo',
                    'xp.nombre as nombre_ad',
                    'xp.ape_paterno as ape_paterno_ad',
                    'xp.ape_materno as ape_materno_ad'
                )
                ->get();
        return view('gp.index',['grupos'=>$grupos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $administrativos = Administrativo::AdministrativoDC();
        //dd($administrativos);
        return view('gp.create', compact('administrativos'));
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ 1. Validar datos
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:150',
            'nro_distritos' => 'nullable|integer|min:0',
            'administrativo_id' => 'nullable|integer|exists:administrativos,id_persona', // 👈 validación extra
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        // ✅ 2. Crear el grupo
        $grupo = Grupo::create([
            'nombre' => $request->nombre,
            'nro_distritos' => $request->nro_distritos ?? 0,  // valor por defecto 0
            'administrativo_id' => $request->administrativo_id, 
        ]);

        // ✅ 3. Redirigir con mensaje de éxito
        return redirect()->route('grupo.index')
                        ->with('success', 'Grupo creado correctamente.');
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
