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
@endpush


        @section('content')
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Dashboard de Bautisos</h3></div>
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
                                                        data-id="{{ $distrito->id_distrito}}">
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

        document.getElementById('tituloGrafica').innerText = 'Bautizos Mensuales - Distrito ID: ' + idDistrito;

        const chartOptions = {
          series: [{
            name: 'Bautizos',
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
          title: { text: 'Bautizos por mes', align: 'center' }
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
        title: { text: 'Tipos de Bautizos - ' + g.nombre, align: 'center' },
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
        title: { text: 'Desafíos y Bautizos Alcanzados', align: 'center' },
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
@endpush