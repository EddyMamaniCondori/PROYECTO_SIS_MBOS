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
            <div class="row">
                <div class="card mb-4">
                      <div class="card-header">
                        <i class="fa-solid fa-money-bill-trend-up"></i>
                        <h5 id="tituloGrafica" class="card-title text-center">Seleccione una Filial</h5>

                      </div>
                      <div class="card-body">
                          <div id="revenue-chart" style="min-height: 350px;"></div>
                      </div>
                  </div>
            </div>
            <div class="row mt-4">
                <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabla de Remesas
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Iglesia</th>
                                            <th>Ene</th>
                                            <th>Feb</th>
                                            <th>Mar</th>
                                            <th>Abr</th>
                                            <th>May</th>
                                            <th>Jun</th>
                                            <th>Jul</th>
                                            <th>Ago</th>
                                            <th>Sep</th>
                                            <th>Oct</th>
                                            <th>Nov</th>
                                            <th>Dic</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tablaMeses as $t)
                                          <tr>
                                              <td>{{ $t->codigo }}</td>
                                              <td>{{ $t->nombre_iglesia }}</td>
                                              <td>{{ $t->mes_enero }}</td>
                                                <td>{{ $t->mes_febrero }}</td>
                                                <td>{{ $t->mes_marzo }}</td>
                                                <td>{{ $t->mes_abril }}</td>
                                                <td>{{ $t->mes_mayo }}</td>
                                                <td>{{ $t->mes_junio }}</td>
                                                <td>{{ $t->mes_julio }}</td>
                                                <td>{{ $t->mes_agosto }}</td>
                                                <td>{{ $t->mes_septiembre }}</td>
                                                <td>{{ $t->mes_octubre }}</td>
                                                <td>{{ $t->mes_noviembre }}</td>
                                                <td>{{ $t->mes_diciembre }}</td>
                                              <td>
                                                  <button class="btn btn-primary btn-sm ver-grafica"
                                                      data-codigo="{{ $t->id_iglesia }}">
                                                      Ver gráfica
                                                  </button>
                                              </td>
                                          </tr>
                                          @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Código</th>
                                            <th>Iglesia</th>
                                            <th>Ene</th>
                                            <th>Feb</th>
                                            <th>Mar</th>
                                            <th>Abr</th>
                                            <th>May</th>
                                            <th>Jun</th>
                                            <th>Jul</th>
                                            <th>Ago</th>
                                            <th>Sep</th>
                                            <th>Oct</th>
                                            <th>Nov</th>
                                            <th>Dic</th>
                                            <th>Acción</th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                </div>
          </div>
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
    const tablaMeses = @json($tablaMeses);
    let graficoActual = null;

    document.querySelectorAll('.ver-grafica').forEach(btn => {
        btn.addEventListener('click', function () {

            const codigo = this.dataset.codigo;
            const fila = tablaMeses.find(x => x.id_iglesia == codigo);

            if (!fila) return;

            document.getElementById('tituloGrafica').innerText =
                "Iglesia: " + fila.nombre_iglesia;

            const valores = [
                fila.mes_enero, fila.mes_febrero, fila.mes_marzo,
                fila.mes_abril, fila.mes_mayo, fila.mes_junio,
                fila.mes_julio, fila.mes_agosto, fila.mes_septiembre,
                fila.mes_octubre, fila.mes_noviembre, fila.mes_diciembre
            ];

            if (graficoActual) graficoActual.destroy();

            graficoActual = new ApexCharts(
                document.querySelector("#revenue-chart"),
                {
                    chart: { type: 'area', height: 350 },
                    series: [{ name: 'Monto', data: valores }],
                    xaxis: {
                        categories: [
                            "Ene","Feb","Mar","Abr","May","Jun",
                            "Jul","Ago","Sep","Oct","Nov","Dic"
                        ]
                    },
                    colors: ['#29AB5F'],
                    dataLabels: { enabled: true }
                }
            );

            graficoActual.render();
        });
    });
  </script>

@endpush