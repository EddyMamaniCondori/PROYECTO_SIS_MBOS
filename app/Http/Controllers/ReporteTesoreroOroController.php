<?php

namespace App\Http\Controllers;
use App\Models\Distrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\TesorerosOroEstrellasExport;
use App\Exports\TesorerosOroDetalladoExport;
use App\Exports\TesorerosOroListaExport;
use Maatwebsite\Excel\Facades\Excel;

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
        // ==========================================
        // 1. CAPTURA Y PARSEO DE DATOS (Orden del Frontend)

        // 1.1 Periodo
        $anio = 2026;
        $mesInicio = $request->periodo_inicio;
        $mesFin = $request->periodo_fin;

        // 1.2 Tipo Tesorero (Oro, Plata, Ambos)
        $tipoTesorero = $request->has('tipo_tesorero') && !empty($request->tipo_tesorero) ? implode(',', $request->tipo_tesorero) : 'todos';

        // 1.3 Alcance (MBOS, Distrito, Responsable, Lugar)
        $alcance = $request->alcance;

        $distritos = $request->has('distritos') && !empty($request->distritos) ? implode(',', $request->distritos) : '';
        $encargadoId = $request->encargado_id ?: 0;
        $regionZona = $request->region_zona ?: '';

        // 1.4 Tipo de Iglesia (Iglesia, Grupo, Filial, Todos)
        $tiposIglesia = $request->has('tipos_iglesia') && !empty($request->tipos_iglesia) ? implode(',', $request->tipos_iglesia) : 'Todos';

        // 1.5 Formato del Reporte
        $tipoReporte = $request->tipo_reporte ?: 'estrellas';

        // 1.6 Formato del Considera (True or False)
        $todasIglesias = $request->boolean('todas_iglesias');
        if ($todasIglesias === true) {
            $tipoTesorero = 'todas_iglesias';
        }

        // 1.7 Ordenamiento
        $ordenarPor = $request->ordenar_por ?: 'puntuacion';
        $ordenDir = strtoupper($request->orden_dir) === 'ASC' ? 'ASC' : 'DESC';

        if ($ordenarPor === 'puntuacion') {
            $ordenDir = ($ordenDir === 'ASC') ? 'DESC' : 'ASC';
        }

        // ==========================================
        // 2. EJECUCIÓN DEL PROCEDIMIENTO ALMACENADO
        // ==========================================

        $nombreCursor = 'cursor_reporte_' . uniqid();
        DB::beginTransaction();
        /*dd($anio, $mesInicio, $mesFin, $alcance, $distritos, $encargadoId, $regionZona, $tiposIglesia,
                $tipoTesorero, $ordenarPor, $ordenDir, $nombreCursor);*/
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

        // ==========================================
        // 3. PREPARACIÓN DE DATOS PARA LA VISTA
        // ==========================================

        $nombresCompletos = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
        ];

        $mesesNombres = [];
        for ($i = $mesInicio; $i <= $mesFin; $i++) {
            $mesesNombres[$i] = $nombresCompletos[$i];
        }

        $tesoreros = collect($resultados);
        $periodo = $nombresCompletos[$mesInicio] . ' a ' . $nombresCompletos[$mesFin] . ' ' . $anio;

        // ==========================================
        // 4. RENDERIZADO Y EXPORTACIÓN DEL PDF
        // ==========================================

        if ($request->action === 'pdf') {

            // 4.1. REPORTE ESTRELLAS (Solo íconos visuales)
            if ($tipoReporte === 'estrellas') {
                $conDetalles = false;
                $pdf = \PDF::loadView('pdf.pdf_tesorero_oro_con_estrellas', compact(
                    'tesoreros', 'conDetalles', 'mesesNombres', 'periodo', 'anio'
                ))->setPaper('a4', 'landscape');
            }
            // 4.2. REPORTE CON DETALLES (Íconos + Datos numéricos/fechas)
            elseif ($tipoReporte === 'detalles') {
                $conDetalles = true;
                // ¡AQUÍ APUNTAMOS AL NUEVO ARCHIVO DETALLADO!
                $pdf = \PDF::loadView('pdf.pdf_tesorero_oro_detallado', compact(
                    'tesoreros', 'conDetalles', 'mesesNombres', 'periodo', 'anio'
                ))->setPaper('a4', 'landscape');
            }
            // 4.3. REPORTE SOLO LISTA (Formato vertical limpio)
            elseif ($tipoReporte === 'lista') {
                $pdf = \PDF::loadView('pdf.pdf_tesorero_oro_lista', compact(
                    'tesoreros', 'periodo', 'anio'
                ))->setPaper('a4', 'portrait');
            }

            return $pdf->stream('Reporte_Tesoreros.pdf');
        }

        // ==========================================
        // 5. EXPORTACIÓN A EXCEL
        // ==========================================

        if ($request->action === 'excel') {

            if ($tipoReporte === 'estrellas') {
                return Excel::download(
                    new TesorerosOroEstrellasExport($tesoreros, $mesesNombres, $periodo, $tipoReporte),
                    'Reporte_Tesoreros_Estrellas_' . date('Ymd_His') . '.xlsx'
                );
            }
            elseif ($tipoReporte === 'detalles') {
                return Excel::download(
                    new TesorerosOroDetalladoExport($tesoreros, $mesesNombres, $periodo),
                    'Reporte_Tesoreros_Detallado_' . date('Ymd_His') . '.xlsx'
                );
            }
            elseif ($tipoReporte === 'lista') {
                return Excel::download(
                    // Pasamos únicamente $tesoreros, que es lo que pide el constructor
                    new TesorerosOroListaExport($tesoreros),
                    'Reporte_Tesoreros_Lista_' . date('Ymd_His') . '.xlsx'
                );
            }
        }
    }
}
