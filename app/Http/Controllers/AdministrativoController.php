<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Persona; 
use App\Models\Pastor;

use App\Http\Requests\PastorRequest;
use App\Http\Requests\UpdatePastorRequest;


class AdministrativoController extends Controller
{


    /**
     * 'ver-administrativo',
     * Display a listing of the resource.
     */

    function __construct()
    {
        // index(): Permiso ver administrativo
        $this->middleware('permission:ver-administrativo', ['only' => ['index']]);
    }
    
    public function index() //Permiso  ver administrativo
    {
        $administrativos = Persona::join('administrativos as xp', 'personas.id_persona', '=', 'xp.id_persona')
                    ->where('personas.estado', true)
                    ->get();
        return view('administrativos.index',['personas'=>$administrativos]);
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
        try {
            DB::beginTransaction();
            $pers = Persona::findOrFail($request->id_persona); // más seguro
            $pers->administrativo()->create([
                'id_persona' => $pers->id_persona,
                'cargo' => 'sin cargo',
                'ministerio' => 'sin ministerio',
            ]);
            DB::commit();
            return redirect()
                ->route('administrativos.index')
                ->with('success', 'Pastor habilitado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error al habilitar al pastor: ' . $e->getMessage());
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
