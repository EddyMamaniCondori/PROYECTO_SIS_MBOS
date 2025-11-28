@extends('template')

@section('title', 'Dashboard Secretaría')

@push('css')
<link 
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css">
<style>
    .small-box-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        opacity: 0.3;
        width: 70px;
        height: 70px;
    }
</style>
@endpush

@section('content')

<div class="container-fluid">

    <h2 class="mb-4 fw-bold text-primary">
        Panel Administrativo – Secretaría
    </h2>

    <!-- 🔵 4 KPIs de Iglesias -->
    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
                <div class="inner">
                    <h3>{{ $totalIglesias }}</h3>
                    <p>Total Iglesias Activas</p>
                </div>
                <i class="bi bi-people-fill small-box-icon"></i>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>{{ $iglesiasTipoIglesia }}</h3>
                    <p>Iglesias</p>
                </div>
                <i class="bi bi-building-fill-check small-box-icon"></i>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner">
                    <h3>{{ $iglesiasTipoGrupo }}</h3>
                    <p>Grupos</p>
                </div>
                <i class="bi bi-people-fill small-box-icon"></i>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-info">
                <div class="inner">
                    <h3>{{ $iglesiasTipoFilial }}</h3>
                    <p>Filiales</p>
                </div>
                <i class="bi bi-house-heart-fill small-box-icon"></i>
            </div>
        </div>

    </div>


   <!-- 🟩 Bautismos MBOS -->
    <div class="row mt-4">

        <div class="col-md-6">
            <br><br>
            <div class="card shadow-lg border-0" style="border-radius: 18px;">

                <!-- ENCABEZADO -->
                <div class="p-3 d-flex align-items-center"
                    style="background: linear-gradient(135deg, #004085, #007bff); 
                            border-radius: 18px 18px 0 0;">

                    <!-- ICONO -->
                    <i class="bi bi-droplet-fill text-white me-3" style="font-size: 40px;"></i>

                    <div>
                        <h5 class="text-white mb-1">Avance General de Bautismos MBOS</h5>

                        <h2 class="text-white fw-bold">
                            {{ $porcentajeGeneral }}%
                        </h2>
                    </div>
                </div>

                <!-- CUERPO -->
                <div class="card-body text-center">

                    <h5 class="mb-3">
                        <strong>Blanco:</strong> 
                        {{ number_format($b_desafio, 0, ',', '.') }} Bautismos
                        <br>

                        <strong>Alcanzado:</strong> 
                        {{ number_format($b_alcanzado, 0, ',', '.') }} Bautismos
                        <br>

                        <strong>Diferencia:</strong>
                        <span class="{{ $b_diferencia >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($b_diferencia, 0, ',', '.') }} Bautismos
                        </span>
                    </h5>

                    <!-- BARRA PROGRESO -->
                    <div class="progress" style="height: 20px; border-radius: 10px;">
                        <div class="progress-bar 
                                {{ $porcentajeGeneral >= 100 ? 'bg-success' : 'bg-info' }}"
                            role="progressbar"
                            style="width: {{ $porcentajeGeneral }}%;"
                            aria-valuenow="{{ $porcentajeGeneral }}"
                            aria-valuemin="0"
                            aria-valuemax="100">
                            {{ $porcentajeGeneral }}%
                        </div>
                    </div>

                </div>

            </div>
        </div>



        <div class="col-md-6">
            <div class="card border-success shadow-sm">
                <div class="card-header bg-success text-white fw-bold">
                    Bautismos por Distritos – {{ $anio }}
                </div>

                <div class="card-body">
                    <!-- CONTENEDOR DE LA GRÁFICA -->
                    <div id="chart-bautismos-distritales"></div>
                </div>
            </div>
        </div>

    </div>


    <!-- 📊 GRÁFICA: Iglesias por Distrito -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white fw-bold">
                    Iglesias por Distrito
                </div>
                <div class="card-body">
                    <div id="chart-distritos"></div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection


@push('js')

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
var chartDistritos = new ApexCharts(document.querySelector("#chart-distritos"), {
    chart: {
        type: 'bar',
        height: 350
    },
    series: [{
        name: 'Iglesias',
        data: @json($iglesiasPorDistrito->pluck('total'))
    }],
    xaxis: {
        categories: @json($iglesiasPorDistrito->pluck('distrito'))
    },
    colors: ['#0d6efd'],
    dataLabels: {
        enabled: true
    }
});

chartDistritos.render();
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    var options = {
        chart: {
            type: 'bar',
            height: 300
        },
        series: [{
            name: 'Desafío Distrital',
            data: [{{ $b_desafio_d }}]
        }, {
            name: 'Alcanzado',
            data: [{{ $b_alcanzado_d }}]
        }, {
            name: 'Diferencia',
            data: [{{ $b_diferencia_d }}]
        }],
        colors: ['#0d6efd', '#198754', '#dc3545'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '45%',
                endingShape: 'rounded'
            }
        },
        dataLabels: {
            enabled: true
        },
        xaxis: {
            categories: ["Gestión {{ $anio }}"]
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toLocaleString('es-BO') + " almas";
                }
            }
        }
    };

    var chart = new ApexCharts(
        document.querySelector("#chart-bautismos-distritales"),
        options
    );

    chart.render();
});
</script>

@endpush