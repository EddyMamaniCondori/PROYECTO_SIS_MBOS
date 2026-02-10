@extends('template')


@section('title', 'Batismos')

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
<x-alerts/>
        @section('content')
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h2 class="mb-0"><strong style="color: #004085"> Panel de Bautismos MBOS {{$anio}} </strong> </h2></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Bautismos</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->

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
                          Bautismos MBOS – {{ $anio }}
                      </div>

                      <div class="card-body">
                          <!-- CONTENEDOR DE LA GRÁFICA -->
                          <div id="chart-bautismos-distritales"></div>
                      </div>
                  </div>
              </div>

          </div>
        </div>
        <div class="row">
          <div class="col p-4">
            <div class="card shadow-sm">
              <div class="card-header fw-bold">
                Bautismos MBOS – {{ $anio }}
              </div>
              <div class="card-body">
                <div id="chartgeneral"></div>
              </div>
            </div>
          </div>
        </div>


        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content" style="background-color: #DFEFF5">
          <div class="row p-3">
            <div class="col-sm-12 text-center"><h2 class="mb-0"><strong style="color: #004085"> Evaluacion por distritos - {{$anio}} </strong> </h2></div>
          </div>
          <!--begin::Container-->
          <div class="container-fluid">
            <div class="row">
              <!--INICIA TABLA-->
              <div class="col-lg-6">
                <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabla de Distritos
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Distrito</th>
                                            <th>Blanco</th>
                                            <th>Alcanzado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($desafios as $distrito)
                                        <tr>
                                            <td>
                                                {{$distrito->nombre_distrito}}
                                            </td>
                                            <td>
                                                {{$distrito->desafio_bautizo}}
                                            </td>
                                            <td>
                                                {{$distrito->bautizos_alcanzados}}
                                            </td>
                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-info ver-grafica" 
                                                        data-id="{{ $distrito->id_distrito}}"
                                                        data-anio="{{ $anio }}">
                                                        <i class="bi bi-bar-chart-line"></i> Ver gráfica
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Distrito</th>
                                            <th>Blanco</th>
                                            <th>Alcanzado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
              </div>
              <!--finaliza TABLA-->
              <!--INICIA grafica-->
              <div class="col-lg-6 ">
                  <div class="card mt-4">
                    <div class="card-body">
                      <h5 id="tituloGrafica_3" class="card-title text-center"></h5>
                      <div id="grafica-desafio" style="min-height: 350px;"></div>
                    </div>
                  </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-6 ">
                <div class="card mt-4">
                  <div class="card-body">
                    <h5 id="tituloGrafica_2" class="card-title text-center"></h5>
                    <div id="grafica-tipo-bautizo" style="min-height: 350px;"></div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 ">
                <div class="card mt-4">
                  <div class="card-body">
                        <h5 id="tituloGrafica" class="card-title text-center"></h5>
                        <div id="revenue-chart" style="min-height: 320px;"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col p-3">
                <div class="card mt-4">
                  <div class="card-body">
                        <h4 id="titulo-distrito">Iglesias del Distrito</h4>
                        <div id="chart-iglesias"></div>
                  </div>
                </div>
              </div>
            </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
        @endsection



@push('js')
<!-- OPTIONAL SCRIPTS -->
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
      <!-- ChartJS -->

 <script>
    const datosGraficos = @json($graficos);

    document.querySelectorAll('.ver-grafica').forEach(btn => {
      btn.addEventListener('click', function() {
        const idDistrito = this.dataset.id;
        const data = datosGraficos.find(d => d.id_distrito == idDistrito);

        if (!data) return alert('No hay datos para este distrito.');

        const categorias = Object.keys(data.meses);
        const valores = Object.values(data.meses);

        document.getElementById('tituloGrafica').innerText = 'Bautismos Mensuales - Distrito ID: ' + idDistrito;

        const chartOptions = {
          series: [{
            name: 'Bautismos',
            data: valores
          }],
          chart: {
            type: 'area',
            height: 350,
            toolbar: { show: true }
          },
          fill: {
            colors: ['#8928AD']
          },
          colors: ['#8928AD'], // azul
          dataLabels: { enabled: true },
          xaxis: { categories: categorias },
          legend: { position: 'top' },
          title: { text: 'Bautismos por mes', align: 'center' }
        };

        if (window.graficoApex) {
          window.graficoApex.destroy();
        }

        window.graficoApex = new ApexCharts(
          document.querySelector("#revenue-chart"),
          chartOptions
        );
        window.graficoApex.render();
      });
    });
