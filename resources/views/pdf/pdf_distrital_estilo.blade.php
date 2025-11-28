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
            font-size: 12px;
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

        /* Info row */
        .info-table {
            width: 100%;
            margin-bottom: 10px;
        }

        .info-table td {
            width: 50%;
            padding: 5px;
            font-size: 13px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th {
            background: #012a4a;
            color: white;
            padding: 7px;
            border: 1px solid #444;
            font-size: 12px;
        }

        td {
            padding: 6px;
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

<h2>Reporte Distrital de Remesas ({{ $anio }})</h2>

<table class="info-table">
    <tr>
        <td><strong>Fecha:</strong> {{ $fecha }}</td>
        <td><strong>Hora:</strong> {{ $hora }}</td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Distrito</th>
            <th>Desafío (Bs)</th>
            <th>Total Anual (Bs)</th>
            <th>Diferencia (Bs)</th>
        </tr>
    </thead>

    <tbody>
        @foreach($result as $i => $r)
            @php $dif = $r->total_anual - $r->blanco_monto; @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $r->nombre_distrito }}</td>
            <td>{{ number_format($r->blanco_monto, 0, ',', '.') }}</td>
            <td>{{ number_format($r->total_anual, 0, ',', '.') }}</td>
            <td style="color: {{ $dif < 0 ? 'red' : 'green' }}">
                {{ number_format($dif, 0, ',', '.') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
