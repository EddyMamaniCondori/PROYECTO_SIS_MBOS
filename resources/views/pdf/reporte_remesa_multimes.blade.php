<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.8cm 1.2cm; }
        body { font-family: 'Helvetica', sans-serif; color: #1a1a1a; line-height: 1.4; }
        
        /* Encabezado Institucional */
        .header-table { width: 100%; border-bottom: 2.5px solid #0d47a1; padding-bottom: 8px; margin-bottom: 20px; }
        .col-logo { width: 10%; text-align: left; }
        .col-mision { width: 55%; text-align: left; padding-left: 15px; }
        .mision-nombre { font-size: 14px; font-weight: bold; color: #0d47a1; }
        .mision-sub { font-size: 9px; color: #555; text-transform: uppercase; letter-spacing: 0.5px; }
        .col-reporte { width: 35%; text-align: right; }
        .reporte-titulo { font-size: 14px; font-weight: bold; margin-bottom: 3px; color: #333; }
        .badge-periodo { display: inline-block; background-color: #e3f2fd; color: #0d47a1; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 11px; }

        /* Bloque de Información de la Iglesia */
        .info-iglesia {
            background: #f8fafc;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        .label-pill {
            display: inline-block;
            font-size: 12px;
            background-color: #0d47a1;
            color: white;
            padding: 1px 6px;
            margin-top: 10px;
            border-radius: 4px;
            text-transform: uppercase;
            margin-left: 5px;
            vertical-align: middle;
        }

        .codigo-valor {
            font-family: 'Courier', monospace; /* Estilo de sistema para el código */
            font-size: 20px;
            color: #0d47a1;
            letter-spacing: 0px;
            font-weight: bold;
        }

        /* CAJA DE COBRO - PARA LA CAJERA */
        /* Contenedor del Resumen Profesional */
        .resumen-container {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: separate;
            border-spacing: 10px 0; /* Espacio entre las tarjetas */
        }

        .card-resumen {
            width: 33.33%;
            background-color: #ffffff;
            border: 1.5px solid #0d47a1;
            border-radius: 10px;
            text-align: center;
            padding: 0;
            overflow: hidden;
        }

        .card-header-blue {
            background-color: #0d47a1;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            padding: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-body-amount {
            padding: 8px 5px;
            color: #1e3a8a;
            font-size: 20px;
            font-weight: 900;
        }

        .card-footer-sub {
            font-size: 8px;
            color: #64748b;
            padding-bottom: 8px;
            text-transform: uppercase;
        }
        .card-mbos {
            border: 2px solid #0d47a1;
            background-color: #d7e9f0; /* Un azul muy tenue para diferenciarlo */
        }
        /* Resalte especial para la Remesa MBOS (El dato que cobra la cajera) */
        

        /* Tablas de Detalles */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f1f5f9; color: #475569; font-size: 9px; text-transform: uppercase; padding: 8px; border-bottom: 2px solid #cbd5e1; }
        td { padding: 8px; border-bottom: 1px solid #f1f5f9; font-size: 10px; text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .bg-light { background-color: #f8fafc; }

        .firmas-table { margin-top: 50px; width: 100%; border: none; }
        .firmas-table td { border: none; padding: 20px; vertical-align: top; }
        .linea-firma { border-top: 1px solid #333; margin-bottom: 5px; width: 180px; margin-left: auto; margin-right: auto; }
        .firma-label { font-size: 9px; color: #666; text-transform: uppercase; }
    </style>
</head>
<body>

    @php
        // Cálculos sobre la colección de meses
        $sumDiezmo = $reporteMensual->sum('diezmo_total');
        $sumOfrenda = $reporteMensual->sum('ofrenda_total');
        $sumProTemplo = $reporteMensual->sum('pro_templo_total');
        $totalGeneralBruto = $sumDiezmo + $sumOfrenda + $sumProTemplo;
        $remesaMbosFinal = $sumDiezmo + ($sumOfrenda * 0.40);
        $fondoLocalFinal = $sumProTemplo + ($sumOfrenda * 0.60);
    @endphp

    <table class="header-table">
        <tr>
            <td class="col-logo">
                <img src="{{ public_path('img/logo_pdf.png') }}" width="55">
            </td>
            <td class="col-mision">
                <div class="mision-nombre">MISIÓN BOLIVIANA OCCIDENTAL SUR</div>
                <div class="mision-sub">Unión Boliviana - Iglesia Adventista del Séptimo Día</div>
            </td>
            <td class="col-reporte">
                <div class="reporte-titulo">PLANILLA DE REMESA MENSUAL</div>
                <div class="badge-periodo">Periodo: {{ $periodo }}</div>
                <div style="font-size: 8px; color: #94a3b8; margin-top: 5px;">Cod: {{ $remesa->id_remesa }} | {{ date('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <div class="info-iglesia">
        <table style="margin-bottom: 0; border: none; width: 100%; border-collapse: collapse;">
            <tr>
                <td style="text-align: left; border: none; padding: 0; width: 60%; vertical-align: top;">
                    <span class="info-label">Cod ACMS/ Nombre de Iglesia / Tipo</span><br>
                    <span class="fw-bold codigo-valor">{{ $iglesia->codigo }}</span>&nbsp;/ &nbsp;
                    <span class="info-value" style="font-size: 15px;">{{ $iglesia->nombre }}</span>&nbsp;/ 
                    <span class="label-pill">{{ $iglesia->tipo }}</span> </td>

                <td style="text-align: left; border: none; padding: 0; width: 40%; vertical-align: top;">
                    <div style="margin-bottom: 8px;">
                        <span class="info-label">Distrito Misionero</span><br>
                        <span class="info-value" style="font-size: 15px;" >{{ $iglesia->nombre_distrito }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <table class="resumen-container">
        <tr>
            <td class="card-resumen">
                <div class="card-header-blue" style="background-color: #475569;">Total Acumulado</div>
                <div class="card-body-amount">Bs {{ number_format($totalGeneralBruto, 2) }}</div>
                <div class="card-footer-sub">Ingresos Brutos del Rango</div>
            </td>
            <td class="card-resumen">
                <div class="card-header-blue">Fondo Local Total</div>
                <div class="card-body-amount">Bs {{ number_format($fondoLocalFinal, 2) }}</div>
                <div class="card-footer-sub">Pro-Templo + 60% Ofrendas</div>
            </td>
            <td class="card-resumen card-mbos">
                <div class="card-header-blue">Remesa MBOS Total</div>
                <div class="card-body-amount" style="color: #0d47a1; font-size: 22px;">Bs {{ number_format($remesaMbosFinal, 2) }}</div>
                <div class="card-footer-sub" style="color: #0d47a1; font-weight: bold;">Diezmos + 40% Ofrendas</div>
            </td>
        </tr>
    </table>

    <div class="info-label" style="margin-left: 5px; margin-bottom: 5px;">Desglose por Meses Seleccionados</div>
    <table>
        <thead>
            <tr>
                <th>Mes / Año</th>
                <th>Diezmos</th>
                <th>Ofrendas</th>
                <th>40% Misión</th>
                <th>60% Local</th>
                <th>Pro-Templo</th>
                <th class="bg-light">Total Mes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reporteMensual as $mesData)
            @php $subTotal = $mesData->diezmo_total + $mesData->ofrenda_total + $mesData->pro_templo_total; @endphp
            <tr>
                <td style="text-align: left; font-weight: bold;">{{ $mesesNombres[$mesData->mes] }} - {{ $mesData->anio }}</td>
                <td>{{ number_format($mesData->diezmo_total, 2) }}</td>
                <td>{{ number_format($mesData->ofrenda_total, 2) }}</td>
                <td style="color: #b45309;">{{ number_format($mesData->ofrenda_total * 0.40, 2) }}</td>
                <td style="color: #0891b2;">{{ number_format($mesData->ofrenda_total * 0.60, 2) }}</td>
                <td>{{ number_format($mesData->pro_templo_total, 2) }}</td>
                <td class="bg-light fw-bold">Bs {{ number_format($subTotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot style="background-color: #f1f5f9; font-weight: bold;">
            <tr>
                <td>TOTALES</td>
                <td>{{ number_format($sumDiezmo, 2) }}</td>
                <td>{{ number_format($sumOfrenda, 2) }}</td>
                <td>{{ number_format($sumOfrenda * 0.4, 2) }}</td>
                <td>{{ number_format($sumOfrenda * 0.6, 2) }}</td>
                <td>{{ number_format($sumProTemplo, 2) }}</td>
                <td style="background-color: #1e3a8a; color: white;">Bs {{ number_format($totalGeneralBruto, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <table style="width: 100%; margin-top: 20px; border: none; border-collapse: collapse;">
        <tr>
            <td style="width: 45%; vertical-align: top; border: none;">
            </td>
            <td style="width: 45%; vertical-align: top; border: none;">
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                    <tr>
                        <td class="text-right info-label" style="padding: 8px; background: #ffffff;">Total Ingresos Iglesia:</td>
                        <td class="text-right fw-bold" style="padding: 8px; background: #ffffff;">Bs {{ number_format($totalGeneralBruto, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right info-label" style="padding: 8px; color: #ef4444; background: #ffffff;">(-) Gastos del Mes:</td>
                        <td class="text-right fw-bold" style="padding: 8px; color: #ef4444; background: #ffffff;">Bs {{ number_format($remesa->gasto_mensual, 2) }}</td>
                    </tr>
                    <tr style="background: #f1f5f9;">
                        <td class="text-right fw-bold" style="padding: 10px; font-size: 12px;">SALDO NETO REAL:</td>
                        <td class="text-right fw-bold" style="padding: 10px; font-size: 12px; color: #0d47a1;">Bs {{ number_format($totalGeneralBruto - $remesa->gasto_mensual, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="clear: both; height: 50px;"></div>

    <table class="firmas-table" style="width: 100%; border: none;">
        <tr>
            <td style="text-align: center; border: none; width: 33.3%;">
                <div class="linea-firma" style="border-top: 1px solid #333; width: 160px; margin: 0 auto 5px auto;"></div>
                <div class="firma-label">Tesorero(a) Iglesia</div>
            </td>
            <td style="text-align: center; border: none; width: 33.3%;">
                <div class="linea-firma" style="border-top: 1px solid #333; width: 160px; margin: 0 auto 5px auto;"></div>
                <div class="firma-label">Pastor de Distrito</div>
            </td>
            <td style="text-align: center; border: none; width: 33.3%;">
                <div class="linea-firma" style="border-top: 1px solid #333; width: 160px; margin: 0 auto 5px auto;"></div>
                <div class="firma-label">Sello Iglesia</div>
            </td>
        </tr>
    </table>
</body>
</html>