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
                <div class="col-sm-6">
                    <h4 class="mb-0">Seguiento de Estudiantes y Instructores por Iglesias - {{$anio}}<br>
                    <span class="text-primary"> <strong> Distrito:</strong>&nbsp;{{$distrito->nombre}}</span> <br>
                    <span class="text-primary"><strong> Pastor:</strong> &nbsp;{{$persona->nombre}}&nbsp;{{$persona->ape_paterno}}&nbsp;{{$persona->ape_materno}}</span></h4>
                </div>
                
                <div class="col-sm-6 mb-3">
                    <!-- Contenedor Flexbox que apila verticalmente (flex-column) y alinea todo a la DERECHA (align-items-end) -->
                    <div class="d-flex flex-column align-items-end">
                        <ol class="breadcrumb mb-2"> 
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('instructores.mbos.distrital.ver') }}">Seguiento Distrital</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Seguiento por Iglesias</li>
                        </ol> 
                        <a href="{{ route('instructores.mbos.distrital.ver') }}" class="btn btn-primary ">
                            <i class="bi bi-box-arrow-in-left"></i> Volver
                        </a>
                    </div>
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
                                Tabla de Iglesias
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Iglesia</th>
                                            <th>Desafio Estudiantes</th>
                                            <th>Estudiantes Alcanzados</th>
                                            <th>Desafio Instructores</th>
                                            <th>Instructores Alcanzados</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($desafios as $desafio)
                                        <tr>
                                            <td>
                                                {{$desafio->codigo}}
                                            </td>
                                            <td>
                                                {{$desafio->nombre_iglesia}}
                                            </td>
                                            <td>
                                                {{$desafio->desafio_estudiantes}} 
                                            </td>
                                            <td>
                                                {{$desafio->estudiantes_alcanzados}}
                                            </td>
                                            <td>
                                                {{$desafio->desafio_instructores}} 
                                            </td>
                                            <td>
                                                {{$desafio->instructores_alcanzados}}
                                            </td>
                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                    <button 
                                                      type="button" 
                                                      class="btn btn-info ver-grafica" 
                                                      data-id="{{ $desafio->id_iglesia }}">
                                                      <i class="bi bi-bar-chart-line"></i> Ver gráfica
                                                    </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Codigo </th>
                                            <th>Iglesia </th>
                                            <th>Desafio Estudiantes</th>
                                            <th>Estudiantes Alcanzados</th>
                                            <th>Desafio Instructores</th>
                                            <th>Instructores Alcanzados</th>
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
                <!-- Contenedor de la gráfica (asegúrate que existan ambos ids) -->
                <div class="card mt-4">
                  <div class="card-body">
                    <h5 id="tituloGrafica" class="card-title text-center"></h5>
                    <div id="revenue-chart" style="min-height: 320px;"></div>
                  </div>
                </div>
              </div>
              <!--termina grafica-->
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
        const idIglesia = this.dataset.id;
        const data = datosGraficos.find(d => d.id_iglesia == idIglesia);

        if (!data) return alert('No hay datos para esta iglesia.');

        // Preparar datos para el gráfico
        const categorias = ['Estudiantes', 'Instructores'];
        const serieDesafio = [data.estudiantes.desafio, data.instructores.desafio];
        const serieAlcanzado = [data.estudiantes.alcanzado, data.instructores.alcanzado];
        const serieDiferencia = [data.estudiantes.diferencia, data.instructores.diferencia];

        // Actualizar título
        document.getElementById('tituloGrafica').innerText = 'Iglesia ID: ' + idIglesia;

        // Crear gráfico
        const chartOptions = {
          series: [
            { name: 'Desafío', data: serieDesafio },
            { name: 'Alcanzado', data: serieAlcanzado },
            { name: 'Diferencia', data: serieDiferencia },
          ],
          chart: {
            type: 'bar',
            height: 350,
            toolbar: { show: true }
          },
          plotOptions: {
            bar: { horizontal: false, columnWidth: '50%', endingShape: 'rounded' }
          },
          colors: ['#0d6efd', '#20c997', '#dc3545'], // azul, verde, rojo
          dataLabels: { enabled: true },
          xaxis: { categories: categorias },
          legend: { position: 'top' },
          title: { text: 'Comparativa de Desafíos y Alcanzados', align: 'center' }
        };

        // Destruir gráfico anterior si existe
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


@endpush