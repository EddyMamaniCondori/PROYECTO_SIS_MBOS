<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distrito;
use App\Models\Pastor;
use App\Models\Grupo;
use App\Models\Dirige;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class ResponsableRemesaController extends Controller
{
    public function index() //PERMISION ver distritos
    {
        $anios = DB::select('SELECT DISTINCT xd.año FROM distritos xd');

        $distritos = Distrito::leftJoin('pastors', 'distritos.id_pastor', '=', 'pastors.id_pastor')
                        ->leftJoin('personas as per_pastor', 'pastors.id_pastor', '=', 'per_pastor.id_persona')
                        ->leftJoin('personales as resp_remesa', 'distritos.id_responsable_remesa', '=', 'resp_remesa.id_personal')
                        ->leftJoin('personas as per_resp', 'resp_remesa.id_personal', '=', 'per_resp.id_persona')
                        ->leftJoin('grupos', 'grupos.id_grupo', '=', 'distritos.id_grupo')
                        ->select(
                            'distritos.*',
                            'per_pastor.nombre as nombre_pastor',
                            'per_pastor.ape_paterno as ape_paterno_pastor',
                            'per_pastor.ape_materno as ape_materno_pastor',
                            'per_resp.nombre as nombre_responsable',
                            'per_resp.ape_paterno as ape_paterno_responsable',
                            'per_resp.ape_materno as ape_materno_responsable',
                            
                            'grupos.nombre as dist_nombre'
                        )
                        ->where('distritos.estado', true)
                        ->get();
        $personal = DB::select('select xpp.id_persona, xpp.nombre, xpp.ape_paterno, xpp.ape_materno
                            from personales xp
                            join personas xpp on xp.id_personal = xpp.id_persona
                            join model_has_roles xm on xm.model_id = xpp.id_persona
                            join roles xr on xm.role_id = xr.id
                            where xr.name like ?', ['%Tesorero%']);

        //dd($anios, $personal, $distritos);
        return view('responsable_remesa.index', compact('distritos', 'anios', 'personal'));
    }

    public function asignar_responsable(Request $request) //permissions creaer distritos 2
    {
       DB::beginTransaction();
        try {
            $distrito = Distrito::findOrFail($request->id_distrito);
            $distrito ->id_responsable_remesa = $request->id_persona;  
            $distrito->save();
            DB::commit();

            return redirect()->route('responsable.index')
                            ->with('success', 'Responsable asignado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error al crear distrito: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al registrar el distrito.');
        }
    }
}
