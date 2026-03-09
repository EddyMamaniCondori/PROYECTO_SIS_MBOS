@extends('template')


@section('title', 'Panel')

@push('css')
<!--data table-->
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
              <div class="col-sm-6"><h3 class="mb-0">Dashboard de Bautismos <br> <label class="text-primary">
              <strong>Colegio: </strong> {{$unidadeducativa->nombre}} </label>
              <br>Gestion: {{$anio}}</h3></div> 
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
              <div class="col-lg-6 ">
                <div class="card mt-4">
                  <div class="card-body">
                    <h5 id="tituloGrafica_2" class="card-title text-center"></h5>
                    <div id="grafica-desafio" style="min-height: 350px;"></div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 ">
                <div class="card mt-4">
                  <div class="card-body">
                        <h5 id="tituloGrafica" class="card-title text-center"></h5>
                        <div id="grafica-tipo" style="min-height: 320px;"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <!--INICIA TABLA-->
              <div class="col-lg-12">
                <div class="card mt-4">
                    <div class="card-body">
                      <h5 id="tituloGrafica_3" class="card-title text-center"></h5>
                      <div id="grafica-meses" style="min-height: 350px;"></div>
                    </div>
                  </div>
              </div>
            </div>

            
          <!--end::Container-->
        </div>
        <!--end::App Content-->
        @endsection



@push('js')
    <!-- **********************apexcharts VhartJS -->

    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous" ></script>
      <!-- ChartJS -->

 

<script>
  let g1 = @json($grafico_desafio);

  const chartDesafio = {
    series: [{ name: 'Cantidad', data: g1.valores }],
    chart: { type: 'bar', height: 350 },
    colors: ['#0d6efd', '#20c997', '#dc3545'], // 3 colores -> 3 barras
    plotOptions: {
      bar: {
        distributed: true,       // <- aplica colores por barra
        columnWidth: '50%',
        endingShape: 'rounded'
      }
    },
    xaxis: { categories: g1.categorias },
    dataLabels: { enabled: true },
    title: { text: 'Desafío Distrital - ' + g1.nombre, align: 'center' },
    legend: { show: false }
  };

  window.graficoDesafio = new ApexCharts(
    document.querySelector("#grafica-desafio"),
    chartDesafio
  );
  window.graficoDesafio.render();
</script>


<script>
  let g2 = @json($grafico_tipos);

  const chartTipos = {
    series: [{ name: 'Cantidad', data: g2.valores }],
    chart: { type: 'bar', height: 350 },
    colors: ['#0d6efd', '#20c997', '#ffc107'], 
    xaxis: { categories: g2.categorias },
    dataLabels: { enabled: true },
    title: { text: 'Tipos de Bautizos - ' + g2.nombre, align: 'center' },
    legend: { show: false },
    plotOptions: { bar: { distributed: true } }
  };

  window.graficoTipos = new ApexCharts(
    document.querySelector("#grafica-tipo"),
    chartTipos
  );
  window.graficoTipos.render();
</script>
<script>
  let g3 = @json($grafico_meses);

  const chartMeses = {
    series: [{
      name: 'Bautizos',
      data: g3.valores
    }],
    chart: { type: 'area', height: 350 },
    stroke: { curve: 'smooth' },
    colors: ['#0d6efd'],
    xaxis: { categories: g3.categorias },
    dataLabels: { enabled: true },
    title: { text: 'Bautizos por Mes - ' + g3.nombre, align: 'center' }
  };

  window.graficoMeses = new ApexCharts(
    document.querySelector("#grafica-meses"),
    chartMeses
  );
  window.graficoMeses.render();
</script>





@endpush