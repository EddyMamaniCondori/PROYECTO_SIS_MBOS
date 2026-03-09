@extends('template')


@section('title', 'Panel')

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
       .parallax-banner {
    height: 350px;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}

    </style>
@endpush


        @section('content')
        <div class="row">
                <div class="col-12 p-0 mb-2">
                <div class="parallax-banner"
                    style="background-image: url('{{ asset('unidad_educativas/' . $unidadeducativa->nombre . '.png') }}');">
                </div>
                </div>
            </div>
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0"><strong> {{$unidadeducativa->nombre}}</strong></h3></div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <!--div de los la imagen-->
            
          <!--begin::Container-->
          <div class="container-fluid">
            <div class="row">
              <!--INICIA TABLA-->
              <div class="col">
                <div class="card mt-4">
                    <div class="card-body">
                      <div class="card-header"><h3 class="card-title">Bautismos</h3></div>
                      <h5 id="tituloGrafica_3" class="card-title text-center"></h5>
                      <div id="grafica-desafio" style="min-height: 350px;"></div>
                    </div>
                  </div>
              </div>
              
          </div>


          <div class="container-fluid py-4">
    @foreach($datosFormateados as $datos)
        <div class="card shadow-sm border-0 mb-4 overflow-hidden" style="border-radius: 15px;">
            <div class="card-header border-0 py-3" style="background: linear-gradient(45deg, #2B1D66, #3D82B3);">
                <h5 class="mb-0 text-white d-flex align-items-center">
                    <i class="fas fa-user-circle fa-lg me-3"></i>
                    <span class="fw-bold" style="letter-spacing: 0.5px;">
                        {{ strtoupper($datos['nombre_completo']) }}
                    </span>
                </h5>
            </div>

            <div class="card-body p-0">
                <div class="row g-0 align-items-center">
                    <div class="col-md-3 d-flex align-items-center justify-content-center p-0">
                        @php
                            $nombreImagen = $datos['nombre_completo'] . '.png';
                            $rutaImagen = 'fotos_capellanes/' . $nombreImagen;
                            $existe = file_exists(public_path($rutaImagen));
                        @endphp

                        <div class="position-relative" >
                            <img src="{{ $existe ? asset($rutaImagen) : asset('img/default-user.jpg') }}" 
                                 alt="{{ $datos['nombre_completo'] }}" 
                                 class="img-fluid p-0" 
                                 style=" heiobject-fit: cover; border: 5px solid white;">
                        </div>
                    </div>

                    <div class="col-md-9 p-2">
                        <div class="d-flex justify-content-between align-items-center px-3">
                            <small class="text-muted text-uppercase fw-bold">Rendimiento Institucional</small>
                            <span class="badge bg-light text-dark border">Periodo 2026</span>
                        </div>
                        <div id="chart-pastor-{{ $datos['id_persona'] }}" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-white border-0 py-2">
                <div class="row text-center text-muted small">
                    <div class="col border-end"><strong>Codigo:</strong> #{{ $datos['id_persona'] }}</div>
                    <div class="col border-end"><strong>Sistema:</strong> Gestión Pastoral</div>
                    <div class="col"><strong>Misión:</strong> MBOS</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

            
          <!--end::Container-->
        </div>
        <!--end::App Content-->
        @endsection



@push('js')

</script>
    <!-- **********************apexcharts VhartJS -->

    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous" ></script>
      <!-- ChartJS -->


<script>
  const graficos_todo = @json($graficos_final);

  const chart_todo = {
    series: [{ name: 'Cantidad', data: graficos_todo.valores }],
    chart: { type: 'bar', height: 350 },
    colors: ['#0d6efd', '#20c997', '#dc3545'], // azul, verde, rojo
    xaxis: { categories: graficos_todo.categorias },
    dataLabels: { enabled: true },
    legend: { show: false },
    plotOptions: {
      bar: { columnWidth: '50%', endingShape: 'rounded', distributed: true }
    }
  };

  const graficoDesafio = new ApexCharts(
    document.querySelector("#grafica-desafio"),
    chart_todo
  );

  graficoDesafio.render();
</script>


  <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Pasamos los datos de PHP a JavaScript de forma segura
        const todosLosDatos = @json($datosFormateados);

        // Nombres de los meses para el eje X
        const nombresMeses = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];

        todosLosDatos.forEach(pastor => {
            // Extraemos los arrays de desafíos y alcanzados de la estructura de meses
            const dataDesafios = [];
            const dataAlcanzados = [];
            const categoriasX = [];

            // Recorremos las series (que ya vienen filtradas por mesMaximo desde el controlador)
            Object.keys(pastor.series).forEach(mes => {
                dataDesafios.push(pastor.series[mes].desafio);
                dataAlcanzados.push(pastor.series[mes].alcanzado);
                categoriasX.push(nombresMeses[mes - 1]); // Convertimos número de mes a nombre
            });

            const options = {
                series: [{
                    name: 'Desafío Visitas',
                    data: dataDesafios
                }, {
                    name: 'Visitas Alcanzadas',
                    data: dataAlcanzados
                }],
                chart: {
                    type: 'bar', // Puedes cambiar a 'line' o 'area' si prefieres
                    height: 350,
                    toolbar: { show: true }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded',
                        dataLabels: {
                            position: 'center', // Opciones: 'top', 'center', 'bottom'
                        },
                    },
                },
                dataLabels: { enabled: true, // Cambiamos a true para que se vean los números
                              formatter: function (val) {
                                  // Solo mostrar el número si es mayor a 0 para no saturar la gráfica
                                  return val > 0 ? val : ''; 
                              },
                              style: {
                                  fontSize: '12px',
                                  colors: ['#fff'] // Color blanco para que resalte sobre el naranja/verde
                              } 
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: categoriasX,
                },
                yaxis: {
                    title: { text: 'Cantidad de Visitas' }
                },
                fill: { opacity: 1 },
                tooltip: {
                    y: {
                        formatter: function (val) { return val + " visitas" }
                    }
                },
                colors: ['#FFA500', '#28a745'], // Naranja para desafíos, Verde para alcanzados
                title: {
                    text: 'Progreso Mensual de Visitas',
                    align: 'left'
                }
            };

            const chart = new ApexCharts(
                document.querySelector(`#chart-pastor-${pastor.id_persona}`), 
                options
            );
            chart.render();
        });
    });
</script>

@endpush