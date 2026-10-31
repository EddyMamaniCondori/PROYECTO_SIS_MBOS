<?php

namespace App\Http\Controllers;
use App\Models\Distrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ReporteTesoreroOroController extends Controller
{
    public function index()
    {
        $distritos = Distrito::where('estado', true)->orderBy('nombre', 'asc')
                         ->get();

        $encargados = DB::select('select xpp.id_persona, xpp.nombre, xpp.ape_paterno, xpp.ape_materno
                                from personales xp
                                join personas xpp on xp.id_personal = xpp.id_persona
                                join model_has_roles xm on xm.model_id = xpp.id_persona
                                join roles xr on xm.role_id = xr.id
                                where xr.name like ?', ['%Tesorero%']);
        // Aquí luego puedes cargar desde PostgreSQL los distritos o encargados si lo necesitas
        return view('tesoreros_oro.index', compact('distritos', 'encargados'));
    }

    public function ReporteTesorerosDeOroPDF(Request $request)
    {
        //dd($request->all());
        $anio = 2026;
        $mesInicio = $request->periodo_inicio;
        $mesFin = $request->periodo_fin;
        $alcance = $request->alcance;

        $distritos = $request->has('distritos') && !empty($request->distritos) ? implode(',', $request->distritos) : '';
        $tiposIglesia = $request->has('tipos_iglesia') && !empty($request->tipos_iglesia) ? implode(',', $request->tipos_iglesia) : 'Todos';

        // 1. AGREGA ESTA LÍNEA PARA CAPTURAR EL TIPO DE REPORTE
        $tipoReporte = $request->tipo_reporte ?: 'detalles';

        $tipoTesorero = $request->has('tipo_tesorero') && !empty($request->tipo_tesorero) ? implode(',', $request->tipo_tesorero) : 'todos';

        // 2. AGREGA ESTA INTERCEPCIÓN PARA "TODAS LAS IGLESIAS"
        if ($tipoReporte === 'todas') {
            $tipoTesorero = 'todas_iglesias';
        }
        $encargadoId = $request->encargado_id ?: 0;
        $regionZona = $request->region_zona ?: '';

        //ORDEN
        $ordenarPor = $request->ordenar_por ?: 'puntuacion';
        $ordenDir = strtoupper($request->orden_dir) === 'ASC' ? 'ASC' : 'DESC';
        if ($ordenarPor === 'puntuacion') {
            $ordenDir = ($ordenDir === 'ASC') ? 'DESC' : 'ASC';
        }


        $nombreCursor = 'cursor_reporte_' . uniqid();


        // En Postgres los cursores deben consumirse dentro de una transacción
        DB::beginTransaction();
        /*dd($anio,
                $mesInicio,
                $mesFin,
                $alcance,
                $distritos,
                $encargadoId,
                $regionZona,
                $tiposIglesia,
                $tipoTesorero,
                $ordenarPor,
                $ordenDir,
                $nombreCursor);*/
        try {
            // Llamar al Procedure
            DB::statement('CALL sp_reporte_tesoreros_oro_plata(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $anio,
                $mesInicio,
                $mesFin,
                $alcance,
                $distritos,
                $encargadoId,
                $regionZona,
                $tiposIglesia,
                $tipoTesorero,
                $ordenarPor,
                $ordenDir,
                $nombreCursor
            ]);

            // Extraer los datos del cursor
            $resultados = DB::select('FETCH ALL IN "' . $nombreCursor . '"');
            //dd($resultados);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Error al procesar el reporte: ' . $e->getMessage());
        }

        $nombresCompletos = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
        ];

        $mesesNombres = [];
        for ($i = $mesInicio; $i <= $mesFin; $i++) {
            $mesesNombres[$i] = $nombresCompletos[$i];
        }


        $conDetalles = $request->con_detalles == "1";

        $tesoreros = collect($resultados); // Convertimos el array de resultados a Colección para poder usar ->where() en Blade
        $periodo = $nombresCompletos[$mesInicio] . ' a ' . $nombresCompletos[$mesFin] . ' ' . $anio;


        if ($request->action === 'pdf') {
            if ($tipoReporte === 'detalles') {
                $pdf = \PDF::loadView('pdf.pdf_tesorero_oro_con_estrellas', compact(
                    'tesoreros',
                    'conDetalles',
                    'mesesNombres',
                    'periodo',
                    'anio'
                ))->setPaper('a4', 'landscape');
            } else {
                $pdf = \PDF::loadView('pdf.pdf_tesorero_oro_lista', compact(
                    'tesoreros', 'periodo', 'anio'
                ))->setPaper('a4', 'portrait');
            }
            return $pdf->stream('Reporte_Tesoreros.pdf');
        }
    }
}
