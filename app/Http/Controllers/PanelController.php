<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Distrito;
use App\Models\Desafio;
use App\Models\Mensual;
use Illuminate\Support\Facades\DB;
class PanelController extends Controller
{
    public function dashboard_pastores() //permision 'ver dashboard pastores - panel',
    {
        $anio = now()->year;
        $persona = Auth::user(); 
        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();

       

        if (!$distrito) {
            return redirect()->route('panel')
                ->with('error', 'No tienes un distrito asignado. ¡Comunícate con el Administrador!');
        }
        $id_distrito = $distrito->id_distrito;

        /**datos para los cards
         */
         $resumenIglesias = DB::table('iglesias')
        ->select(
            DB::raw("COUNT(*) as total_iglesias"),
            DB::raw("SUM(CASE WHEN tipo = 'Iglesia' THEN 1 ELSE 0 END) as total_iglesia"),
            DB::raw("SUM(CASE WHEN tipo = 'Grupo' THEN 1 ELSE 0 END) as total_grupo"),
            DB::raw("SUM(CASE WHEN tipo = 'Filial' THEN 1 ELSE 0 END) as total_filial")
        )
        ->where('distrito_id', $id_distrito)
        ->first();

        /**DATOS DE BAUTISOS DEL DISTRITO */
        $bautiso = DB::table('desafios as xd')
            ->join('distritos as xdd', 'xd.id_distrito', '=', 'xdd.id_distrito')
            ->where('xdd.id_distrito', $id_distrito)
            ->where('xd.anio', $anio)
            ->select(
                'xdd.id_distrito',
                'xdd.nombre as nombre_distrito',
                'xd.desafio_bautizo',
                'xd.bautizos_alcanzados',
                DB::raw('(xd.desafio_bautizo - xd.bautizos_alcanzados) as diferencia')
            )
            ->first();

        if (!$bautiso) {
            return redirect()->route('panel')
                ->with('info', 'Aún no hay desafíos asignados a tu distrito este año.');
        }

        $graficos_final = [
            'categorias' => ['Desafío', 'Alcanzado', 'Diferencia'],
            'valores' => [
                (int) $bautiso->desafio_bautizo,
                (int) $bautiso->bautizos_alcanzados,
                (int) $bautiso->diferencia
            ]
        ];
        /**DATOS DE VISITAS DEL DISTRITO */
        // Obtener el desafío anual del distrito
        $desafio = Desafio::where('anio', $anio)
            ->where('id_distrito', $id_distrito)
            ->first();
        if (!$desafio) {
            return redirect()->back()->with('error', 'Desafío anual no encontrado.');
        }
        $mensuales = Mensual::where('id_desafio', $desafio->id_desafio)
            ->orderBy('mes') // importante para que los meses estén en orden
            ->get();
        $mensuales = $mensuales->filter(fn($m) => $m->mes <= now()->month); // SOLO LOS DESAFIOS DE HASTA EL MES ACTUAL

        $meses = [];
        $desafios = [];
        $alcanzados = [];
        $nombresMeses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        foreach ($mensuales as $m) {
            $meses[] = $nombresMeses[$m->mes] ?? 'Desconocido';
            $desafios[] = (int) $m->desafio_visitas;
            $alcanzados[] = (int) $m->visitas_alcanzadas;
        }  
        //DATOS DE INSTRUCTORES Y ESTUDIANTES

       $totales = DB::table('desafios as xd')
            ->join('anual_iglesias as xai', 'xd.id_desafio', '=', 'xai.id_desafio')
            ->where('xd.id_distrito', $id_distrito)
            ->where('xd.anio', $anio)
            ->select(
                DB::raw('SUM(xai.desafio_estudiantes) as total_desafio_estudiantes'),
                DB::raw('SUM(xai.estudiantes_alcanzados) as total_estudiantes_alcanzados'),
                DB::raw('SUM(xai.desafio_instructores) as total_desafio_instructores'),
                DB::raw('SUM(xai.instructores_alcanzados) as total_instructores_alcanzados'),
                DB::raw('SUM(xai.desafio_estudiantes - xai.estudiantes_alcanzados) as diferencia_estudiantes'),
                DB::raw('SUM(xai.desafio_instructores - xai.instructores_alcanzados) as diferencia_instructores')
            )
            ->first();
        $grafico_estudiantes = [
            'categorias' => ['Desafío', 'Alcanzado', 'Diferencia'],
            'valores' => [
                (int) $totales->total_desafio_estudiantes,
                (int) $totales->total_estudiantes_alcanzados,
                (int) $totales->diferencia_estudiantes,
            ]
        ];

        $grafico_instructores = [
            'categorias' => ['Desafío', 'Alcanzado', 'Diferencia'],
            'valores' => [
                (int) $totales->total_desafio_instructores,
                (int) $totales->total_instructores_alcanzados,
                (int) $totales->diferencia_instructores,
            ]
        ];

        return view('dashboards.dashboard_pastores', compact('resumenIglesias', 'graficos_final', 'distrito','meses', 'desafios', 'alcanzados', 'grafico_estudiantes', 'grafico_instructores' ));
    }

    

}
