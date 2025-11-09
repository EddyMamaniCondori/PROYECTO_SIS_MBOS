<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\AnualIglesia; 

class DesafioAnualIglesiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function update(Request $request, string $id)
    {
        try {
            // Validar los datos primero
            $validated = $request->validate([
                'desafio_estudiantes' => 'required|integer|min:0',
                'desafio_instructores' => 'required|integer|min:0',
            ]);

            DB::beginTransaction();

            $desafio_anual_iglesia = AnualIglesia::find($id);
            
            $desafio_anual_iglesia->update($validated);
            
            $id_desafio = $desafio_anual_iglesia->id_desafio;
            
            DB::commit();

            return redirect()->route('desafios.index_distrital', ['id' => $id_desafio])
                ->with('success', 'Desafío Anual de Iglesia actualizado correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el Desafío: ' . $e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $desafio_anual_iglesia = AnualIglesia::find($id);

            if (!$desafio_anual_iglesia) {
                return redirect()->back()
                    ->with('error', 'Desafío Anual de la Iglesia no encontrado');
            }

            // Guardamos el id del distrito antes de eliminarlo
            $id_desafio= $desafio_anual_iglesia->id_desafio;

            $desafio_anual_iglesia->delete();

            DB::commit();

            // Redirigir a la ruta /desafios/{id}/distrital
            return redirect()->route('desafios.index_distrital', ['id' => $id_desafio])
                ->with('success', 'Desafío Anual de Iglesia eliminado correctamente.');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al eliminar el Desafío: ' . $e->getMessage());
        }
    }

}
