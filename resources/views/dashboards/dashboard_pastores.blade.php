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
              <div class="col-sm-6"><h3 class="mb-0">Distrito {{$distrito->nombre}}</h3></div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <!--div de los cards-->
            <div class="row">
              <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 1-->
                <div class="small-box text-bg-primary">
                  <div class="inner">
                    <h3><span>{{ $resumenIglesias->total_iglesias }}</span></h3>
                    <p>Total de Iglesias</p>
                  </div>
                  <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="currentColor" class="bi bi-houses-fill small-box-icon" viewBox="0 0 24 24">
                    <path clip-rule="evenodd"
                      fill-rule="evenodd" d="M7.207 1a1 1 0 0 0-1.414 0L.146 6.646a.5.5 0 0 0 .708.708L1 7.207V12.5A1.5 1.5 0 0 0 2.5 14h.55a2.5 2.5 0 0 1-.05-.5V9.415a1.5 1.5 0 0 1-.56-2.475l5.353-5.354z"/>
                    <path  clip-rule="evenodd"
                      fill-rule="evenodd" d="M8.793 2a1 1 0 0 1 1.414 0L12 3.793V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3.293l1.854 1.853a.5.5 0 0 1-.708.708L15 8.207V13.5a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 4 13.5V8.207l-.146.147a.5.5 0 1 1-.708-.708z"/>
                  </svg>
                 
                  <a
                    href="#"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Mas Informacion <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 1-->
              </div>
              <!--end::Col-->
              <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 2-->
                <div class="small-box text-bg-success">
                  <div class="inner">
                    <h3>{{ $resumenIglesias->total_iglesia }}<sup class="fs-5"></sup></h3>
                    <p>Iglesias</p>
                  </div>

                  <svg xmlns="http://www.w3.org/2000/svg"  aria-hidden="true"  fill="currentColor" class="bi bi-house-heart-fill small-box-icon" viewBox="0 0 24 24">
                  <path clip-rule="evenodd"
                      fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0L11 3.793V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3.293l2.354 2.353a.5.5 0 0 1-.708.707L8 2.207 1.354 8.853a.5.5 0 1 1-.708-.707z"/>
                  <path clip-rule="evenodd"
                      fill-rule="evenodd" d="m14 9.293-6-6-6 6V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5zm-6-.811c1.664-1.673 5.825 1.254 0 5.018-5.825-3.764-1.664-6.691 0-5.018"/>
                </svg>

                  <a
                    href="#"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    Mas informacion <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 2-->
              </div>
              <!--end::Col-->
              <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 3-->
                <div class="small-box text-bg-warning">
                  <div class="inner">
                    <h3>{{ $resumenIglesias->total_grupo }}</h3>
                    <p>Grupos</p>
                  </div>
                  <svg xmlns="http://www.w3.org/2000/svg"  aria-hidden="true" fill="currentColor" class="bi bi-house-gear-fill small-box-icon" viewBox="0 0 24 24">
                    <path clip-rule="evenodd"
                      fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0L11 3.793V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3.293l2.354 2.353a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708z"/>
                    <path clip-rule="evenodd"
                      fill-rule="evenodd"  d="M11.07 9.047a1.5 1.5 0 0 0-1.742.26l-.02.021a1.5 1.5 0 0 0-.261 1.742 1.5 1.5 0 0 0 0 2.86 1.5 1.5 0 0 0-.12 1.07H3.5A1.5 1.5 0 0 1 2 13.5V9.293l6-6 4.724 4.724a1.5 1.5 0 0 0-1.654 1.03"/>
                    <path clip-rule="evenodd"
                      fill-rule="evenodd" d="m13.158 9.608-.043-.148c-.181-.613-1.049-.613-1.23 0l-.043.148a.64.64 0 0 1-.921.382l-.136-.074c-.561-.306-1.175.308-.87.869l.075.136a.64.64 0 0 1-.382.92l-.148.045c-.613.18-.613 1.048 0 1.229l.148.043a.64.64 0 0 1 .382.921l-.074.136c-.306.561.308 1.175.869.87l.136-.075a.64.64 0 0 1 .92.382l.045.149c.18.612 1.048.612 1.229 0l.043-.15a.64.64 0 0 1 .921-.38l.136.074c.561.305 1.175-.309.87-.87l-.075-.136a.64.64 0 0 1 .382-.92l.149-.044c.612-.181.612-1.049 0-1.23l-.15-.043a.64.64 0 0 1-.38-.921l.074-.136c.305-.561-.309-1.175-.87-.87l-.136.075a.64.64 0 0 1-.92-.382ZM12.5 14a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"/>
                    </svg>
                    
                    
                  <a
                    href="#"
                    class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    Mas Informacion <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 3-->
              </div>
              <!--end::Col-->
              <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 4-->
                <div class="small-box text-bg-danger">
                  <div class="inner">
                    <h3>{{$resumenIglesias->total_filial }}</h3>
                    <p>Filiales</p>
                  </div>
                  <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="currentColor" class="bi bi-house small-box-icon" viewBox="0 0 24 24" >
                    <path  clip-rule="evenodd"
                      fill-rule="evenodd" d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
                </svg>
                  
                  
                  <a
                    href="#"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    Mas Informacion <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 4-->
              </div>
              <!--end::Col-->
            </div>
          <!--begin::Container-->
          <div class="container-fluid">
            <div class="row">
              <!--INICIA TABLA-->
              <div class="col-lg-3">
                <div class="card mt-4">
                    <div class="card-body">
                      <div class="card-header"><h3 class="card-title">Bautisos Anuales</h3></div>
                      <h5 id="tituloGrafica_3" class="card-title text-center"></h5>
                      <div id="grafica-desafio" style="min-height: 350px;"></div>
                    </div>
                  </div>
              </div>
              <!--finaliza TABLA-->
              <!--INICIA grafica-->
              <div class="col-lg-9 ">
                  <div class="card mt-4">
                    <div class="card-body">
                      <div class="card-header"><h3 class="card-title">Visitas Mensuales</h3></div>
                      <div class="card-body"><div id="revenue-chart" style="min-height: 350px;"></div></div>
                    </div>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                  <div class="card mt-4">
                      <div class="card-body">
                          <h5 class="card-title text-center">Gráfico de Estudiantes</h5>
                          <div id="grafico-estudiantes" style="min-height: 350px;"></div>
                      </div>
                  </div>
              </div>

              <div class="col-lg-6">
                  <div class="card mt-4">
                      <div class="card-body">
                          <h5 class="card-title text-center">Gráfico de Instructores</h5>
                          <div id="grafico-instructores" style="min-height: 350px;"></div>
                      </div>
                  </div>
              </div>
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
      var meses = @json($meses);
      var datosDesafio = @json($desafios);
      var datosAlcanzados = @json($alcanzados);

      const sales_chart_options = {
          series: [
              {
                  name: 'Desafío',
                  data: datosDesafio,
              },
              {
                  name: 'Alcanzado',
                  data: datosAlcanzados,
              },
          ],
          chart: {
              height: 350,
              type: 'bar', // usa 'bar' para barras
              toolbar: { show: true },
          },
          plotOptions: {
              bar: {
                  horizontal: false,
                  columnWidth: '60%',
                  endingShape: 'rounded',
              },
          },
          legend: {
              show: true,
          },
          colors: ['#0d6efd', '#20c997'], // azul y verde
          dataLabels: {
              enabled: true,
          },
          stroke: {
              show: true,
              width: 2,
              colors: ['transparent'],
          },
          xaxis: {
              categories: meses,
          },
          yaxis: {
              title: { text: 'Número de visitas' },
          },
          tooltip: {
              y: {
                  formatter: function (val) {
                      return val + " visitas";
                  },
              },
          },
      };

      const sales_chart = new ApexCharts(document.querySelector("#revenue-chart"), sales_chart_options);
      sales_chart.render();
  </script>

  
  <script>
  const graficoEstudiantes = @json($grafico_estudiantes);
  const graficoInstructores = @json($grafico_instructores);

  function crearGrafico(idDiv, data, titulo) {
    const chartConfig = {
      series: [{ name: 'Cantidad', data: data.valores }],
      chart: { type: 'bar', height: 350 },
      colors: ['#0d6efd', '#20c997', '#dc3545'], // Azul, verde, rojo
      xaxis: { categories: data.categorias },
      dataLabels: { enabled: true },
      title: { text: titulo, align: 'center' },
      legend: { show: false },
      plotOptions: {
        bar: { columnWidth: '50%', endingShape: 'rounded', distributed: true }
      }
    };

    new ApexCharts(document.querySelector(`#${idDiv}`), chartConfig).render();
  }

  // Renderizar ambos gráficos
  crearGrafico('grafico-estudiantes', graficoEstudiantes, 'Desafíos de Estudiantes');
  crearGrafico('grafico-instructores', graficoInstructores, 'Desafíos de Instructores');
</script>

@endpush