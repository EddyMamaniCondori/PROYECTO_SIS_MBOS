<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Pastor;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Persona; 

use App\Http\Requests\PastorRequest;


class PastorController extends Controller
{
    public function index()
    {
        $pastores = Persona::join('pastors as xp', 'personas.id_persona', '=', 'xp.id_pastor')
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
    public function edit(string $persona) //verificado
    {
        $paciente = Persona::findOrFail($persona);
        return view('paciente.edit',['paciente'=>$paciente]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePacienteRequest $request, $id) //verificado
    {
        try {
         // Recuperar el modelo de la persona por el ID proporcionado en el request
            $persona = Persona::findOrFail($id);

            // Aplicar la actualización
            $persona->update($request->validated());

            // Redirigir con un mensaje de éxito
            return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Redirigir con mensaje de error
            return redirect()->back()->with('error', 'Error al actualizar el paciente: ' . $e->getMessage());
        }
    }

    public function destroy(string $id) //VERIFICADO
    {
        try {
            DB::beginTransaction();
            $persona = Persona::find($id); 
            $persona -> estado = 0;
            $persona -> save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al Eliminar el Paciente: ' . $e->getMessage()], 500);
        }
        return redirect()->route('pacientes.index')->with('success','Paciente Eliminado Correctamente');
    }

    public function reactive(string $id) //VERIFICADO
    {
        try {
            DB::beginTransaction();
            $persona = Persona::find($id); 
            $persona -> estado = 1;
            $persona -> save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al Reactivar el Paciente: ' . $e->getMessage()], 500);
        }
        return redirect()->route('pacientes.index')->with('success','Paciente Recuperado Correctamente');
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
