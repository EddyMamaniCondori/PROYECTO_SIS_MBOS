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
        .reporte-titulo { font-size: 13px; font-weight: bold; color: #b8860b; }
        .reporte-fecha { font-size: 8px; color: #666; }
        
        .badge-ranking {
            display: inline-block;
            background-color: #b8860b; /* Color Oro */
            color: white;
            padding: 4px 12px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 11px;
            margin-top: 5px;
            text-transform: uppercase;
        }

        /* Caja de Resumen (Excelencia Distrital) */
        .caja-resumen {
            width: 100%;
            background: linear-gradient(to right, #0d47a1, #1565c0);
            color: white;
            border-radius: 10px;
            margin-bottom: 25px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .resumen-titulo { font-size: 11px; text-transform: uppercase; opacity: 0.9; font-weight: bold; }
        .resumen-valor { font-size: 26px; font-weight: bold; display: block; margin: 5px 0; }
        .resumen-sub { font-size: 9px; opacity: 0.8; }

        /* Tabla del Ranking */
        table.ranking-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { 
            background-color: #f1f4f9; 
            color: #0d47a1; 
            font-size: 9px; 
            text-transform: uppercase; 
            padding: 10px 5px; 
            border-bottom: 2px solid #0d47a1;
            text-align: left;
        }
        td { 
            font-size: 11px; 
            padding: 12px 8px; 
            border-bottom: 0.5px solid #eee; 
        }
        
        /* Estilos de Fila */
        .posicion { font-size: 14px; font-weight: bold; color: #0d47a1; text-align: center; }
        .distrito-nombre { font-size: 12px; font-weight: bold; color: #1a1a1a; text-transform: uppercase; }
        .pastor-info { font-size: 9px; color: #444; }
        .meta-cumplida { color: #2e7d32; font-weight: bold; font-size: 9px; }
        
        /* Desempate Cronológico */
        .fecha-final { color: #0d47a1; font-weight: bold; text-align: center; }
        .hora-final { 
            color: #b8860b; 
            font-weight: bold; 
            font-size: 13px; 
            text-align: center;
            display: block;
        }

        /* Resaltado Ganador */
        .oro-row { background-color: #fffde7; border: 1px solid #fff59d; }
        .medal-icon { width: 15px; vertical-align: middle; margin-right: 5px; }

        .footer-note { 
            margin-top: 30px; 
            padding: 10px; 
            background-color: #fdfdfd; 
            border: 1px dashed #ccc; 
            font-size: 8px; 
            color: #777; 
            border-radius: 5px;
        }
    </style>
</head>
<body>

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
                <div class="reporte-titulo">DISTRITOS DE ORO</div>
                <div class="reporte-fecha">Corte de Ranking: {{ date('d/m/Y H:i') }}</div>
                <div class="badge-ranking">Excelencia 100%</div>
            </td>
        </tr>
    </table>

    <div class="caja-resumen">
        <span class="resumen-titulo">Distritos que alcanzaron el 100% de Eficiencia</span>
        <span class="resumen-valor">{{ $ranking->count() }} Distritos</span>
        <span class="resumen-sub">Evaluados bajo el criterio de "Hora de última entrega recibida"</span>
    </div>

    <table class="ranking-table">
        <thead>
            <tr>
                <th width="8%" style="text-align: center;">POS</th>
                <th width="30%">DISTRITO / RESPONSABLES</th>
                <th width="15%" style="text-align: center;">IGLESIAS EN REGLA</th>
                <th width="20%" style="text-align: center;">FECHA DE CIERRE</th>
                <th width="15%" style="text-align: center;">HORA DE ORO</th>
                <th width="12%" style="text-align: center;">ESTADO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ranking as $index => $item)
            @php
                $pos = $index + 1;
                $fechaFull = \Carbon\Carbon::parse($item->fecha_finalizacion);
            @endphp
            <tr class="{{ $pos == 1 ? 'oro-row' : '' }}">
                <td class="posicion">
                    @if($pos == 1)
                        <span style="color: #b8860b;">🥇 1°</span>
                    @elseif($pos == 2)
                        <span style="color: #9e9e9e;">🥈 2°</span>
                    @elseif($pos == 3)
                        <span style="color: #cd7f32;">🥉 3°</span>
                    @else
                        {{ $pos }}°
                    @endif
                </td>
                <td>
                    <div class="distrito-nombre">{{ $item->distrito }}</div>
                    <div class="pastor-info">
                        <strong>Pastor:</strong> {{ $item->nombre_pas }}<br>
                        <strong>Encargado:</strong> {{ $item->nombre_res }}
                    </div>
                </td>
                <td style="text-align: center;">
                    <span class="meta-cumplida">
                        <i class="fas fa-check-circle"></i> {{ $item->iglesias_contadas }} / {{ $item->iglesias_contadas }}
                    </span>
                    <div style="font-size: 7px; color: #999;">TODAS ENTREGADAS</div>
                </td>
                <td class="fecha-final">
                    {{ $fechaFull->format('d/m/Y') }}
                </td>
                <td style="text-align: center;">
                    <span class="hora-final">{{ $fechaFull->format('H:i:s') }}</span>
                </td>
                <td style="text-align: center;">
                    <span style="background-color: #e8f5e9; color: #2e7d32; padding: 2px 8px; border-radius: 10px; font-size: 8px; font-weight: bold;">
                        COMPLETO
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-note">
        <strong>NOTA DE CALIFICACIÓN:</strong><br>
        1. Solo participan en este ranking los distritos que han reportado el <strong>100% de las remesas</strong> de todas sus iglesias, grupos y filiales.<br>
        2. En caso de empate en fecha, la posición se determina por la <strong>hora exacta</strong> de la última remesa recibida del distrito.<br>
        3. El "Distrito de Oro" es aquel que logra la sincronización total de sus tesorerías en el menor tiempo posible.
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