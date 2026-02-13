<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EncargadoRemesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personales = DB::select("select xp.nombre, xr.name
                from personas xp
                join personales xpp on xp.id_persona = xpp.id_personal
                join model_has_roles xm on xp.id_persona = xm.model_id 
                join roles xr on xm.role_id = xr.id
                where xr.name like 'Tesorero'");
        $distritos = Distrito::all();
        return view('encargadoremesas.index', compact('personales', 'distritos'));
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
