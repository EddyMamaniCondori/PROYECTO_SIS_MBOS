<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1cm 1.2cm; }
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

        /* Tablas de Detalles */
        table.table-datos { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.table-datos th { background-color: #f1f5f9; color: #475569; font-size: 9px; text-transform: uppercase; padding: 8px; border-bottom: 2px solid #cbd5e1; text-align: center; }
        table.table-datos td { padding: 8px 4px; border-bottom: 1px solid #e2e8f0; font-size: 10px; text-align: center; vertical-align: middle; }

        .text-left { text-align: left !important; }

        /* Pills de Categoría */
        .pill { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .pill-oro { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .pill-plata { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .pill-bronce { background-color: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }
        .pill-ninguno { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
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
                <div class="reporte-titulo">REPORTE RESUMIDO TESOREROS</div>
                <div class="badge-periodo">{{ $periodo }}</div>
                <div style="font-size: 8px; color: #94a3b8; margin-top: 5px;">Fecha Impresión: {{ date('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <table class="table-datos">
        <thead>
            <tr>
                <th width="5%">Nro</th>
                <th class="text-left" width="30%">Iglesia (Cod - Nombre)</th>
                <th class="text-left" width="20%">Distrito</th>
                <th width="10%">Tipo</th>
                <th width="11%">Categoría</th>
                <th width="12%">Recepciona</th>
                <th width="12%">Recibido</th>
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
                <td></td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
