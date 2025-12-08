<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria; 
class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        // Opcional: Búsqueda
         $auditorias = Auditoria::orderBy('fecha_hora', 'DESC')->paginate(20);

        return view('auditorias.index', compact('auditorias'));
    }
}
