<?php

namespace App\Http\Controllers;
use App\Models\Iglesia;
use App\Models\Remesa;
use App\Models\RemesaFilial;
use App\Models\Genera;
use App\Models\RemesaSemanal;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\CalculaSaldoTrait;
use Barryvdh\DomPDF\Facade\Pdf;


class RemesaSemanalController extends Controller
{
    //
    use CalculaSaldoTrait;

    public function registroSemanas(Request $request, $id) {
    // Cargamos la remesa con sus semanas ya registradas (si las tiene)
        $remesa = Remesa::with('semanas')->findOrFail($id);
        //para boton volver.
        $mes = $request->query('mes'); 
        $anio = $request->query('anio');
        $datos = Genera::where('id_remesa', $id)->firstOrFail();

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $nombreMes = $meses[$datos->mes] ?? 'Mes no válido';
        $periodo = $nombreMes.' '.$datos->anio;
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
            ->get()
            ->keyBy('nro_semana');
        $gasto = 0;
        if($iglesia->tipo === 'Filial')
        {
            $remesa_filial = RemesaFilial::findOrFail($remesa->id_remesa);
            $gasto = $remesa_filial->gasto;
            
        }
        return view('remesas.remesas_semanales', compact('remesa', 'iglesia', 'semanasExistentes', 'periodo', 'mes', 'anio', 'gasto'));
        //dd($gasto, $mes, $anio, $datos, $nombreMes, $remesa, $id_iglesia, $iglesia, $semanasExistentes);
    }

    public function getDatosJSON($id) {
        $remesa = Remesa::with('semanas')->findOrFail($id);
        
        // Obtenemos la iglesia y el periodo (reutilizando tu lógica)
        $datosGenera = DB::table('generas')->where('id_remesa', $id)->first();
        
        return response()->json([
            'remesa' => $remesa,
            'semanas' => $remesa->semanas->keyBy('nro_semana'),
            'iglesia' => Iglesia::find($datosGenera->id_iglesia),
            'periodo' => $this->formatearPeriodo($datosGenera->mes, $datosGenera->anio)
        ]);
    }

