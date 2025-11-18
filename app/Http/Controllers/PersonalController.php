<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona; 
use App\Models\Personal;
use Spatie\Permission\Models\Role;
use App\Http\Requests\PersonalRequest;
use App\Http\Requests\UpdatePersonalRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;
class PersonalController extends Controller
{
    /**
     * 
     * 'ver-personal',
      *      'ver eliminados-personal',
       *     'crear-personal',
     *       'editar-personal',
     *       'eliminar-personal',
     *       'reactivar-personal',

     * Display a listing of the resource.
     */

    function __construct()
    {
        // index(): permission ver personal
        // Nota: Agrupamos todos los permisos de gestión para acceder al índice principal.
        $this->middleware('permission:ver-personal|crear-personal|editar-personal|eliminar-personal|reactivar-personal', ['only' => ['index']]);

        // indexdelete(): permission ver eliminados-personal
        $this->middleware('permission:ver eliminados-personal', ['only' => ['indexdelete']]);

        // create() y store(): permission crear personal
        $this->middleware('permission:crear-personal', ['only' => ['create', 'store']]);

        // edit() y update(): permission editar personal
        $this->middleware('permission:editar-personal', ['only' => ['edit', 'update']]);

        // destroy(): permission eliminar personal (Inhabilitar)
        $this->middleware('permission:eliminar-personal', ['only' => ['destroy']]);

        // reactive(): permission reactivar personal
        $this->middleware('permission:reactivar-personal', ['only' => ['reactive']]);

        // show() y perfil_pastor() no tienen permiso explícito y se dejan sin protección aquí.
    }


    public function indexdelete() // permission ver elimnado -personal
    {
        $personales = Persona::join('personales as xp', 'personas.id_persona', '=', 'xp.id_personal')
                    ->where('personas.estado', false)
                    ->get();
        
        return view('personales.indexeliminados',['personas'=>$personales]);
    }   
 
    public function index() // permission ver personal
    {
        $personales = Persona::with(['personal', 'roles']) // incluye personal y roles
            ->where('estado', true)
            ->get();
        
        return view('personales.index',['personas'=>$personales]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create() // permission crear personal
    {
        $roles = Role::all(); // obtenemos todos los roles existentes

        return view('personales.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonalRequest $request)// permission crear personal
    {
        //try {
            DB::beginTransaction();

        // se crea el registro 
            $pers = Persona::create(array_merge(
                $request->validated(),
                ['password' => Hash::make($request->ci)]
            ));
            $pers->personal()->create([
                'id_personal'          => $pers->id_persona,  
                'fecha_ingreso' => $request->filled('fecha_ingreso') ? $request->fecha_ingreso : null,
            ]);
            // Asignar Rol (si se envió)
            if ($request->filled('rol')) {
                $pers->assignRole($request->rol);
            }
            DB::commit();
            return redirect()->route('personales.index')->with('success','Personal registrado Correctamente');
        
        /*} catch (Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear personal: ' . $e->getMessage());
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Error al registrar al personal.');
        }*/
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
     
    public function edit(string $id) // permission editar personal
    {
        $persona = Persona::with('personal', 'roles')->findOrFail($id);
        $roles = Role::all();
        //DD($persona);
        return view('personales.edit', compact('persona', 'roles'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonalRequest $request, $id) // permission editar personal
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
                'email'         => $request->email,
                'ciudad'       => $request->ciudad,
                'zona'         => $request->zona,
                'calle'        => $request->calle,
                'nro'          => $request->nro,
            ]);
            
            // Actualizar datos específicos del personal
            $personal->update([
                'fecha_ingreso'       => $request->fecha_ingreso
            ]);

            //$persona->syncRoles([$request->rol]);
            //manejo de roles 
            // 🔹 Manejo profesional de roles
            $rolActual = $persona->getRoleNames()->first(); // Obtiene el nombre del rol actual (si tiene)
            $nuevoRol = $request->rol; // Puede venir vacío o con un nuevo rol
            // Si tenía rol y el nuevo es vacío → remover rol
            if ($rolActual && !$nuevoRol) {
                $persona->removeRole($rolActual);
            }
                // Si tenía rol y el nuevo es distinto → remover el anterior y asignar el nuevo
                elseif ($rolActual && $nuevoRol && $rolActual !== $nuevoRol) {
                    $persona->removeRole($rolActual);
                    $persona->assignRole($nuevoRol);
                }
                    // Si no tenía rol y ahora se asignó uno nuevo → asignar
                    elseif (!$rolActual && $nuevoRol) {
                        $persona->assignRole($nuevoRol);
                    }
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
    public function destroy(string $id) // permission eliminar personal
    {
        try {
            DB::beginTransaction();
            $personal = Personal::findOrFail($id);
            // Buscar la persona asociada
            $persona = Persona::findOrFail($personal->id_personal);
            // 🔹 Desactivar persona
            $persona->estado = false;
            $persona->save();
            // 🔹 Registrar fecha de finalización del personal
            $personal->fecha_finalizacion = now()->toDateString();
            $personal->save();
            // 🔹 Eliminar (remover) rol si tiene alguno
            $rolActual = $persona->getRoleNames()->first(); // obtiene el nombre del rol actual
            if ($rolActual) {
                $persona->removeRole($rolActual);
            }
            DB::commit();
            return redirect()
                ->route('personales.index')
                ->with('success', 'Personal eliminado y rol removido correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al eliminar personal: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Error al eliminar el personal: ' . $e->getMessage());
        }
    }
    public function reactive(string $id) // permission reactivar personal
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
