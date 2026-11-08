<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.5cm 0.8cm; } /* Márgenes muy reducidos para ganar espacio */
        body { font-family: 'Helvetica', sans-serif; color: #1a1a1a; line-height: 1.2; }

        /* Encabezado Institucional */
        .header-table { width: 100%; border-bottom: 2px solid #0d47a1; padding-bottom: 5px; margin-bottom: 10px; }
        .col-logo { width: 8%; text-align: left; }
        .col-mision { width: 57%; text-align: left; padding-left: 10px; }
        .mision-nombre { font-size: 13px; font-weight: bold; color: #0d47a1; }
        .mision-sub { font-size: 8px; color: #555; text-transform: uppercase; letter-spacing: 0.5px; }
        .col-reporte { width: 35%; text-align: right; }
        .reporte-titulo { font-size: 12px; font-weight: bold; margin-bottom: 2px; color: #333; }
        .badge-periodo { display: inline-block; background-color: #e3f2fd; color: #0d47a1; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 10px; }

        /* Contenedor del Resumen Profesional */
        .resumen-container { width: 100%; margin-bottom: 15px; border-collapse: separate; border-spacing: 8px 0; }
        .card-resumen { width: 33.33%; background-color: #ffffff; border: 1.5px solid #0d47a1; border-radius: 6px; text-align: center; padding: 0; overflow: hidden; }
        .card-header { font-size: 9px; font-weight: bold; padding: 4px; text-transform: uppercase; color: white; }
        .card-body-amount { padding: 4px 5px; font-size: 18px; font-weight: 900; }

        .card-oro { border-color: #d97706; }
        .card-oro .card-header { background-color: #d97706; }
        .card-oro .card-body-amount { color: #b45309; }

        .card-plata { border-color: #64748b; }
        .card-plata .card-header { background-color: #64748b; }
        .card-plata .card-body-amount { color: #475569; }

        .card-bronce { border-color: #b45309; }
        .card-bronce .card-header { background-color: #b45309; }
        .card-bronce .card-body-amount { color: #78350f; }

        /* Tablas de Detalles Ultra Compacta */
        table.table-datos { width: 100%; border-collapse: collapse; table-layout: fixed; }
        /* Control de anchos: Si son 12 meses, queda muy poco espacio. Se fuerza ajuste. */
        table.table-datos th {
            background-color: #f1f5f9; color: #1e293b; font-size: 7.5px;
            text-transform: uppercase; padding: 4px 2px; border-bottom: 1.5px solid #cbd5e1;
            text-align: center;
        }
        table.table-datos td {
            padding: 4px 2px; border-bottom: 1px solid #e2e8f0;
            font-size: 7.5px; text-align: center; vertical-align: middle;
            word-wrap: break-word;
        }

        .text-left { text-align: left !important; }
        .col-fija { width: 10%; } /* Para Iglesia/Distrito */

        /* Pills de Categoría */
        .pill { display: inline-block; padding: 1px 4px; border-radius: 3px; font-size: 7px; font-weight: bold; text-transform: uppercase; }
        .pill-oro { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .pill-plata { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .pill-bronce { background-color: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }
        .pill-ninguno { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* Bloque de micro-datos (El Detalle) */
        .micro-datos { font-size: 6.5px; text-align: left; line-height: 1.1; margin-bottom: 2px; }
        .md-lbl { color: #64748b; font-weight: bold; }
        .md-val { color: #0f172a; }
        .md-time { color: #0284c7; font-weight: bold; }
    </style>
</head>
<body>

    @php
        // Contadores para las tarjetas
        $totalOro = $tesoreros->where('categoria', 'Oro')->count();
        $totalPlata = $tesoreros->where('categoria', 'Plata')->count();
        $totalOtros = $tesoreros->whereIn('categoria', ['Bronce', 'Ninguno'])->count();

        // Función Helper para convertir Segundos a D:H:M:S
        $formatTime = function($secs) {
            if ($secs === null || $secs === '') return '-';
            $isNegative = $secs < 0;
            $secs = abs((int)$secs);
            $d = floor($secs / 86400);
            $h = floor(($secs % 86400) / 3600);
            $m = floor(($secs % 3600) / 60);
            $s = $secs % 60;

            $sign = $isNegative ? '-' : '';
            return sprintf("%s%dd %02d:%02d:%02d", $sign, $d, $h, $m, $s);
        };
    @endphp

    <table class="header-table">
        <tr>
            <td class="col-logo">
                <img src="{{ public_path('img/logo_pdf.png') }}" width="45">
            </td>
            <td class="col-mision">
                <div class="mision-nombre">MISIÓN BOLIVIANA OCCIDENTAL SUR</div>
                <div class="mision-sub">Unión Boliviana - Iglesia Adventista del Séptimo Día</div>
            </td>
            <td class="col-reporte">
                <div class="reporte-titulo">
                    REPORTE {{ $conDetalles ? 'DETALLADO' : 'ESTRELLAS' }} TESOREROS
                </div>
                <div class="badge-periodo">{{ $periodo }}</div>
                <div style="font-size: 7px; color: #94a3b8; margin-top: 3px;">Impresión: {{ date('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <table class="resumen-container">
        <tr>
            <td class="card-resumen card-oro">
                <div class="card-header">Tesoreros de Oro</div>
                <div class="card-body-amount">{{ $totalOro }}</div>
            </td>
            <td class="card-resumen card-plata">
                <div class="card-header">Tesoreros de Plata</div>
                <div class="card-body-amount">{{ $totalPlata }}</div>
            </td>
            <td class="card-resumen card-bronce">
                <div class="card-header">Bronce / Ninguno</div>
                <div class="card-body-amount">{{ $totalOtros }}</div>
            </td>
        </tr>
    </table>

    <table class="table-datos">
        <thead>
            <tr>
                <th style="width: 2%;">#</th>
                <th class="text-left" style="width: 10%;">Iglesia (Cod - Nombre)</th>
                <th class="text-left" style="width: 8%;">Distrito</th>
                <th style="width: 5%;">Lugar</th>
                <th style="width: 5%;">Tipo</th>
                <th style="width: 5%;">Categoría</th>

                @foreach($mesesNombres as $numMes => $nombreMes)
                    <th>{{ $nombreMes }}</th>
                @endforeach

                @if($conDetalles)
                    <th style="width: 6%;">Total<br>Demora</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($tesoreros as $index => $t)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left"><strong>{{ $t->codigo_iglesia }}</strong><br>{{ $t->nombre_iglesia }}</td>
                <td class="text-left">{{ $t->nombre_distrito }}</td>
                <td>{{ $t->lugar }}</td>
                <td>{{ $t->tipo_iglesia }}</td>
                <td>
                    <span class="pill pill-{{ strtolower($t->categoria) }}">{{ $t->categoria }}</span>
                </td>

                @php
                    $detalles = is_string($t->detalles_meses) ? json_decode($t->detalles_meses) : $t->detalles_meses;
                @endphp

                @foreach($mesesNombres as $numMes => $nombreMes)
                    @php
                        $detalleMes = collect($detalles)->firstWhere('mes', $numMes);
                    @endphp

                    <td>
                        @if(!$detalleMes || is_null($detalleMes->fecha_entrega))
                            {{-- SIN ENVÍO --}}
                            @if($conDetalles) <div class="micro-datos text-center" style="color: #ef4444; margin-bottom: 2px;">Sin Envío</div> @endif
                            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGNpcmNsZSBjeD0iMTIiIGN5PSIxMiIgcj0iOSIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjZGMyNjI2IiBzdHJva2Utd2lkdGg9IjIiLz48bGluZSB4MT0iNiIgeTE9IjYiIHgyPSIxOCIgeTI9IjE4IiBzdHJva2U9IiNkYzI2MjYiIHN0cm9rZS13aWR0aD0iMiIvPjwvc3ZnPg==" width="12" height="12" />
                        @else
                            {{-- VISTA DETALLADA --}}
                            @if($conDetalles)
                                @php
                                    $segundosMes = strtotime($detalleMes->fecha_entrega) - strtotime($detalleMes->fecha_limite);
                                @endphp
                                <div class="micro-datos">
                                    <span class="md-lbl">Lím:</span> <span class="md-val">{{ \Carbon\Carbon::parse($detalleMes->fecha_limite)->format('d/m H:i') }}</span><br>
                                    <span class="md-lbl">Ent:</span> <span class="md-val">{{ \Carbon\Carbon::parse($detalleMes->fecha_entrega)->format('d/m H:i') }}</span><br>
                                    <span class="md-lbl">Ret:</span> <span class="md-val">{{ $detalleMes->dias_retraso }}d</span><br>
                                    <span class="md-time">{{ $formatTime($segundosMes) }}</span>
                                </div>
                            @endif

                            {{-- ESTRELLAS --}}
                            <div style="text-align: center;">
                                @if($detalleMes->estrellas == 2)
                                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0iI2VhYjMwOCIgc3Ryb2tlPSIjY2E4YTA0IiBzdHJva2Utd2lkdGg9IjEiIGQ9Ik0xMiAybDMuMDkgNi4yNkwyMiA5LjI3bC01IDQuODcgMS4xOCA2Ljg4TDEyIDE3Ljc3bC02LjE4IDMuMjVMNyAxNC4xNCAyIDkuMjdsNi45MS0xLjAxTDEyIDJ6Ii8+PC9zdmc+" width="12" height="12" />
                                @elseif($detalleMes->estrellas == 1)
                                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0iI2VhYjMwOCIgZD0iTTEyIDJsMy4wOSA2LjI2TDIyIDkuMjdsLTUgNC44NyAxLjE4IDYuODhMMTIgMTcuNzdWMnoiLz48cGF0aCBmaWxsPSJub25lIiBzdHJva2U9IiNjYThhMDQiIHN0cm9rZS13aWR0aD0iMS41IiBkPSJNMTIgMmwzLjA5IDYuMjZMMjIgOS4yN2wtNSA0Ljg3IDEuMTggNi44OEwxMiAxNy43N2wtNi4xOCAzLjI1TDcgMTQuMTQgMiA5LjI3bDYuOTEtMS4wMUwxMiAyeiIvPjwvc3ZnPg==" width="12" height="12" />
                                @else
                                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0ibm9uZSIgc3Ryb2tlPSIjY2E4YTA0IiBzdHJva2Utd2lkdGg9IjEuNSIgZD0iTTEyIDJsMy4wOSA2LjI2TDIyIDkuMjdsLTUgNC44NyAxLjE4IDYuODhMMTIgMTcuNzdsLTYuMTggMy4yNUw3IDE0LjE0IDIgOS4yN2w2LjkxLTEuMDFMMTIgMnoiLz48L3N2Zz4=" width="12" height="12" />
                                @endif
                            </div>
                        @endif
                    </td>
                @endforeach

                {{-- COLUMNA TOTAL FINAL (Solo visible si hay detalles) --}}
                @if($conDetalles)
                    <td style="font-weight: bold; color: #0d47a1;">
                        {{ $formatTime($t->total_segundos_demora) }}
                    </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
