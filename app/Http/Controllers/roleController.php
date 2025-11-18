<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Exception;
use Illuminate\Support\Facades\DB;
class roleController extends Controller
{
    /**
     * 
     * 'gestionar-roles',
     *       'gestionar-permisos',
     *       'asignar-roles',

     * Display a listing of the resource.
     */
    function __construct()
    {
        // index(): permission ver roles
        $this->middleware('permission:ver-roles|crear-roles|editar-roles|eliminar-roles', ['only' => ['index']]);

        // create() y store(): permission crear roles
        $this->middleware('permission:crear-roles', ['only' => ['create', 'store']]);

        // edit() y update(): permission editar roles
        $this->middleware('permission:editar-roles', ['only' => ['edit', 'update']]);

        // destroy(): permission eliminar roles
        $this->middleware('permission:eliminar-roles', ['only' => ['destroy']]);

        // Los permisos 'gestionar-permisos' y 'asignar-roles' se usan típicamente en otros controladores, por lo que no se aplican directamente aquí.
    }
    
    public function index() // permission ver roles
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() // permission crear roles
    { 
        $permisos = Permission::all();
        return view('roles.create', compact('permisos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // permission crear roles
    {
        //dd($request);
        // ✅ Validación de los datos
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array|min:1',
        ]);
        try {
            DB::beginTransaction();
            $rol = Role::create(['name' => $request->name]);
            $rol->syncPermissions($request->permission);
            DB::commit(); 
            return redirect()
                ->route('roles.index')
                ->with('success', 'Rol registrado correctamente.');

       } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Error al registrar rol: ' . $th->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al registrar el rol.');
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
    public function edit(string $id) // permission editar roles
    {
        $role = Role::findOrFail($id);
        $permisos = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'permisos', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) // permission editar roles
    {
        // Validar los datos
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id, // Permite mantener el mismo nombre
            'permission' => 'required|array|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Buscar el rol
            $role = Role::findOrFail($id);

            // Actualizar nombre
            $role->update(['name' => $request->name]);

            // Sincronizar permisos
            $role->syncPermissions($request->permission);

            DB::commit();

            return redirect()
                ->route('roles.index')
                ->with('success', 'Rol actualizado correctamente.');

        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Error al actualizar el rol: ' . $th->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar el rol.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)  // permission eliminar roles
    {
        try {
            DB::beginTransaction();

            $role = Role::findOrFail($id);

            if (in_array($role->name, ['Administrador', 'Super Administrador'])) {
                DB::rollBack();
                return redirect()
                    ->route('roles.index')
                    ->with('error', "No se puede eliminar el rol {$role->name}.");
            }

            $usuariosConRol = $role->users()->count() > 0;
            if ($usuariosConRol > 0) {
                DB::rollBack();
                return redirect()
                    ->route('roles.index')
                    ->with('error', 'No se puede eliminar este rol porque está asignado a uno o más usuarios.');
            }

            $role->delete();

            DB::commit();

            return redirect()
                ->route('roles.index')
                ->with('success', 'Rol eliminado correctamente.');

        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Error al eliminar el rol: ' . $th->getMessage());

            return redirect()
                ->route('roles.index')
                ->with('error', 'Ocurrió un error al intentar eliminar el rol.');
        }
    }

}
