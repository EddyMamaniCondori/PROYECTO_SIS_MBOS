@extends('template')

@section('title', 'Dashboard Mensual')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <!-- *********************apexcharts GRAFICOS-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
<style>
.summary-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 25px;
    border-radius: 18px;
    color: #fff;
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    transition: all .25s ease;
}

.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 26px rgba(0,0,0,0.18);
}

.summary-icon {
    font-size: 40px;
    color: #fff;
    opacity: 0.92;
}

.summary-title {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 3px;
}

.summary-value {
    font-size: 34px;
    font-weight: 700;
}

/* Colores personalizados */
.summary-blue {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
}

.summary-green {
    background: linear-gradient(135deg, #198754, #157347);
}

.summary-red {
    background: linear-gradient(135deg, #dc3545, #b02a37);
}

</style>
@endpush
@section('content')
@php
            $meses_array = [
                        1 => 'Enero',
                        2 => 'Febrero',
                        3 => 'Marzo',
                        4 => 'Abril',
                        5 => 'Mayo',
                        6 => 'Junio',
                        7 => 'Julio',
                        8 => 'Agosto',
                        9 => 'Septiembre',
                        10 => 'Octubre',
                        11 => 'Noviembre',
                        12 => 'Diciembre'
                    ];
            
@endphp
<div class="container-fluid mt-4">
    <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Asignacion de Blanco de Visitas <strong>{{$meses_array[$mes]}} del {{$anio}}</strong></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{route('desafios_mensuales.index')}}">Desafios Visitas Meses</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Asignacion Blancos</li>
                    </ol>
              </div>
            </div>
            <div class="row mb-3 justify-content-end">
                <!-- El botón se mueve al final de la fila -->
                <div class="col-auto"> 
                    <a href="{{ route('desafios_mensuales.index') }}">
                        <button type="button" class="btn btn-primary "> 
                            <i class="bi bi-arrow-left"></i> &nbsp;Volver 
                        </button>
                    </a>
                </div>
            </div>
          </div>
        <div class="row mb-4">

            <!-- TOTAL -->
            <div class="col-md-4">
                <div class="summary-card summary-blue">
                    <div class="summary-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <h5 class="summary-title">Total de Distritos</h5>
                        <h2 class="summary-value">{{ $totalDistritos }}</h2>
                    </div>
                </div>
            </div>

            <!-- COMPLETARON -->
            <div class="col-md-4">
                <div class="summary-card summary-green">
                    <div class="summary-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <h5 class="summary-title">Distritos que Cumplieron</h5>
                        <h2 class="summary-value">{{ $completaron }}</h2>
                    </div>
                </div>
            </div>

            <!-- FALTAN -->
            <div class="col-md-4">
                <div class="summary-card summary-red">
                    <div class="summary-icon">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div>
                        <h5 class="summary-title">Distritos que Faltan</h5>
                        <h2 class="summary-value">{{ $faltan }}</h2>
                    </div>
                </div>
            </div>

        </div>



    <!-- ===== SEGUNDA FILA: TABLA + GRÁFICA ===== -->
    <div class="row">

        <!-- TABLA -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i> Distritos del Mes
                </div>
                <div class="card-body">

                    <table id="example" class="display">
                        <thead>
                            <tr>
                                <th>Distrito</th>
                                <th>Pastor</th>
                                <th>Desafío</th>
                                <th>Alcanzado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($resultados as $r)
                            <tr>
                                <td>{{ $r->nombre_distrito }}</td>
                                <td>{{ $r->nombre_p }} {{ $r->ape_paterno }} {{ $r->ape_materno }}</td>
                                <td>{{ $r->desafio_visitas }}</td>
                                <td>{{ $r->visitas_alcanzadas }}</td>

                                <td>
                                    <button 
                                        class="btn btn-info text-white ver-grafica"
                                        data-id="{{ $r->id_distrito }}">
                                        <i class="bi bi-bar-chart"></i> Ver Gráfica
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>
            </div>
        </div>


        <!-- GRÁFICA -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <strong id="tituloGrafica">Seleccione un distrito...</strong>
                </div>
                <div class="card-body">
                    <div id="grafico-distrito" style="height:350px;"></div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection




@push('js')
    <!--JQUERY-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!--data table-->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script>
    $(document).ready(function() {
        $('#example').DataTable({
            scrollX: true,
            language: {
                search: "Buscar:",   // Cambia el texto de "Search"
                lengthMenu: "Mostrar _MENU_ registros por página",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "No hay registros disponibles",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                zeroRecords: "No se encontraron resultados",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                },
            }
        });
    });
</script>

    <!-- **********************apexcharts VhartJS -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous" ></script>

<script>
const datosGraficos = @json($graficos);

document.querySelectorAll('.ver-grafica').forEach(btn => {
    btn.addEventListener('click', function() {

        const idDistrito = this.dataset.id;
        const data = datosGraficos.find(d => d.id_distrito == idDistrito);

        if (!data) {
            alert('No hay datos.');
            return;
        }

        document.getElementById('tituloGrafica').innerText =
            'Distrito: ' + idDistrito;

        const opciones = {
            series: [
                { name: 'Desafío', data: [data.desafio] },
                { name: 'Alcanzado', data: [data.alcanzado] },
                { name: 'Diferencia', data: [data.diferencia] }
            ],
            chart: {
                type: 'bar',
                height: 340
            },
            colors: ['#0d6efd', '#20c997', '#dc3545'],
            xaxis: { categories: ['Visitas'] }
        };

        if (window.chartDistrito) {
            window.chartDistrito.destroy();
        }

        window.chartDistrito = new ApexCharts(
            document.querySelector("#grafico-distrito"),
            opciones
        );

        window.chartDistrito.render();
    });
});
</script>

@endpush
