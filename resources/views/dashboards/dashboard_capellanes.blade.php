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
    height: 300px;
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
                    style="
                        background-image: url('{{ asset('img/bannerasea.png') }}');
                    ">
                </div>
                </div>
            </div>
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0"><strong> {{$distrito->nombre}}</strong></h3></div>
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
              <div class="col-lg-3">
                <div class="card mt-4">
                    <div class="card-body">
                      <div class="card-header"><h3 class="card-title">Bautismos</h3></div>
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

@endpush