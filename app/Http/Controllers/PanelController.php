<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Distrito;
use App\Models\Desafio;
use App\Models\Mensual;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
    /**
     * 'ver dashboard pastores-panel',
     * ver avance pastores-panel
     */
    function __construct()
    {
        // dashboard_pastores(): permision 'ver dashboard pastores - panel'
        $this->middleware('permission:ver dashboard pastores-panel', ['only' => ['dashboard_pastores']]);
        $this->middleware('permission:ver avance pastores-panel', ['only' => ['ver_avance_pastores']]);
    }

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
    //MBOS - puede ver el avance en graficos de los pastores
    public function ver_avance_pastores($id, $anio) //permision 'ver avanve pastores - panel',
    {
        $distrito = Distrito::findOrFail($id);
        $pastor = Persona::findOrFail($distrito->id_pastor);
        if (!$distrito) {
            return redirect()->route('panel')
                ->with('error', 'No pudimos encontra al Distrito en el refistro');
        }

        $id_distrito = $distrito->id_distrito;
        /**DATOS DE IGLESIA Del Distrito  **/
        $resumenIglesias = DB::table('iglesias') //ajustar solo junciona para el año actual
        ->select(
            DB::raw("COUNT(*) as total_iglesias"),
            DB::raw("SUM(CASE WHEN tipo = 'Iglesia' THEN 1 ELSE 0 END) as total_iglesia"),
            DB::raw("SUM(CASE WHEN tipo = 'Grupo' THEN 1 ELSE 0 END) as total_grupo"),
            DB::raw("SUM(CASE WHEN tipo = 'Filial' THEN 1 ELSE 0 END) as total_filial")
        )
        ->where('distrito_id', $id_distrito)
        ->first();

        /**DATOS DE BAUTISOS DEL DISTRITO **/
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
        //**VER GRAFICOS POR IGLESIA DE ESE DESAFIO */
        $desafios_ins_est = DB::table('desafios as xd')
            ->join('anual_iglesias as xai', 'xd.id_desafio', '=', 'xai.id_desafio')
            ->join('iglesias as xi', 'xai.id_iglesia', '=', 'xi.id_iglesia')
            ->where('xd.id_distrito', $id_distrito)
            ->where('xd.anio', $anio)
            ->select(
                'xai.id_iglesia',
                'xi.codigo',
                'xi.tipo',
                'xi.nombre as nombre_iglesia',
                'xai.desafio_estudiantes',
                'xai.estudiantes_alcanzados',
                'xai.desafio_instructores',
                'xai.instructores_alcanzados'
            )
            ->get();

        // Procesar los datos para el gráfico (ya listos) NUMEROS
        $graficos_ins_est = $desafios_ins_est->map(function ($d) {
            return [
                'id_iglesia' => $d->id_iglesia,
                'estudiantes' => [
                    'desafio' => (int)$d->desafio_estudiantes,
                    'alcanzado' => (int)$d->estudiantes_alcanzados,
                    'diferencia' => (int)$d->desafio_estudiantes - (int)$d->estudiantes_alcanzados,
                ],
                'instructores' => [
                    'desafio' => (int)$d->desafio_instructores,
                    'alcanzado' => (int)$d->instructores_alcanzados,
                    'diferencia' => (int)$d->desafio_instructores - (int)$d->instructores_alcanzados,
                ]
            ];
        });
        return view('dashboards.dashboard_pastor_mbos', compact('pastor','graficos_ins_est','desafios_ins_est','resumenIglesias', 'graficos_final', 'distrito','meses', 'desafios', 'alcanzados', 'grafico_estudiantes', 'grafico_instructores' ));
    }

}