</script>

<script>
  var nombres_lista = @json($nombresDistritos);
  var p_lista = @json($porcentajes);
    var optionsgeneral = {
        series: [
            {
                name: 'Desafío',
                data: @json($datosDesafio)
            },
            {
              name: 'Alcanzados',
                data: @json($datosAlcanzados)
            }
        ],
        chart: {
            type: 'bar',
            height: 450,
            stacked: false, // Mantener en false para poder superponer
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '70%',
                barOverlap: true,
                borderRadius: 0,
            }
        },
        grid: {
            padding: {
                left: 0,
                right: 0,
            }
        },

        // Usamos esto para que la segunda barra (Alcanzados) sea más delgada
        // y se note que está "dentro" de la otra
        stroke: {
            show: true,
            width: [0,10], // Crea un margen visual
            colors: ['transparent']
        },
        colors: ['#226FD6', '#28a745'], 
        fill: {
            opacity: [1, 1] // Desafío transparente, Alcanzado sólido
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: @json($nombresDistritos),
            labels: {
                rotate: -45,
                style: { fontSize: '10px' }
            }
        },
        tooltip: {
            shared: true,
            intersect: false,
            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                // FORZAMOS EL NOMBRE: Lo sacamos de nuestra lista propia de JS
                var nombreDistrito = nombres_lista[dataPointIndex]; 
                
                var desafio = series[0][dataPointIndex];
                var alcanzado = series[1][dataPointIndex];
                var avance = p_lista[dataPointIndex];

                return (
                    '<div style="padding: 10px; background: #fff; border: 1px solid #ccc; font-family: sans-serif; color: #333;">' +
                        '<div style="font-weight: bold; border-bottom: 1px solid #eee; margin-bottom: 5px; color: #000;">' + 
                            nombreDistrito + 
                        '</div>' +
                        '<div>🎯 Desafío: <b>' + desafio + '</b></div>' +
                        '<div>🌊 Alcanzado: <b>' + alcanzado + '</b></div>' +
                        '<div style="margin-top: 5px; border-top: 1px dashed #226FD6; padding-top: 5px; color: #226FD6;">' +
                            '📊 <b>Porcentaje: ' + avance + '%</b>' +
                        '</div>' +
                    '</div>'
                );
            }
        },
        title: {
            text: 'Comparativa: Desafío vs Alcanzado por Distrito'
        },
        legend: {
            position: 'top'
        }
    };

    var chartgeneral = new ApexCharts(document.querySelector("#chartgeneral"), optionsgeneral);
    chartgeneral.render();
    chartgeneral.hideSeries('Desafío');
</script>

<script>
  const graficos = @json($graficos_tipos);

  document.querySelectorAll('.ver-grafica').forEach(btn => {
    btn.addEventListener('click', function() {
      const idDistrito = this.dataset.id;
      const g = graficos.find(d => d.id_distrito == idDistrito);
      if (!g) return alert('No hay datos.');

      document.getElementById('tituloGrafica_2').innerText = g.nombre;

      if (window.graficoTipoBautizo) window.graficoTipoBautizo.destroy();

      const chart_tipo = {
        series: [{ name: 'Cantidad', data: g.valores }],
        chart: { type: 'bar', height: 350 },
        colors: ['#0d6efd', '#20c997', '#ffc107'],
        xaxis: { categories: g.categorias },
        dataLabels: { enabled: true },
        title: { text: 'Tipos de Bautismos - ' + g.nombre, align: 'center' },
        legend: { show: false },
        plotOptions: {
          bar: { columnWidth: '50%', endingShape: 'rounded', distributed: true }
        }
        
      };

      window.graficoTipoBautizo = new ApexCharts(
        document.querySelector("#grafica-tipo-bautizo"), chart_tipo
      );
      window.graficoTipoBautizo.render();
    });
  });
