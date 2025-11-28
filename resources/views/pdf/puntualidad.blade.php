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

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        /* Info Row */
        .info-table {
            width: 100%;
            margin-bottom: 10px;
        }
        .info-table td {
            width: 50%;
            font-size: 13px;
            padding: 5px;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th {
            background: #012a4a;
            color: white;
            padding: 6px;
            border: 1px solid #333;
        }

        td {
            border: 1px solid #999;
            padding: 5px;
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
    </style>
</head>
<body>

<div class="header">
    <img src="{{ public_path('img/logoiasd.png') }}" alt="Logo">
    <div class="header-title">MISION BOLIVIANA OCCIDENTAR SUR</div>
</div>

<div class="footer">
    Sistema de gestion institucional - Mision Boliviana Occidental Sur
</div>

<h2>Lista de puntualidad Iglesias y Grupo</h2>

<table class="info-table">
    <tr>
        <td><strong>Fecha:</strong> {{ $fecha }}</td>
        <td><strong>Hora:</strong> {{ $hora }}</td>
    </tr>
</table>
@php
    function estrella($valor) {
        if ($valor === null) return '-';
        if ($valor == 0) return '☆';      // estrella vacía
        if ($valor == 1) return '⯪';      // media estrella
        if ($valor == 2) return '★';      // estrella llena
        return '-';
    }
@endphp
<table>
    <thead>
        <tr>
            <th>Total</th>
            <th>Distrito</th>
            <th>Iglesia</th>
            <th>Tipo</th>
            <th>Lugar</th>
            <th>Año</th>

            <th>Ene</th><th>Feb</th><th>Mar</th><th>Abr</th>
            <th>May</th><th>Jun</th><th>Jul</th><th>Ago</th>
            <th>Sep</th><th>Oct</th><th>Nov</th><th>Dic</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($iglesias as $i)
        <tr>
            <td style="font-weight:bold; text-align:center;">
                {{ $i->total_puntaje }}
            </td>
            <td>{{ $i->nombre_distrito }}</td>
            <td>{{ $i->codigo }} / {{ $i->nombre }}</td>
            <td>{{ $i->tipo }}</td>
            <td>{{ $i->lugar }}</td>
            <td>{{ $i->anio }}</td>
            <td>{{ estrella($i->puntualidad_enero) }}</td>
            <td>{{ estrella($i->puntualidad_febrero) }}</td>
            <td>{{ estrella($i->puntualidad_marzo) }}</td>
            <td>{{ estrella($i->puntualidad_abril) }}</td>
            <td>{{ estrella($i->puntualidad_mayo) }}</td>
            <td>{{ estrella($i->puntualidad_junio) }}</td>
            <td>{{ estrella($i->puntualidad_julio) }}</td>
            <td>{{ estrella($i->puntualidad_agosto) }}</td>
            <td>{{ estrella($i->puntualidad_septiembre) }}</td>
            <td>{{ estrella($i->puntualidad_octubre) }}</td>
            <td>{{ estrella($i->puntualidad_noviembre) }}</td>
            <td>{{ estrella($i->puntualidad_diciembre) }}</td>

        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
