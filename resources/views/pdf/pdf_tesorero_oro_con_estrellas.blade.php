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

        /* Contenedor del Resumen Profesional */
        .resumen-container { width: 100%; margin-bottom: 20px; border-collapse: separate; border-spacing: 10px 0; }
        .card-resumen { width: 33.33%; background-color: #ffffff; border: 1.5px solid #0d47a1; border-radius: 10px; text-align: center; padding: 0; overflow: hidden; }
        .card-header { font-size: 10px; font-weight: bold; padding: 6px; text-transform: uppercase; letter-spacing: 0.5px; color: white; }
        .card-body-amount { padding: 8px 5px; font-size: 22px; font-weight: 900; }

        /* Colores de las Cards */
        .card-oro { border-color: #d97706; }
        .card-oro .card-header { background-color: #d97706; }
        .card-oro .card-body-amount { color: #b45309; }

        .card-plata { border-color: #64748b; }
        .card-plata .card-header { background-color: #64748b; }
        .card-plata .card-body-amount { color: #475569; }

        .card-bronce { border-color: #b45309; }
        .card-bronce .card-header { background-color: #b45309; }
        .card-bronce .card-body-amount { color: #78350f; }

        /* Tablas de Detalles */
        table.table-datos { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.table-datos th { background-color: #f1f5f9; color: #475569; font-size: 8px; text-transform: uppercase; padding: 6px; border-bottom: 2px solid #cbd5e1; text-align: center; }
        table.table-datos td { padding: 6px 4px; border-bottom: 1px solid #e2e8f0; font-size: 9px; text-align: center; vertical-align: middle; }

        .text-left { text-align: left !important; }

        /* Pills de Categoría */
        .pill { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .pill-oro { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .pill-plata { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .pill-bronce { background-color: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }
        .pill-ninguno { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .icon-svg { vertical-align: middle; width: 14px; height: 14px; }
    </style>
</head>
<body>

    @php
        // Contamos las categorías directamente desde la colección que envíe el controlador
        $totalOro = $tesoreros->where('categoria', 'Oro')->count();
        $totalPlata = $tesoreros->where('categoria', 'Plata')->count();
        // Agrupamos Bronce y Ninguno en la tercera tarjeta según tu solicitud
        $totalOtros = $tesoreros->whereIn('categoria', ['Bronce', 'Ninguno'])->count();
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
                <div class="reporte-titulo">REPORTE DETALLADO TESOREROS</div>
                <div class="badge-periodo">{{ $periodo }}</div>
                <div style="font-size: 8px; color: #94a3b8; margin-top: 5px;">Fecha Impresión: {{ date('d/m/Y H:i') }}</div>
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
                <th width="3%">Nro</th>
                <th class="text-left" width="25%">Iglesia (Cod - Nombre)</th>
                <th class="text-left" width="15%">Distrito</th>
                <th width="7%">Tipo</th>
                <th width="10%">Categoría</th>

                {{-- Imprimimos las columnas de los meses dinámicamente --}}
                @foreach($mesesNombres as $numMes => $nombreMes)
                    <th>{{ $nombreMes }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($tesoreros as $index => $t)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left"><strong>{{ $t->codigo_iglesia }}</strong> - {{ $t->nombre_iglesia }}</td>
                <td class="text-left">{{ $t->nombre_distrito }}</td>
                <td>{{ $t->tipo_iglesia }}</td>
                <td>
                    <span class="pill pill-{{ strtolower($t->categoria) }}">{{ $t->categoria }}</span>
                </td>

                @php
                    // Decodificamos el JSON que viene de la BD (si viene como string)
                    $detalles = is_string($t->detalles_meses) ? json_decode($t->detalles_meses) : $t->detalles_meses;
                @endphp

                @foreach($mesesNombres as $numMes => $nombreMes)
                    @php
                        // Buscamos el mes específico en el JSON
                        $detalleMes = collect($detalles)->firstWhere('mes', $numMes);
                    @endphp

                    <td>
                        @if(!$detalleMes || is_null($detalleMes->fecha_entrega))
                            {{-- NULL / NO ENTREGÓ: Círculo rojo (Base64) --}}
                            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGNpcmNsZSBjeD0iMTIiIGN5PSIxMiIgcj0iOSIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjZGMyNjI2IiBzdHJva2Utd2lkdGg9IjIiLz48bGluZSB4MT0iNiIgeTE9IjYiIHgyPSIxOCIgeTI9IjE4IiBzdHJva2U9IiNkYzI2MjYiIHN0cm9rZS13aWR0aD0iMiIvPjwvc3ZnPg==" width="14" height="14" />

                        @elseif($detalleMes->estrellas == 2)
                            {{-- 2 PTS (ESTRELLA LLENA DORADA - Base64) --}}
                            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0iI2VhYjMwOCIgc3Ryb2tlPSIjY2E4YTA0IiBzdHJva2Utd2lkdGg9IjEiIGQ9Ik0xMiAybDMuMDkgNi4yNkwyMiA5LjI3bC01IDQuODcgMS4xOCA2Ljg4TDEyIDE3Ljc3bC02LjE4IDMuMjVMNyAxNC4xNCAyIDkuMjdsNi45MS0xLjAxTDEyIDJ6Ii8+PC9zdmc+" width="14" height="14" />

                        @elseif($detalleMes->estrellas == 1)
                            {{-- 1 PT (MEDIA ESTRELLA DORADA - Base64) --}}
                            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0iI2VhYjMwOCIgZD0iTTEyIDJsMy4wOSA2LjI2TDIyIDkuMjdsLTUgNC44NyAxLjE4IDYuODhMMTIgMTcuNzdWMnoiLz48cGF0aCBmaWxsPSJub25lIiBzdHJva2U9IiNjYThhMDQiIHN0cm9rZS13aWR0aD0iMS41IiBkPSJNMTIgMmwzLjA5IDYuMjZMMjIgOS4yN2wtNSA0Ljg3IDEuMTggNi44OEwxMiAxNy43N2wtNi4xOCAzLjI1TDcgMTQuMTQgMiA5LjI3bDYuOTEtMS4wMUwxMiAyeiIvPjwvc3ZnPg==" width="14" height="14" />

                        @else
                            {{-- 0 PTS (ESTRELLA VACÍA DORADA - Base64) --}}
                            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0ibm9uZSIgc3Ryb2tlPSIjY2E4YTA0IiBzdHJva2Utd2lkdGg9IjEuNSIgZD0iTTEyIDJsMy4wOSA2LjI2TDIyIDkuMjdsLTUgNC44NyAxLjE4IDYuODhMMTIgMTcuNzdsLTYuMTggMy4yNUw3IDE0LjE0IDIgOS4yN2w2LjkxLTEuMDFMMTIgMnoiLz48L3N2Zz4=" width="14" height="14" />
                        @endif
                    </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