    private function formatearPeriodo($mes, $anio) {
        $meses = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 
                7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];
        return ($meses[$mes] ?? 'Mes') . ' ' . $anio;
    }


    public function guardarTodo(Request $request, $id)
    {
        //dd($request);
        DB::beginTransaction();

        try {
            // 1. Limpiamos registros previos de semanas para este ID (para evitar duplicados al editar)
            DB::table('remesas_semanales')->where('id_remesa', $id)->delete();

            $totalMesDiezmo = 0;
            $totalMesOfrenda = 0;
            $totalMesPro = 0;

            // 2. Procesamos las 5 semanas
            for ($s = 1; $s <= 5; $s++) {
                
                // Recolectamos los totales de la cabecera de la semana
                $diezmoSemana = $request->input("resumen_diezmo.$s", 0);
                $ofrendaSemana = $request->input("resumen_ofrenda.$s", 0);
                $proSemana = $request->input("resumen_pro.$s", 0);

                // Preparamos el detalle JSON si existe el desglose
                $detalleJson = null;
                if ($request->has("semana.$s.diezmo")) {
                    $filas = [];
                    $diezmos = $request->input("semana.$s.diezmo");
                    $ofrendas = $request->input("semana.$s.ofrenda");
                    $pactos = $request->input("semana.$s.pacto");
                    $especiales = $request->input("semana.$s.especiales");
                    $pro_templo = $request->input("semana.$s.pro_templo");

                    foreach ($diezmos as $index => $val) {
                        // Solo guardamos filas que tengan al menos un valor mayor a 0
                        if ($val > 0 || $ofrendas[$index] > 0 || $pactos[$index] > 0 || $especiales[$index] > 0 || $pro_templo[$index] > 0) {
                            $filas[] = [
                                'diezmo'     => $val,
                                'ofrenda'    => $ofrendas[$index],
                                'pacto'      => $pactos[$index],
                                'especiales' => $especiales[$index],
                                'pro_templo' => $pro_templo[$index],
                            ];
                        }
                    }
                    $detalleJson = json_encode($filas);
                }

                // Guardamos en la tabla remesas_semanales
                DB::table('remesas_semanales')->insert([
                    'id_remesa'        => $id,
                    'nro_semana'       => $s,
                    'diezmo_total'     => $diezmoSemana,
                    'ofrenda_total'    => $ofrendaSemana,
                    'pro_templo_total' => $proSemana,
                    // Nota: en tu migración faltaba pacto y especiales como columnas, 
                    // pero si los tienes en la migración agrégalos aquí:
                    'pacto_total'      => $request->input("total_panto_$s", 0), 
                    'detalle_filas'    => $detalleJson,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                $totalMesDiezmo += $diezmoSemana;
                $totalMesOfrenda += $ofrendaSemana;
                $totalMesPro += $proSemana;
            }
            // 3. Actualizamos la tabla principal 'remesas'
            // Calculamos el monto final que va a la remesa MBOS (Diezmo + 40% Ofrenda)
            $montoRemesaFinal = $totalMesDiezmo + ($totalMesOfrenda * 0.40);
            $montoFondoLocalFinal = $totalMesPro + ($totalMesOfrenda * 0.60);
            //buscamos la iglesia


            $remesa = Remesa::findOrFail($id);


            $remesa->cierre         = $request->cierre;
            $remesa->deposito       = $request->deposito;
            $remesa->documentacion  = $request->documentacion;
            $remesa->escaneado      = $request->escaneado;
            $remesa->observacion    = $request->observaciones;
            $remesa->fecha_entrega  = $request->fecha_entrega;
            $remesa->sw_det_semana  = 1;
            $remesa->estado  = "ENTREGADO";

            $genera = Genera::where('id_remesa', $remesa->id_remesa)->firstOrFail();
            $mes_r = $genera->mes;
            $anio_r = $genera->anio;

            //calculo de estado de Dias
            $fechaEntrega = Carbon::parse($request->fecha_entrega);
            $fechaLimite = Carbon::parse($remesa->fecha_limite);

            $entregaSoloFecha = $fechaEntrega->copy()->startOfDay();
            $limiteSoloFecha = $fechaLimite->copy()->startOfDay();

            $diferencia = $entregaSoloFecha->diffInDays($limiteSoloFecha, false);

            
            if ($diferencia === 0) {
                $remesa->estado_dias = 'Completado con 0 días de retraso (entrega puntual)';
            } elseif ($diferencia > 0) {
                $remesa->estado_dias = "Completado con {$diferencia} día(s) de adelanto";
            } else {
                $remesa->estado_dias = "Entregado con " . abs($diferencia) . " día(s) de retraso";
            }
            $remesa->save();


            $id_iglesia = DB::table('remesas as xr')
                ->leftJoin('generas as xg', 'xg.id_remesa', '=', 'xr.id_remesa')
                ->where('xr.id_remesa', $id)
                ->value('xg.id_iglesia');

            $iglesia = Iglesia::where('id_iglesia', $id_iglesia)->firstOrFail();

            if($iglesia->tipo === 'Filial')
            {
                $remesa_filial = RemesaFilial::findOrFail($remesa->id_remesa);

                $remesa_filial->ofrenda         = $totalMesOfrenda;
                $remesa_filial->diezmo          = $totalMesDiezmo;
                $remesa_filial->pro_templo      = $totalMesPro;
                $remesa_filial->fondo_local     = $montoFondoLocalFinal;
                $remesa_filial->monto_remesa    = $montoRemesaFinal;
                $remesa_filial->gasto    = $request->gasto_mensual;
                $remesa_filial->save();

                $this->recalcularSaldosDesde($id_iglesia, $anio_r, $mes_r);
            }

            DB::commit();
          // Si la petición es AJAX (por el SweetAlert que pusimos antes)
            if ($request->ajax()) {
                return response()->json([
                    'status'  => 'success',
                    'message' => '¡Remesa guardada perfectamente!',
                ]);
            }

            return redirect()->route('remesas.registro_semanas', [
                    'id'   => $id, 
                    'mes'  => $request->input('mes'), // Asegúrate de tener estos nombres en tu form
                    'anio' => $request->input('anio')
                ])->with('success', 'Registro Semanal guardado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function generarReportePDF(Request $request, $id)
    {
        $cantidad = $request->query('cantidad_meses', 1);
        //obtnemos la remesa
        $remesa = Remesa::findOrFail($id);
        
        // Obtenemos info de la iglesia y periodo base
        $datosGenera = DB::table('generas')->where('id_remesa', $id)->first();
        $id_iglesia = $datosGenera->id_iglesia;

        $iglesia = Iglesia::join('distritos', 'iglesias.distrito_id', '=', 'distritos.id_distrito')
            ->where('iglesias.id_iglesia', $id_iglesia)
            ->select('iglesias.*', 'distritos.nombre as nombre_distrito')
            ->firstOrFail();

        $mesesNombres = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 
                        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];

        $periodoBase = $mesesNombres[$datosGenera->mes] . " " . $datosGenera->anio;

        if ($iglesia->tipo === 'Filial') {
            if ($cantidad == 1) {
                if($remesa->sw_det_semana == 1){
                     $semanas = DB::table('remesas_semanales')->where('id_remesa', $id)->orderBy('nro_semana', 'asc')->get();
                    $periodo = $mesesNombres[$datosGenera->mes] . " " . $datosGenera->anio;
                    //dd($semanas, $periodo);
                    return Pdf::loadView('pdf.reporte_remesa_semanal', compact('remesa', 'semanas', 'iglesia', 'periodo'))
                            ->stream("Reporte_{$iglesia->nombre}_{$periodo}.pdf");

                }else{
                    // 1. Reporte Mensual Único para Filial
                    $reporteMensual = DB::table('remesas_filiales')
                        
                        ->join('generas', 'remesas_filiales.id_remesa', '=', 'generas.id_remesa')
                        ->where('remesas_filiales.id_remesa', $id)
                        ->select(
                            'generas.mes',
                            'generas.anio',
                            'remesas_filiales.diezmo as diezmo_total',
                            'remesas_filiales.ofrenda as ofrenda_total',
                            'remesas_filiales.pro_templo as pro_templo_total'
                        )
                        ->get();
                        // compact('remesa', 'reporteMensual', 'iglesia', 'periodo', 'mesesNombres'
                    $periodo = $mesesNombres[$datosGenera->mes] . " " . $datosGenera->anio;
                    return Pdf::loadView('pdf.reporte_remesa_multimes', compact('remesa', 'reporteMensual', 'iglesia', 'periodo', 'mesesNombres'))
                        ->stream("Reporte_Filial_{$iglesia->nombre}_{$periodoBase}.pdf");
                }
            } else {
                // 2. Reporte Acumulado (Varios meses) para Filial
                $valorComparacion = ($datosGenera->anio * 100) + $datosGenera->mes;

                $remesasIds = DB::table('generas')
                    ->where('id_iglesia', $id_iglesia)
                    ->whereRaw("(anio * 100 + mes) <= ?", [$valorComparacion])
                    ->orderBy('anio', 'desc')
                    ->orderBy('mes', 'desc')
                    ->take($cantidad)
                    ->pluck('id_remesa');

                $reporteMensual = DB::table('remesas_filiales')
                    ->join('generas', 'remesas_filiales.id_remesa', '=', 'generas.id_remesa')
                    ->select(
                        'generas.mes',
                        'generas.anio',
                        'remesas_filiales.diezmo as diezmo_total',
                        'remesas_filiales.ofrenda as ofrenda_total',
                        'remesas_filiales.pro_templo as pro_templo_total'
                    )
                    ->whereIn('remesas_filiales.id_remesa', $remesasIds)
                    ->orderBy('generas.anio', 'asc')
                    ->orderBy('generas.mes', 'asc')
                    ->get();
                // compact('remesa', 'reporteMensual', 'iglesia', 'periodo', 'mesesNombres'
                $periodo = "Rango: " . $cantidad . " meses hasta " . $periodoBase;

                return Pdf::loadView('pdf.reporte_remesa_multimes', compact('remesa', 'reporteMensual', 'iglesia', 'periodo', 'mesesNombres'))
                    ->stream("Reporte_Acumulado_Filial_{$iglesia->nombre}.pdf");
            }
        }

        //dd($cantidad, $remesa, $datosGenera, $id_iglesia, $iglesia, $mesesNombres);
        if ($cantidad == 1) {
            // --- LOGICA MES UNICO (SEMANAL) ---
            $semanas = DB::table('remesas_semanales')->where('id_remesa', $id)->orderBy('nro_semana', 'asc')->get();
            $periodo = $mesesNombres[$datosGenera->mes] . " " . $datosGenera->anio;
            //dd($semanas, $periodo);
            return Pdf::loadView('pdf.reporte_remesa_semanal', compact('remesa', 'semanas', 'iglesia', 'periodo'))
                    ->stream("Reporte_{$iglesia->nombre}_{$periodo}.pdf");
        } else {

            $valorComparacion = ($datosGenera->anio * 100) + $datosGenera->mes;

            $remesasIds = DB::table('generas')
                ->where('id_iglesia', $id_iglesia)
                // Usamos una fórmula matemática: (2026 * 100 + 3) = 202603
                // Esto evita errores de LPAD y es compatible con cualquier tipo numérico
                ->whereRaw("(anio * 100 + mes) <= ?", [$valorComparacion])
                ->orderBy('anio', 'desc')
                ->orderBy('mes', 'desc')
                ->take($cantidad)
                ->pluck('id_remesa');

            $reporteMensual = DB::table('remesas_semanales')
                ->join('generas', 'remesas_semanales.id_remesa', '=', 'generas.id_remesa')
                ->select(
                    'generas.mes',
                    'generas.anio',
                    DB::raw('SUM(diezmo_total) as diezmo_total'),
                    DB::raw('SUM(ofrenda_total) as ofrenda_total'),
                    DB::raw('SUM(pro_templo_total) as pro_templo_total')
                )
                ->whereIn('remesas_semanales.id_remesa', $remesasIds)
                ->groupBy('generas.anio', 'generas.mes')
                ->orderBy('generas.anio', 'asc')
                ->orderBy('generas.mes', 'asc')
                ->get();

            $periodo = "Rango: " . $cantidad . " meses hasta " . $mesesNombres[$datosGenera->mes] . " " . $datosGenera->anio;
            //dd($valorComparacion, $remesasIds, $reporteMensual, $periodo);
            return Pdf::loadView('pdf.reporte_remesa_multimes', compact('remesa', 'reporteMensual', 'iglesia', 'periodo', 'mesesNombres'))
                    ->stream("Reporte_Acumulado_{$iglesia->nombre}.pdf");
        }
    }
}
