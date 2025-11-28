<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 120px 30px 80px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        /* HEADER */
        .header {
            position: fixed;
            top: -90px;
            left: 0;
            right: 0;
            height: 80px;
            background-color: #012a4a;
            color: white;
            text-align: center;
            padding-top: 10px;
        }

        .header img {
            position: absolute;
            left: 20px;
            top: 10px;
            width: 60px;
        }

        .header-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
        }

        /* FOOTER */
        .footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 40px;
            background: #012a4a;
            color: white;
            text-align: center;
            padding-top: 10px;
            font-size: 12px;
        }

        /* Title */
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        /* Info */
        .info-table {
            width: 100%;
            margin-bottom: 10px;
        }

        .info-table td {
            width: 50%;
            padding: 5px;
            font-size: 12px;
        }

        /* Main table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th {
            background: #012a4a;
            color: white;
            padding: 6px;
            border: 1px solid #444;
            font-size: 11px;
        }

        td {
            padding: 5px;
            border: 1px solid #888;
        }
    </style>
</head>

<body>

<div class="header">
    <img src="{{ public_path('img/logoiasd.png') }}" alt="Logo">
    <div class="header-title">MISION BOLIVIANA OCCIDENTAL SUR</div>
</div>

<div class="footer">
    Sistema de Gestión Institucional - Misión Boliviana Occidental Sur
</div>

<h2>Reporte Mensual de Remesas de Filiales ({{ $anio }})</h2>

<table class="info-table">
    <tr>
        <td><strong>Fecha:</strong> {{ $fecha }}</td>
        <td><strong>Hora:</strong> {{ $hora }}</td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>Código</th>
            <th>Iglesia</th>
            <th>Tipo</th>
            <th>Distrito</th>

            <th>Ene</th><th>Feb</th><th>Mar</th><th>Abr</th>
            <th>May</th><th>Jun</th><th>Jul</th><th>Ago</th>
            <th>Sep</th><th>Oct</th><th>Nov</th><th>Dic</th>

            <th>Total</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($result as $r)
            <tr>
                <td>{{ $r->codigo }}</td>
                <td>{{ $r->nombre }}</td>
                <td>{{ $r->tipo }}</td>
                <td>{{ $r->distrito }}</td>

                <td>{{ number_format($r->enero, 0, ',', '.') }}</td>
                <td>{{ number_format($r->febrero, 0, ',', '.') }}</td>
                <td>{{ number_format($r->marzo, 0, ',', '.') }}</td>
                <td>{{ number_format($r->abril, 0, ',', '.') }}</td>
                <td>{{ number_format($r->mayo, 0, ',', '.') }}</td>
                <td>{{ number_format($r->junio, 0, ',', '.') }}</td>
                <td>{{ number_format($r->julio, 0, ',', '.') }}</td>
                <td>{{ number_format($r->agosto, 0, ',', '.') }}</td>
                <td>{{ number_format($r->septiembre, 0, ',', '.') }}</td>
                <td>{{ number_format($r->octubre, 0, ',', '.') }}</td>
                <td>{{ number_format($r->noviembre, 0, ',', '.') }}</td>
                <td>{{ number_format($r->diciembre, 0, ',', '.') }}</td>

                <td><strong>{{ number_format($r->total_anual, 0, ',', '.') }}</strong></td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