</script>

<script>
  const graficos_todo = @json($graficos_final);

  document.querySelectorAll('.ver-grafica').forEach(btn => {
    btn.addEventListener('click', function() {
      const idDistrito = this.dataset.id;
      const g = graficos_todo.find(d => d.id_distrito == idDistrito);
      if (!g) return alert('No hay datos.');

      document.getElementById('tituloGrafica_3').innerText = g.nombre;

      if (window.graficoDesafio) window.graficoDesafio.destroy();

      const chart_todo = {
        series: [{ name: 'Cantidad', data: g.valores }],
        chart: { type: 'bar', height: 350 },
        colors: ['#0d6efd', '#20c997', '#dc3545'], // azul, verde, rojo
        xaxis: { categories: g.categorias },
        dataLabels: { enabled: true },
        title: { text: 'Desafíos y Bautismos Alcanzados', align: 'center' },
        legend: { show: false },
        plotOptions: {
          bar: { columnWidth: '50%', endingShape: 'rounded', distributed: true }
        }
      };

      window.graficoDesafio = new ApexCharts(
        document.querySelector("#grafica-desafio"), chart_todo
      );
      window.graficoDesafio.render();
    });
  });
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
<script>
    // Usamos un nombre de variable diferente para no chocar con tus otras gráficas
    window.graficoIglesias = null;

    document.querySelectorAll('.ver-grafica').forEach(btn => {
        btn.addEventListener('click', function() {
            const idDistrito = this.dataset.id;
            const anio = this.dataset.anio;
            // Obtenemos el nombre del distrito desde la primera celda de la fila
            const nombreDistrito = this.closest('tr').querySelector('td:first-child').innerText.trim();

            // 1. Actualizar el título de la tarjeta
            document.getElementById('titulo-distrito').innerText = 'Iglesias del Distrito: ' + nombreDistrito;

            // 2. Consultar las iglesias por AJAX (Fetch)
            fetch(`/distrito/${idDistrito}/iglesias?anio=${anio}`)
                .then(response => response.json())
                .then(data => {
                    const nombres = data.map(i => i.nombre_iglesia);
                    const valores = data.map(i => i.total_bautizos);

                    // Calculamos la altura según cuántas iglesias hay (45px por fila)
                    const alturaCalculada = (nombres.length * 45) + 100;

                    const chartOptions = {
                        series: [{
                            name: 'Bautizos',
                            data: valores
                        }],
                        chart: {
                            type: 'bar',
                            height: alturaCalculada < 400 ? 400 : alturaCalculada,
                            toolbar: { show: true }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: true, // Barras horizontales
                                borderRadius: 4,
                                dataLabels: { position: 'right' }
                            }
                        },
                        colors: ['#52A86D'], // Verde éxito
                        xaxis: {
                            categories: nombres
                        },
                        dataLabels: {
                            enabled: true,
                            offsetX: 30,
                            style: { fontSize: '3px', colors: ['#333'] }
                        }
                    };

                    // 3. Destruir instancia previa si existe para evitar duplicados
                    if (window.graficoIglesias) {
                        window.graficoIglesias.destroy();
                    }

                    // 4. Renderizar la nueva gráfica
                    window.graficoIglesias = new ApexCharts(
                        document.querySelector("#chart-iglesias"),
                        chartOptions
                    );
                    window.graficoIglesias.render();
                })
                .catch(err => console.error('Error al cargar iglesias:', err));
        });
    });
</script>


@endpush