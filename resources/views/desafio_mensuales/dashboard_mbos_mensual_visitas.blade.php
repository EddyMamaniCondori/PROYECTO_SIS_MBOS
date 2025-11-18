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
                <div class="col-sm-6"><h3 class="mb-0">Desafios Mensuales <strong>del {{$anio}}</strong></h3>
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
                                <th>Acción</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($resultado as $r)
                                <tr>
                                    <td>{{ $r['distrito'] }}</td>
                                    <td>{{ $r['pastor'] }}</td>
                                    <td>
                                        <button 
                                            class="btn btn-primary ver-grafica"
                                            data-info='@json($r)'>
                                            <i class="bi bi-bar-chart"></i> Ver gráfica
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
                    <strong id="tituloGrafica">Grafica Mensual</strong>
                </div>
                <div class="card-body">
                    <div id="grafica-mensual" style="height:350px;"></div>
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
let chart = null; // para destruir gráfico previo

document.querySelectorAll('.ver-grafica').forEach(btn => {
    btn.addEventListener('click', () => {

        const data = JSON.parse(btn.dataset.info);

        // Datos REALES que manda el controlador:
        const meses = data.meses;
        const desafios = data.desafios;
        const alcanzados = data.alcanzados;
        const diferencias = data.diferencias;

        // Destruir gráfica anterior
        if (chart) chart.destroy();

        const options = {
            chart: {
                type: "bar",
                height: 350,
                toolbar: { show: true }
            },
            series: [
                { name: "Desafío", data: desafios },
                { name: "Alcanzado", data: alcanzados },
                { name: "Diferencia", data: diferencias },
            ],
            colors: ['#0d6efd', '#198754', '#dc3545'],
            xaxis: {
                categories: meses
            },
            dataLabels: { enabled: true },
            legend: { position: 'top' },
            title: {
                text: "Desafío Mensual — " + data.distrito,
                align: "center"
            }
        };

        chart = new ApexCharts(
            document.querySelector("#grafica-mensual"),
            options
        );

        chart.render();
    });
});
</script>



@endpush
