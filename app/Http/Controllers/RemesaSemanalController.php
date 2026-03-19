<?php

namespace App\Http\Controllers;
use App\Models\Iglesia;
use App\Models\Remesa;
use App\Models\RemesaSemanal;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RemesaSemanalController extends Controller
{
    //

    public function registroSemanas($id) {
    // Cargamos la remesa con sus semanas ya registradas (si las tiene)
        $remesa = Remesa::with('semanas')->findOrFail($id);

        $id_iglesia = DB::table('remesas as xr')
            ->leftJoin('generas as xg', 'xg.id_remesa', '=', 'xr.id_remesa')
            ->where('xr.id_remesa', $id)
            ->value('xg.id_iglesia');

        if (!$id_iglesia) {
            return redirect()->back()->with('error', 'No se encontró una iglesia vinculada a esta remesa.');
        }

        $iglesia = Iglesia::where('id_iglesia', $id_iglesia)->firstOrFail();

        $semanasExistentes = RemesaSemanal::where('id_remesa', $id)
            ->orderBy('nro_semana', 'asc')
            ->get();


        return view('remesas.remesas_semanales', compact('remesa', 'iglesia', 'semanasExistentes'));
    }

}
