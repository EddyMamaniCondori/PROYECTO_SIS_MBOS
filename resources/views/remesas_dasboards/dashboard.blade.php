@extends('template')


@section('title', 'Panel')

@push('css')
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
              <div class="col-lg-7 connectedSortable">
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Remesa Anual con Blancos</h3></div>
                  <div class="card-body"><div id="revenue-chart"></div></div>
                </div>
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
 
    <!-- **********************apexcharts VhartJS -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous" ></script>
      <!-- ChartJS -->
    <script>
      var meses = @json($meses);
      var series = @json($series);

      const sales_chart_options = {
        series: series,
        chart: {
          height: 300,
          type: 'area',
          toolbar: { show: true },
        },
        legend: { show: true },
        colors: ['#0d6efd', '#20c997'], // Azul y verde (puedes ajustar)
        dataLabels: { enabled: true },
        stroke: { curve: 'smooth' },
        xaxis: {
          type: 'category',
          categories: meses,
        },
        tooltip: {
          x: { show: true },
        },
      };

      const sales_chart = new ApexCharts(
        document.querySelector('#revenue-chart'),
        sales_chart_options
      );
      sales_chart.render();
    </script> 

      <script>
        var meses = @json($meses);
        var series = @json($series_mensual);

        const options = {
          series: series,
          chart: {
            height: 300,
            type: 'area', // puedes usar 'area' o 'line' también
            toolbar: { show: true },
          },
          colors: ['#0d6efd'], // azul Bootstrap
          dataLabels: { enabled: true },
          stroke: { curve: 'smooth' },
          xaxis: { categories: meses },
          yaxis: { title: { text: 'Monto Total (Bs)' } },
        };

        const chart = new ApexCharts(document.querySelector('#revenue-chart-mensual'), options);
        chart.render();
    </script> 



  <script>
    var series = @json($series_baras);
    const chartOptions = {
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
          endingShape: 'rounded',
        },
      },
      colors: ['#0d6efd', '#198754', '#dc3545'], // azul, verde, rojo
      dataLabels: {
        enabled: true,
        style: { colors: ['#fff'] },
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent'],
      },
      xaxis: {
        categories: ['Año 2025'], // solo etiqueta visual, no datos
      },
      yaxis: {
        title: { text: 'Monto Total (Bs)' },
      },
      fill: {
        opacity: 1,
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return val.toLocaleString('es-BO', {
              style: 'currency',
              currency: 'BOB',
              minimumFractionDigits: 2,
            });
          },
        },
      },
      legend: {
        position: 'top',
        horizontalAlign: 'center',
      },
    };

    const chart_baras = new ApexCharts(document.querySelector('#revenue-chart-baras'), chartOptions);
    chart_baras.render();
  </script>
@endpush