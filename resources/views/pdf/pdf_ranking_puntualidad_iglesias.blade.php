<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.8cm 1.2cm; }
        body { font-family: 'Helvetica', sans-serif; color: #1a1a1a; line-height: 1.4; }
        
        /* Encabezado Institucional */
        .header-table { width: 100%; border-bottom: 2.5px solid #0d47a1; padding-bottom: 8px; margin-bottom: 10px; }
        .col-logo { width: 10%; text-align: left; vertical-align: middle; }
        .col-mision { width: 55%; text-align: left; vertical-align: middle; padding-left: 15px; }
        .mision-nombre { font-size: 14px; font-weight: bold; color: #0d47a1; }
        .mision-sub { font-size: 9px; color: #555; text-transform: uppercase; }

        .col-reporte { width: 35%; text-align: right; vertical-align: middle; }
        .reporte-titulo { font-size: 13px; font-weight: bold; color: #b8860b; } /* Color Oro para el Ranking */
        .reporte-fecha { font-size: 8px; color: #666; }
        
        .badge-periodo {
            display: inline-block;
            background-color: #0d47a1;
            color: white;
            padding: 3px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
            margin-top: 5px;
        }

        /* Caja de Resumen (Estilo Excelencia) */
        .caja-resumen {
            width: 100%;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 4 px;
            text-align: center;
        }
        .resumen-titulo { font-size: 10px; text-transform: uppercase; color: #666; font-weight: bold; margin-bottom: 5px; }
        .resumen-valor { font-size: 22px; font-weight: bold; color: #0d47a1; display: block; }
        
        .resumen-detalles { width: 100%; margin-top: 2px; border-top: 1px dashed #ccc; padding-top: 2px; }
        .col-detalle { width: 20%; display: inline-block; }
        .det-num { font-size: 12px; font-weight: bold; color: #333; }
        .det-label { font-size: 7px; color: #888; text-transform: uppercase; }

        /* Estilo de la Tabla Ranking */
        table.ranking-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { 
            background-color: #0d47a1; 
            color: white; 
            font-size: 9px; 
            text-transform: uppercase; 
            padding: 8px 5px; 
            border: 0.5px solid #0d47a1;
        }
        td { 
            font-size: 10px; 
            padding: 7px 5px; 
            border-bottom: 0.5px solid #ddd; 
            text-align: left;
        }
        
        /* Resaltados */
        .posicion { font-size: 12px; font-weight: bold; color: #0d47a1; text-align: center; }
        .codigo-igle { color: #666; font-family: monospace; }
        .nombre-igle { font-weight: bold; color: #1a1a1a; }
        .font-bold { font-weight: bold; }
        .fecha-destacada { color: #0d47a1; font-weight: bold; }
        .hora-destacada { color: #b8860b; font-weight: bold; } /* Hora en color oro/café para destacar */

        /* Filas Especiales */
        .top-1 { background-color: #fff9c4; } /* Oro suave para el 1er lugar */
        .fila-filial { color: #dc3545; } /* Rojo para filiales */

        .footer-page { font-size: 8px; text-align: center; color: #999; margin-top: 20px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="col-logo">
                <img src="{{ public_path('img/logo_pdf.png') }}" width="50">
            </td>
            <td class="col-mision">
                <div class="mision-nombre">MISIÓN BOLIVIANA OCCIDENTAL SUR</div>
                <div class="mision-sub">Unión Boliviana - Iglesia Adventista del Séptimo Día</div>
            </td>
            <td class="col-reporte">
                <div class="reporte-titulo"><i class="fas fa-trophy"></i> RANKING DE PUNTUALIDAD</div>
                <div class="reporte-fecha">Generado: {{ date('d/m/Y H:i:s') }}</div>
                <div class="badge-periodo">PUNTUALIDAD DE ORO</div>
            </td>
        </tr>
    </table>

    <div class="caja-resumen">
        <span class="resumen-titulo">Total Iglesias Evaluadas en el Ranking</span>
        <span class="resumen-valor">{{ $resultados->count() }}</span>
        
        <div class="resumen-detalles">
            <div class="col-detalle">
                <span class="det-num">{{ $resultados->where('tipo', 'Iglesia')->count() }}</span><br>
                <span class="det-label">Iglesias</span>
            </div>
            <div class="col-detalle">
                <span class="det-num">{{ $resultados->where('tipo', 'Grupo')->count() }}</span><br>
                <span class="det-label">Grupos</span>
            </div>
            <div class="col-detalle">
                <span class="det-num">{{ $resultados->where('tipo', 'Filial')->count() }}</span><br>
                <span class="det-label">Filiales</span>
            </div>
        </div>
    </div>

    <table class="ranking-table">
        <thead>
            <tr>
                <th width="5%">POS</th>
                <th width="10%">CÓDIGO</th>
                <th width="20%">IGLESIA / GRUPO</th>
                <th width="15%">DISTRITO</th>
                <th width="15%">PASTOR / RESPONSABLE</th>
                <th width="12%">FECHA ENTREGA</th>
                <th width="10%">HORA</th>
                <th width="8%">TIPO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resultados as $index => $row)
            @php
                // Parseamos la fecha y hora para que no haya errores
                $fechaFull = \Carbon\Carbon::parse($row->fecha_entrega);
            @endphp
            <tr class="{{ $index == 0 ? 'top-1' : '' }} {{ trim(strtolower($row->tipo)) == 'filial' ? 'fila-filial' : '' }}">
                <td class="posicion">{{ $index + 1 }}°</td>
                <td class="codigo-igle">{{ $row->codigo }}</td>
                <td class="nombre-igle">{{ $row->nombre_igle }}</td>
                <td>{{ $row->distrito }}</td>
                <td style="font-size: 8px;">
                    <strong>P:</strong> {{ $row->nombre_pas }}<br>
                    <strong>R:</strong> {{ $row->nombre_res }}
                </td>
                <td class="fecha-destacada">{{ $fechaFull->format('d/m/Y') }}</td>
                <td class="hora-destacada">{{ $fechaFull->format('H:i:s') }}</td>
                <td style="text-transform: uppercase; font-size: 8px;">{{ $row->tipo }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-page">
        * El ranking se calcula basándose en la fecha y hora exacta de registro en el sistema MBOS.
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $font = $fontMetrics->getFont("Helvetica", "bold");
            $pdf->page_text(520, 750, $text, $font, 7, array(0,0,0));
        }
    </script>
</body>
</html>