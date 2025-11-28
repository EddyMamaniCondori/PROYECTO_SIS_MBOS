@extends('template')

@section('title', 'Dashboard Tesorería')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css">

<style>
    .kpi-card {
        transition: transform 0.2s;
    }
    .kpi-card:hover {
        transform: scale(1.03);
    }
    .ranking-table tbody tr td:first-child {
        font-weight: bold;
    }
    .alert-badge {
        font-size: 12px;
        padding: 3px 6px;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Panel Administrativo de Tesorería</h2>
        <span class="badge bg-primary p-2">Año {{ date('Y') }}</span>
    </div>



    <!-- ROW 1: KPIS -->
    <!-- 🟦 KPIs Tesorería Mejorados -->
    <div class="row">

        <!-- KPI 1: Desafío Total -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary kpi-card">
                <div class="inner">
                    <h3>{{ number_format($blanco, 0, ',', '.') }} <sup class="fs-6">Bs</sup></h3>
                    <p>Desafío Total (Misión)</p>
                </div>

                <!-- Icono -->
                <svg xmlns="http://www.w3.org/2000/svg" width="62" height="62" fill="currentColor"  viewBox="0 0 16 16" class="bi bi-radar small-box-icon">
                    <path d="M6.634 1.135A7 7 0 0 1 15 8a.5.5 0 0 1-1 0 6 6 0 1 0-6.5 5.98v-1.005A5 5 0 1 1 13 8a.5.5 0 0 1-1 0 4 4 0 1 0-4.5 3.969v-1.011A2.999 2.999 0 1 1 11 8a.5.5 0 0 1-1 0 2 2 0 1 0-2.5 1.936v-1.07a1 1 0 1 1 1 0V15.5a.5.5 0 0 1-1 0v-.518a7 7 0 0 1-.866-13.847"/>
                </svg>
                

                <a href="#" class="small-box-footer link-light link-underline-opacity-0">
                    Más Información <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>



        <!-- KPI 2: Alcanzado -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success kpi-card">
                <div class="inner">
                    <h3>{{ number_format($alcanzado, 0, ',', '.') }} <sup class="fs-6">Bs</sup></h3>
                    <p>Total Alcanzado</p>
                </div>

                <!-- Icono -->
                <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" fill="currentColor" class="bi bi-graph-up-arrow small-box-icon" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5"/>
                </svg>

                <a href="#" class="small-box-footer link-light link-underline-opacity-0">
                    Más Información <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>



        <!-- KPI 3: Porcentaje de Avance -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-info kpi-card">
                <div class="inner">
                    <h3>{{ $porcentaje }} <sup class="fs-6">%</sup></h3>
                    <p>Avance General</p>
                </div>

                <!-- Icono -->
                
                
                <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" fill="currentColor" class="bi bi-percent small-box-icon" viewBox="0 0 16 16">
  <path d="M13.442 2.558a.625.625 0 0 1 0 .884l-10 10a.625.625 0 1 1-.884-.884l10-10a.625.625 0 0 1 .884 0M4.5 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5m7 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
</svg>
                <a href="#" class="small-box-footer link-light link-underline-opacity-0">
                    Ver Detalles <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>



        <!-- KPI 4: Promedio Mensual -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning kpi-card">
                <div class="inner">
                    <h3>{{ number_format($promedioMensual, 0, ',', '.') }} <sup class="fs-6">Bs</sup></h3>
                    <p>Promedio Mensual</p>
                </div>

                <!-- Icono -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    class="bi bi-calendar3-range small-box-icon" viewBox="0 0 16 16">
                    <path d="M14 3h-1V1h-1v2H4V1H3v2H2a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h12a2 2 
                    0 0 0 2-2V5a2 2 0 0 0-2-2z"/>
                    <path d="M11 8H5v3h6V8z"/>
                </svg>

                <a href="#" class="small-box-footer link-dark link-underline-opacity-0">
                    Más Información <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>

    </div>




    <!-- ROW 2: GRÁFICAS -->
    <div class="row">

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bi bi-bar-chart-line"></i>Avance Global de Remesas
                </div>
                <div class="card-body">
                    <div id="chart-barras"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white fw-bold">
                    <i class="bi bi-bar-chart-line"></i> Remesas Globales por Mes
                </div>
                <div class="card-body">
                    <div id="chart-area"></div>
                </div>
            </div>
        </div>

    </div>



    <!-- ROW 3: RANKINGS Y ALERTAS -->
    <div class="row mt-4">

        <!-- TOP DISTRITOS -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white fw-bold">
                    Top 5 Distritos por Remesa
                </div>
                <div class="card-body">
                    <table class="table ranking-table">
                        <thead>
                            <tr>
                                <th>Distrito</th>
                                <th>Total (Bs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topDistritos as $d)
                                <tr>
                                    <td>{{ $d->distrito }}</td>
                                    <td>{{ number_format($d->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ALERTAS -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white fw-bold">
                    Distritos en Alerta (< 50% del blanco)
                </div>
                <div class="card-body">
                    @if(count($alertas) == 0)
                        <p class="text-success fw-bold">✔ Todos los distritos están dentro del rango esperado</p>
                    @else
                        <ul class="list-group">
                            @foreach($alertas as $d)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $d->distrito }}
                                    <span class="alert-badge bg-danger text-white">
                                        {{ number_format($d->total, 0, ',', '.') }} Bs
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>

@endsection



@push('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    // Barras
    new ApexCharts(document.querySelector("#chart-barras"), {
        chart: { type: 'bar', height: 300 },
        series: [
            { name: "Desafío", data: [{{ $blanco }}] },
            { name: "Alcanzado", data: [{{ $alcanzado }}] },
            { name: "Diferencia", data: [{{ $diferencia }}] }
        ],
        colors: ['#0d6efd', '#198754', '#dc3545'],
        plotOptions: { bar: { columnWidth: '45%' }},
        dataLabels: { enabled: true },
        xaxis: { categories: ["{{ date('Y') }}"] },
    }).render();

    // Área mensual
    new ApexCharts(document.querySelector("#chart-area"), {
        chart: { type: 'area', height: 300 },
        series: [{ name: "Remesa Mensual", data: @json($dataMensual) }],
        xaxis: { categories: @json($meses) },
        colors: ['#198754'],
        stroke: { curve: 'smooth' },
        dataLabels: { enabled: true }
    }).render();
</script>
@endpush
