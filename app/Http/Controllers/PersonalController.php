<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona; 
use App\Models\Personal;
use App\Http\Requests\PersonalRequest;
use App\Http\Requests\UpdatePersonalRequest;
use Illuminate\Support\Facades\DB;
use Exception;
class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexdelete()
    {
        $personales = Persona::join('personales as xp', 'personas.id_persona', '=', 'xp.id_personal')
                    ->where('personas.estado', false)
                    ->get();
        
        return view('personales.indexeliminados',['personas'=>$personales]);
    }   

    public function index()
    {
        $personales = Persona::join('personales as xp', 'personas.id_persona', '=', 'xp.id_personal')
                    ->where('personas.estado', true)
                    ->get();
        
        return view('personales.index',['personas'=>$personales]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('personales.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonalRequest $request)
    {
        try {
            DB::beginTransaction();
            $pers = Persona::create($request->validated()); // se crea el registro 
            $pers->personal()->create([
                'id_personal'          => $pers->id_persona,  
                'fecha_ingreso' => $request->filled('fecha_ingreso') ? $request->fecha_ingreso : null,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear al personal: ' . $e->getMessage()], 500);

        }

        return redirect()->route('personales.index')->with('success','Personal registrado Correctamente');

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
        $personal = DB::table('personas as xp')
                ->join('personales as xps', 'xp.id_persona', '=', 'xps.id_personal')
                ->where('xps.id_personal', $id)
                ->select('xp.*', 'xps.*')
                ->first();
        return view('personales.edit', ['pastor' => $personal]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonalRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $personal = Personal::findOrFail($id);
            $persona = Persona::findOrFail($personal->id_personal);
            
            // Actualizar datos de la persona
            $persona->update([
                'nombre'       => $request->nombre,
                'ape_paterno'  => $request->ape_paterno,
                'ape_materno'  => $request->ape_materno,
                'fecha_nac'    => $request->fecha_nac,
                'ci'           => $request->ci,
                'celular'      => $request->celular,
                'ciudad'       => $request->ciudad,
                'zona'         => $request->zona,
                'calle'        => $request->calle,
                'nro'          => $request->nro,
            ]);
            
            // Actualizar datos específicos del personal
            $personal->update([
                'fecha_ingreso'       => $request->fecha_ingreso
            ]);
            
            DB::commit();
            
            return redirect()->route('personales.index')->with('success', 'Personal actualizado correctamente.');
            
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
            $personal = Persona::findOrFail($id);
            $personal->estado = false;
            $personal->save();

            $personale = Personal::findOrFail($id);
            $personale ->fecha_finalizacion = now()->toDateString();
            $personale->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al eliminar el Personal: ' . $e->getMessage()], 500);
        }
        return redirect()->route('personales.index')->with('success', 'Personal eliminado correctamente');
    }

    public function reactive(string $id)
    {
        try {
            DB::beginTransaction();
            $personal = Persona::findOrFail($id);
            $personal->estado = true;
            $personal->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al reactivar el Personal: ' . $e->getMessage()], 500);
        }
        return redirect()->route('personales.index')->with('success', 'Personal reactivado correctamente');
    }
}
