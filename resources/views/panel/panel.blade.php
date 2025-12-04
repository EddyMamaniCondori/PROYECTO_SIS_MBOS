@extends('template')

@section('title', 'Dashboard Secretaría')

@push('css')
<link 
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css">

 <!-- *********************apexcharts GRAFICOS-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
    <style>
        .parallax-banner {
            height: 500px;
            background-image: url('/img/portada.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

    </style>
@endpush

@section('content')

<div class="container-fluid">

    
    <div class="row">
        <div class="col-12 p-0">
            <div class="parallax-banner"></div>
        </div>
    </div>
    <div class="row text-white text-center p-3" style="background-color: #001f3f;">
        <div class="col-12">
            <h2>PROGRESO DE BAUTISMOS - MBOS</h2>
        </div>
    </div>

    <div class="ROW">
        <div class="row mt-4 mb-4">

              <div class="col-md-6">
                  <br><br><br>
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
                      <div class="card-header bg-primary text-white fw-bold">
                          <i class="bi bi-bar-chart-fill"></i> Bautismos MBOS {{$anio}}
                      </div>

                      <div id="chart-bautismos" style="height: 350px;"></div>

                  </div>
              </div>

          </div>
    </div>
    
    
    <div class="row text-white text-center p-3" style="background-color: #001f3f;">
        <div class="col-12">
            <h2>PROGRESO DE REMESAS - MBOS</h2>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
                  <br><br><br>
                  <div class="card shadow-lg border-0" style="border-radius: 18px;">

                      <!-- ENCABEZADO -->
                      <div class="p-3 d-flex align-items-center"
                          style="background: linear-gradient(135deg, #0a5c1c, #259959); 
                                  border-radius: 18px 18px 0 0;">

                          <!-- ICONO -->

                        <i class="bi bi-piggy-bank-fill text-white me-3 " style="font-size: 40px;"></i>
                          <div>
                              <h5 class="text-white mb-1">Avance Remesas MBOS</h5>

                              <h2 class="text-white fw-bold">
                                  {{ $r_porcentaje }}%
                              </h2>
                          </div>
                      </div>

                      <!-- CUERPO -->
                      <div class="card-body text-center">

                          <h5 class="mb-3">
                              <strong>Blanco:</strong> 
                              {{ number_format($r_blanco, 0, ',', '.') }} |

                              <strong>Alcanzado:</strong> 
                              {{ number_format($r_alcanzado, 0, ',', '.') }} 
                          </h5>

                          <!-- BARRA PROGRESO -->
                          <div class="progress" style="height: 20px; border-radius: 10px;">
                              <div class="progress-bar 
                                      {{ $r_porcentaje >= 100 ? 'bg-success' : 'bg-info' }}"
                                  role="progressbar"
                                  style="width: {{ $r_porcentaje }}%;"
                                  aria-valuenow="{{ $r_porcentaje }}"
                                  aria-valuemin="0"
                                  aria-valuemax="100">
                                  {{ $r_porcentaje }}%
                              </div>
                          </div>

                      </div>

                  </div>
              </div>
        <div class="col-md-6 mt-4">
            <div class="card text-start">
                
                <div class="card-header bg-success text-white fw-bold">
                    <i class="bi bi-bar-chart-fill"></i> Remesas MBOS
                </div>
                <div class="card-body">
                    <div id="chart-remesas" style="height: 350px;"></div>
                </div>
            </div>
            
            
        </div>
    </div>


@endsection


@push('js')
 <!-- **********************apexcharts VhartJS -->

    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous" ></script>
      <!-- ChartJS -->
<script>
    // Variables que vienen del Controller
    const desafio = {{ $b_desafio }};
    const alcanzado = {{ $b_alcanzado }};
    const diferencia = {{ $b_diferencia }};

    var options = {
        series: [{
            name: 'Bautismos',
            data: [desafio, alcanzado, diferencia]
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            }
        },
        colors: ['#004085', '#28a745', '#dc3545'], // Azul, verde, rojo
        plotOptions: {
            bar: {
                columnWidth: '45%',
                distributed: true,
                borderRadius: 8
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toLocaleString();
            }
        },
        xaxis: {
            categories: ['Desafío', 'Alcanzado', 'Diferencia'],
            labels: {
                style: {
                    fontSize: '14px',
                    colors: ['#004085', '#28a745', '#dc3545']
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return value.toLocaleString();
                }
            }
        },
        tooltip: {
            y: {
                formatter: function (value) {
                    return value.toLocaleString() + " bautismos";
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart-bautismos"), options);
    chart.render();
</script>

<script>
    const blanco1 = {{ $r_blanco }};
    const alcanzado1 = {{ $r_alcanzado }};
    const diferencia1 = {{ $r_diferencia }};

    var options1 = {
        series: [{
            name: 'Remesas',
            data: [blanco1, alcanzado1, diferencia1]
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: { show: false }
        },
        colors: ['#004085', '#28a745', '#dc3545'], // Azul, verde, rojo
        plotOptions: {
            bar: {
                columnWidth: '45%',
                distributed: true,
                borderRadius: 8
            }
        },
        dataLabels: {
            enabled: true,
            formatter: value => value.toLocaleString()
        },
        xaxis: {
            categories: ['Blanco', 'Alcanzado', 'Diferencia'],
            labels: {
                style: {
                    fontSize: '14px',
                    colors: ['#004085', '#28a745', '#dc3545']
                }
            }
        },
         yaxis: {
            show: false
        },

        // ❌ QUITAR TOOLTIP COMPLETAMENTE
        tooltip: {
            enabled: false
        }
    };

    var chart1 = new ApexCharts(
        document.querySelector("#chart-remesas"),
        options1
    );

    chart1.render();
</script>
<script>
    window.addEventListener('scroll', function () {
        const scrollTop = window.pageYOffset;
        const banner = document.querySelector('.parallax-banner');
        banner.style.backgroundPositionY = -(scrollTop * 0.15) + 'px';
    });
</script>

@endpush