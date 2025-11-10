<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Exception;
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
        $administrativos = DB::table('personas as p')
            ->join('administrativos as a', 'p.id_persona', '=', 'a.id_persona')
            ->leftJoin('grupos as g', 'a.id_persona', '=', 'g.administrativo_id')
            ->whereNull('g.administrativo_id') // No están en ningún grupo
            ->where('p.estado', true) // Solo activos (opcional)
            ->select('p.*', 'a.*')
            ->get();
            
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
    /**
 * Show the form for editing the specified resource.
 */
    public function edit(string $id)
    {
        $grupo = DB::table('grupos as g')
            ->where('g.id_grupo', $id)
            ->select('g.*')
            ->first();
        
        // Obtener administrativos disponibles (no asignados a otros grupos)
        $administrativos = DB::table('personas as p')
            ->join('administrativos as a', 'p.id_persona', '=', 'a.id_persona')
            ->leftJoin('grupos as g', 'a.id_persona', '=', 'g.administrativo_id')
            ->where(function($query) use ($id) {
                $query->whereNull('g.administrativo_id') // No asignados
                    ->orWhere('g.id_grupo', $id); // O el actual (para poder mantenerlo)
            })
            ->where('p.estado', true)
            ->select('p.*')
            ->get();
        
        return view('gp.edit', compact('grupo', 'administrativos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'administrativo_id' => 'required',
        ], [
            'nombre.required' => 'El nombre del grupo es obligatorio.',
            'administrativo_id.required' => 'Debe seleccionar un administrativo.',
        ]);
        try {
            DB::beginTransaction();
            
            $grupo = Grupo::findOrFail($id);
            $grupo->update([
                'nombre' => $request->nombre,
                'administrativo_id' => $request->administrativo_id,
            ]);
            
            DB::commit();
            
            return redirect()->route('grupo.index')->with('success', 'Grupo actualizado correctamente.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar el grupo: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            $grupo = Grupo::findOrFail($id);
            $grupo->delete();
            
            DB::commit();
            
            return redirect()->route('grupo.index')->with('success', 'Grupo eliminado correctamente.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al eliminar el grupo: ' . $e->getMessage()], 500);
        }
    }
}
