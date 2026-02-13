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

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 10px;
        }

        .info-table td {
            width: 50%;
            padding: 5px;
            font-size: 12px;
        }

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





    .rojo-suave {
        background-color: #f8d7da !important;
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

<h2>Reporte Mensual de Remesas ({{ $mes }} {{ $anio }})</h2>

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
            <th>Iglesia / Grupo</th>
            <th>Distrito</th>
            <th>Cierre</th>
            <th>Depósito</th>
            <th>Documentación</th>
            <th>Fecha Entrega</th>
            <th>Estado Días</th>
            <th>Estado</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($result as $r)
        @php
            // Si es pendiente, asignamos la clase, si no, vacío.
            $claseFila = ($r->estado === 'PENDIENTE') ? 'rojo-suave' : '';
        @endphp
        <tr>
            <td class="{{ $claseFila }}">{{ $r->codigo }}</td>
            <td class="{{ $claseFila }}">{{ $r->nombre }}</td>
            <td class="{{ $claseFila }}"> {{ $r->distrito }}</td>

           <!-- Cierre -->
            <td style="text-align:center;" class="{{ $claseFila }}">
                {!! $r->cierre
                    ? '<span style="color:green;">&#10003;</span>'   {{-- ✓ --}}
                    : '<span style="color:red;">&#10007;</span>' !!} {{-- ✗ --}}
            </td>

            <!-- Depósito -->
            <td class="{{ $claseFila }}" style="text-align:center;" >
                {!! $r->deposito
                    ? '<span style="color:green;">&#10003;</span>'
                    : '<span style="color:red;">&#10007;</span>' !!}
            </td>

            <!-- Documentación -->
            <td class="{{ $claseFila }}" style="text-align:center;" >
                {!! $r->documentacion
                    ? '<span style="color:green;">&#10003;</span>'
                    : '<span style="color:red;">&#10007;</span>' !!}
            </td>




            <!-- Fecha de Entrega -->
            <td class="{{ $claseFila }}" style="text-align:center;" >
                @if ($r->fecha_entrega)
                    {{ \Carbon\Carbon::parse($r->fecha_entrega)->format('d/m/Y') }}
                @else
                    <span style="color:#777;">SIN ENTREGAR</span>
                @endif
            </td>
            <td class="{{ $claseFila }}">{{ $r->estado_dias }}</td>

            <!-- Estado -->
<td class="{{ $claseFila }}" style="text-align:center; font-weight:bold;">
    @if ($r->estado === 'ENTREGADO')
        <span style="color:green;">&#10003; ENTREGADO</span>
    @elseif ($r->estado === 'PENDIENTE')
        <span style="color:red;">&#10007; PENDIENTE</span>
    @else
        {{ $r->estado }}
    @endif
</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
