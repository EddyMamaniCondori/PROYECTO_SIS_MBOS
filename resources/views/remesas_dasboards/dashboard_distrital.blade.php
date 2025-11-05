@extends('template')


@section('title', 'Panel')

@push('css')
<!-- *********************data tables-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <!-- *********************apexcharts GRAFICOS-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
    <!-- ********************jsvectormap JS VECTOR MAP-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />  
@endpush


        @section('content')
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
              <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabla de Remesas
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Nombre Distrito</th>
                                            <th>Desafio</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataDistritos as $idx => $d)
                                          <tr>
                                            <td>
                                                {{ $d['nombre_distrito'] }}

                                            </td>
                                            <td>
                                              @if(isset($d['totales']['desafio']) && $d['totales']['desafio'] > 0)
                                                {{ number_format($d['totales']['desafio'], 2, ',', '.') }} Bs
                                              @else
                                                <span class="text-muted">No definido</span>
                                              @endif
                                            </td>
                                            <td>
                                              <button type="button" class="btn btn-info ver-graficas" 
                                                      data-index="{{ $idx }}">
                                                <i class="bi bi-file-earmark-bar-graph-fill"></i> Ver Gráficas
                                              </button>
                                            </td>
                                          </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Nombre Distrito</th>
                                            <th>Desafio</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>


            <div class="card-body">
          </div>



          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-7 connectedSortable">
                <!--GRAFICA 2-->
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Remesa mensual distrital</h3></div>
                  <div class="card-body"><div id="revenue-chart-mensual"></div></div>
                </div>
                <!--GRAFICA 3-->
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Remesa Distrital Blancos</h3></div>
                  <div class="card-body"><div id="revenue-chart-baras"></div></div>
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
  // Datos que envía el controlador (array de distritos con series)
  const dataDistritos = @json($dataDistritos ?? []);

  // Referencias a los contenedores de las gráficas
  const mensualContainer = document.querySelector('#revenue-chart-mensual');
  const barrasContainer = document.querySelector('#revenue-chart-baras');

  // Variables globales para las instancias de ApexCharts
  let chartMensual = null;
  let chartBarras = null;

  // Inicializa DataTable si quieres (opcional)
  document.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && $.fn.DataTable) {
      $('#example').DataTable({
        responsive: true,
        language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" } // opcional
      });
    }

    // Delegación: cuando haga click un botón .ver-graficas en la tabla
    document.querySelectorAll('.ver-graficas').forEach(btn => {
      btn.addEventListener('click', function () {
        const idx = parseInt(this.dataset.index, 10);
        if (Number.isNaN(idx)) return console.warn('index no válido');

        const distrito = dataDistritos[idx];
        if (!distrito) return console.warn('Distrito no encontrado para index', idx);

        // Preparar series (asumo estructura creada en controlador)
        // mensual: [{ name: 'Remesas Totales', data: [12 valores] }]
        const seriesMensual = distrito.series_mensual || [{ name: 'Remesas Totales', data: [] }];
        // barras: [{ name: 'Blanco Anual', data: [value] }, { name:'Alcanzado', data: [value] }, ...]
        const seriesBarras = distrito.series_baras || [];

        // Render o actualizar las gráficas
        renderOrUpdateMensual(seriesMensual);
        renderOrUpdateBarras(seriesBarras);

        // Mostrar las cards si estaban ocultas (si usas display none)
        document.querySelectorAll('.card.mb-4').forEach(card => card.style.display = '');

        // Scroll suave a la sección de gráficas
        mensualContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });
  });

  // Función para crear o actualizar la gráfica mensual
  function renderOrUpdateMensual(series) {
    const options = {
      series: series,
      chart: {
        height: 300,
        type: 'area',
        toolbar: { show: true },
      },
      colors: ['#0d6efd'],
      dataLabels: { enabled: true },
      stroke: { curve: 'smooth' },
      xaxis: { categories: @json($meses) }, // asume que $meses está en la vista
      yaxis: { title: { text: 'Monto Total (Bs)' } },
      tooltip: {
        y: {
          formatter: function (val) {
            // Opcional: formatear moneda en cliente
            return Number(val).toLocaleString('es-BO', { style: 'currency', currency: 'BOB', minimumFractionDigits: 2 });
          }
        }
      }
    };

    // Si ya existe, actualizamos series
    if (chartMensual) {
      chartMensual.updateOptions(options);
      chartMensual.updateSeries(series);
    } else {
      chartMensual = new ApexCharts(mensualContainer, options);
      chartMensual.render();
    }
  }

  // Función para crear o actualizar la gráfica de barras (blanco/alcanzado/diferencia)
  function renderOrUpdateBarras(series) {
    const options = {
      series: series,
      chart: {
        type: 'bar',
        height: 350,
        toolbar: { show: true },
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '45%',
          endingShape: 'rounded'
        }
      },
      colors: ['#0d6efd', '#198754', '#dc3545'],
      dataLabels: {
        enabled: true,
        style: { colors: ['#fff'] },
      },
      stroke: { show: true, width: 2, colors: ['transparent'] },
      xaxis: { categories: ['Año ' + (new Date().getFullYear())] }, // o usa la variable $anio
      yaxis: { title: { text: 'Monto Total (Bs)' } },
      fill: { opacity: 1 },
      tooltip: {
        y: {
          formatter: function (val) {
            return Number(val).toLocaleString('es-BO', { style: 'currency', currency: 'BOB', minimumFractionDigits: 2 });
          }
        }
      },
      legend: { position: 'top', horizontalAlign: 'center' }
    };

    if (chartBarras) {
      chartBarras.updateOptions(options);
      chartBarras.updateSeries(series);
    } else {
      chartBarras = new ApexCharts(barrasContainer, options);
      chartBarras.render();
    }
  }
</script>

@endpush