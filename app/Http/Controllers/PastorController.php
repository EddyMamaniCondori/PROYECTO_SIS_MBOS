<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Persona; 
use App\Models\Pastor;


use App\Http\Requests\PastorRequest;
use App\Http\Requests\UpdatePastorRequest;

class PastorController extends Controller
{
    public function indexdelete()
    {
        $pastores = Persona::join('pastors as xp', 'personas.id_persona', '=', 'xp.id_pastor')
                    ->where('personas.estado', false)
                    ->get();
        //dd($pastores);
        
        return view('pastores.indexeliminados',['personas'=>$pastores]);
    }

    public function index()
    {
        /*$pastores = Persona::join('pastors as xp', 'personas.id_persona', '=', 'xp.id_pastor')
                    ->where('personas.estado', true)
                    ->get();*/
        $pastores = Persona::join('pastors as xp', 'personas.id_persona', '=', 'xp.id_pastor')
            ->leftJoin('distritos as xd', 'xp.id_pastor', '=', 'xd.id_pastor')
            ->where('personas.estado', true)
            ->select(
                'personas.*',
                'xp.*',
                DB::raw('CASE WHEN xd.id_pastor IS NOT NULL THEN 1 ELSE 0 END as asignado')
            )
            ->get();
        //dd($pastores);
        return view('pastores.index',['personas'=>$pastores]);
    }


    public function indexEliminados()
    {
        $pacientes = Persona::join('pacientes as xp', 'personas.id', '=', 'xp.id')
                    ->where('personas.estado', 0)
                    ->get();
        return view('paciente.indexEliminados',['personas'=>$pacientes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() //VERIFICADO
    {
        return view('pastores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PastorRequest $request) //VERIFICADO
    {
        try {
            DB::beginTransaction();
            $pers = Persona::create($request->validated()); // se crea el registro 
            $pers->pastor()->create([
                'id_pastor'          => $pers->id_persona,  
                'fecha_ordenacion'   => $request->filled('fecha_ordenacion') ? $request->fecha_ordenacion : null,
                'ordenado'           => $request->filled('fecha_ordenacion'), // true si hay fecha, false si no
                'cargo'              => $request->filled('cargo') ? $request->cargo : 'Pastor',
                'fecha_contratacion' => $request->filled('fecha_contratacion') ? $request->fecha_contratacion : null,
                'contratado'         => $request->filled('fecha_contratacion'), // true si hay fecha, false si no
                'nro_distritos'      => 0,
            ]);


            /*$pers ->user()->create([
                'id'=>$pers->id,
                'name'=>$pers->nombre,
                'email'=> $request->email,
                'password'=>Hash::make($request->password), // Encripta la contraseña
            ]);*/
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear la pastor: ' . $e->getMessage()], 500);

        }

        return redirect()->route('pastores.index')->with('success','Pastor registrado Correctamente');
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
    public function edit(string $pastore) //verificado
    {
        //dd($pastore);
        $pastor = DB::table('personas as xp')
                ->join('pastors as xpp', 'xp.id_persona', '=', 'xpp.id_pastor')
                ->where('xp.id_persona', $pastore)
                ->select('xp.*', 'xpp.*')
                ->first();

        return view('pastores.edit',['pastor'=>$pastor]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePastorRequest $request, $id) //verificado
    {
        try {
            $pastor = Pastor::findOrFail($id);
            $persona = Persona::findOrFail($pastor->id_pastor); 
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
            $pastor->update([
                'fecha_ordenacion'   => $request->fecha_ordenacion,
                'cargo'              => $request->cargo,
                'fecha_contratacion' => $request->fecha_contratacion,
            ]);
            return redirect()->route('pastores.index')->with('success', 'Pastor actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    
    }

    public function destroy(string $id) //VERIFICADO
    {
        try {
            DB::beginTransaction();
            $persona = Persona::find($id); 
            $persona -> estado = false;
            $persona -> save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al Eliminar al Pastor: ' . $e->getMessage()], 500);
        }
        return redirect()->route('pastores.index')->with('success','Pastor Eliminado Correctamente');
    }

    public function reactive(string $id) //VERIFICADO
    {
        try {
            DB::beginTransaction();
            $persona = Persona::find($id); 
            $persona -> estado = true;
            $persona -> save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al Reactivar el Pastor: ' . $e->getMessage()], 500);
        }
        return redirect()->route('pastores.index')->with('success','Pastor Recuperado Correctamente');
    }

    

    public function perfil_pastor($id_pastor) //VERIFICADO
    {
        DB::beginTransaction();
        $pastor = DB::table('pastors as xp')
                ->join('personas as xpp', 'xp.id_pastor', '=', 'xpp.id_persona')
                ->where('xp.id_pastor', $id_pastor)
                ->select('xp.*', 'xpp.*')
                ->first();
        DB::commit();

        if (!$pastor) {
            abort(404, 'Pastor no encontrado');
        }
        return view('pastores.perfil_administrativo', compact('pastor'));
    }

    /*
    ************************************
    eliminacion definitiva (estas lineas eliminan de las tablas personas y pacientes)
    **************************************

     public function destroy(string $id) //VERIFICADO
    {
        try {
            DB::beginTransaction();
            $persona = Persona::find($id); 
            $persona -> delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al eliminar la persona: ' . $e->getMessage()], 500);
        }
        return redirect()->route('pacientes.index')->with('success','Paciente Eliminado Correctamente');
    }

     */
}
