<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    

    <style>
        @page { margin: 0.8cm 1.2cm; }
        body { font-family: 'Helvetica', sans-serif; color: #1a1a1a; }
        .header { width: 100%; border-bottom: 2px solid #333; margin-bottom: 15px; }
        .titulo { font-size: 14px; font-weight: bold; }
        /* Contenedor del Encabezado */
        .header-container {
            width: 100%;
            border-bottom: 2.5px solid #0d47a1; /* Azul Institucional */
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        .header-table { width: 100%; border: none; border-collapse: collapse; }
    
        /* Columna Izquierda: Logo */
        .col-logo { width: 10%; text-align: left; vertical-align: middle; }
        
        /* Columna Central: Nombre Misión */
        .col-mision { 
            width: 55%; 
            text-align: left; 
            vertical-align: middle; 
            padding-left: 15px;
        }
        .mision-nombre { 
            font-size: 14px; 
            font-weight: bold; 
            color: #0d47a1;
            margin-bottom: 2px;
        }
        .mision-sub { font-size: 9px; color: #555; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Columna Derecha: Datos del Reporte */
        .col-reporte { width: 30%; text-align: right; vertical-align: middle; }
        .reporte-titulo { font-size: 13px; font-weight: bold; margin-bottom: 3px; }
        .reporte-fecha { font-size: 9px; color: #666; }
        .badge-periodo {
            display: inline-block;
            background-color: #e3f2fd;
            color: #0d47a1;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 5px;
            font-size: 10px;
        }
        /* Estilo de Agrupación */
        .distrito-header { 
            background-color: #f2f2f2; 
            padding: 5px; 
            font-weight: bold; 
            border-left: 4px solid #0d47a1;
            margin-top: 15px;
            font-size: 11px;
            text-transform: uppercase;
        }

        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th { border-bottom: 1px solid #000; padding: 5px; text-align: left; color: #555; font-size: 11px; }
        td { padding: 5px; border-bottom: 0.5px solid #eee; font-size: 11px; }
        
        .estado-entregado { color: green; font-weight: bold; }
        .estado-pendiente { color: red; font-weight: bold; }
        .text-right { text-align: right; }

        .fila-filial {
            color: #dc3545; /* Rojo de Bootstrap */
        }

    </style>
</head>
<body>
    @php
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
    @endphp
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
                <div class="reporte-titulo">ESTADO DE REMESAS</div>
                <div class="reporte-fecha">Emitido: {{ date('d/m/Y H:i') }}</div>
                <div class="badge-periodo">
                    Periodo: {{ $periodo }}
                </div>
            </td>
        </tr>
    </table>

    <div class="linea-separadora"></div>
    @foreach($datos as $distrito => $registros)
        <div class="distrito-header">Distrito: {{ $distrito }}</div>
        <table>
            <thead>
                <tr>
                    <th width="10%">Código</th>
                    <th width="35%">Iglesia</th>
                    <th width="15%">Tipo</th>
                    <th width="15%">Pastor</th>
                    <th width="15%">Responsable</th>
                    <th width="10%">Mes</th>
                    <th width="10%">Año</th>
                    <th width="20%" class="text-right">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registros as $row)
                <tr class="{{ trim(strtolower($row->tipo)) == 'filial' ? 'fila-filial' : '' }}">
                    <td>{{ $row->codigo }}</td>
                    <td>{{ $row->nombre_igle }}</td>
                    <td>{{ $row->tipo }}</td>
                    <td>{{ $row->nombre_pas }}</td>
                    <td>{{ $row->nombre_res }}</td>
                    <td>{{ $meses[(int)$row->mes] }}</td>
                    <td>{{ $row->anio }}</td>
                    <td class="text-right">
                        <span class="{{ $row->estado == 'ENTREGADO' ? 'estado-entregado' : 'estado-pendiente' }}">
                            {{ $row->estado }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    
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