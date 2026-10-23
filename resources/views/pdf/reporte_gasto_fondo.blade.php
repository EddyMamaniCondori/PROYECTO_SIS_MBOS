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
        .info-iglesia { background: #f8fafc; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e2e8f0; }
        .label-pill { display: inline-block; font-size: 12px; background-color: #0d47a1; color: white; padding: 1px 6px; margin-top: 10px; border-radius: 4px; text-transform: uppercase; margin-left: 5px; vertical-align: middle; }
        .codigo-valor { font-family: 'Courier', monospace; font-size: 20px; color: #0d47a1; letter-spacing: 0px; font-weight: bold; }
        .info-label { font-size: 10px; font-weight: bold; color: #64748b; text-transform: uppercase; }
        .info-value { font-weight: bold; color: #1e293b; }

        /* Contenedor del Resumen Profesional */
        .resumen-container { width: 100%; margin-bottom: 30px; border-collapse: separate; border-spacing: 10px 0; }
        .card-resumen { width: 33.33%; background-color: #ffffff; border: 1.5px solid #0d47a1; border-radius: 10px; text-align: center; padding: 0; overflow: hidden; }
        .card-header-blue { background-color: #0d47a1; color: #ffffff; font-size: 9px; font-weight: bold; padding: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .card-body-amount { padding: 8px 5px; color: #1e3a8a; font-size: 20px; font-weight: 900; }
        .card-footer-sub { font-size: 8px; color: #64748b; padding-bottom: 8px; text-transform: uppercase; }

        /* Tarjeta especial para Gastos */
        .card-danger { border: 1.5px solid #dc2626; }
        .card-header-danger { background-color: #dc2626; color: #ffffff; font-size: 9px; font-weight: bold; padding: 6px; text-transform: uppercase; }
        .card-amount-danger { padding: 8px 5px; color: #dc2626; font-size: 20px; font-weight: 900; }

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
        // Cálculos sobre la colección de meses obtenidos en el controlador
        $sumOfrenda = $reporteMensual->sum('ofrenda_total');
        $sumProTemplo = $reporteMensual->sum('pro_templo_total');
        $sumGasto = $reporteMensual->sum('gasto_mensual');

        $totalFondoLocal = $sumProTemplo + ($sumOfrenda * 0.60);
        $saldoNeto = $totalFondoLocal - $sumGasto;
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
                <div class="reporte-titulo">REPORTE FONDO LOCAL Y GASTOS</div>
                <div class="badge-periodo">{{ $periodo }}</div>
                <div style="font-size: 8px; color: #94a3b8; margin-top: 5px;">Fecha Impresión: {{ date('d/m/Y H:i') }}</div>
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
                    <span class="label-pill">{{ $iglesia->tipo }}</span>
                </td>
                <td style="text-align: left; border: none; padding: 0; width: 40%; vertical-align: top;">
                    <div style="margin-bottom: 4px;">
                        <span class="info-label">Distrito Misionero</span><br>
                        <span class="info-value" style="font-size: 11px;" >{{ $iglesia->nombre_distrito }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="resumen-container">
        <tr>
            <td class="card-resumen">
                <div class="card-header-blue" style="background-color: #475569;">Fondo Local Total</div>
                <div class="card-body-amount">Bs {{ number_format($totalFondoLocal, 2) }}</div>
                <div class="card-footer-sub">Pro-Templo + 60% Ofrendas</div>
            </td>
            <td class="card-resumen card-danger">
                <div class="card-header-danger">Gasto Total</div>
                <div class="card-amount-danger">Bs {{ number_format($sumGasto, 2) }}</div>
                <div class="card-footer-sub">Gastos Ejecutados en Rango</div>
            </td>
            <td class="card-resumen" style="border: 2px solid #047857; background-color: #ecfdf5;">
                <div class="card-header-blue" style="background-color: #047857;">Saldo Fondo Local</div>
                <div class="card-body-amount" style="color: #047857; font-size: 22px;">Bs {{ number_format($saldoNeto, 2) }}</div>
                <div class="card-footer-sub" style="color: #047857; font-weight: bold;">Ingreso F.L. (-) Gastos</div>
            </td>
        </tr>
    </table>

    <div class="info-label" style="margin-left: 5px; margin-bottom: 5px;">Desglose Histórico por Meses</div>
    <table>
        <thead>
            <tr>
                <th>Mes / Año</th>
                <th>Ofrendas (100%)</th>
                <th style="color: #0d47a1;">Fondo Local (60% Of.)</th>
                <th style="color: #0d47a1;">Pro-Templo</th>
                <th class="bg-light fw-bold">Total Ingreso F.L.</th>
                <th style="color: #dc2626;">(-) Gastos</th>
                <th class="bg-light fw-bold" style="color: #047857;">Saldo Final</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reporteMensual as $mesData)
            @php
                $ingresoFondoLocal = ($mesData->ofrenda_total * 0.60) + $mesData->pro_templo_total;
                $saldoMes = $ingresoFondoLocal - $mesData->gasto_mensual;
            @endphp
            <tr>
                <td style="text-align: left; font-weight: bold;">{{ $mesesNombres[$mesData->mes] }} {{ $mesData->anio }}</td>
                <td>{{ number_format($mesData->ofrenda_total, 2) }}</td>
                <td style="color: #0d47a1;">{{ number_format($mesData->ofrenda_total * 0.60, 2) }}</td>
                <td style="color: #0d47a1;">{{ number_format($mesData->pro_templo_total, 2) }}</td>
                <td class="bg-light fw-bold">Bs {{ number_format($ingresoFondoLocal, 2) }}</td>
                <td style="color: #dc2626;">{{ number_format($mesData->gasto_mensual, 2) }}</td>
                <td class="bg-light fw-bold" style="{{ $saldoMes < 0 ? 'color: #dc2626;' : 'color: #047857;' }}">
                    Bs {{ number_format($saldoMes, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot style="background-color: #f1f5f9; font-weight: bold;">
            <tr>
                <td>TOTALES</td>
                <td>{{ number_format($sumOfrenda, 2) }}</td>
                <td>{{ number_format($sumOfrenda * 0.6, 2) }}</td>
                <td>{{ number_format($sumProTemplo, 2) }}</td>
                <td style="background-color: #1e3a8a; color: white;">Bs {{ number_format($totalFondoLocal, 2) }}</td>
                <td style="background-color: #dc2626; color: white;">Bs {{ number_format($sumGasto, 2) }}</td>
                <td style="background-color: {{ $saldoNeto < 0 ? '#dc2626' : '#047857' }}; color: white;">Bs {{ number_format($saldoNeto, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="clear: both; height: 30px;"></div>

    <table class="firmas-table" style="width: 100%; border: none;">
        <tr>
            <td style="border: none; width: 33.3%;"></td>
            <td style="border: none; width: 33.3%;"></td>
            <td style="text-align: center; border: none; width: 33.3%;">
                <div class="linea-firma" style="border-top: 1px solid #000; margin-bottom: 5px;"></div>
                <div class="firma-label">Analista Tesoreria</div>
            </td>
        </tr>
    </table>
</body>
</html>
