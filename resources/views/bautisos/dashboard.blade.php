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
                  <div class="card-header"><h3 class="card-title">Bautisos Mensuales</h3></div>
                  <div class="card-body"><div id="revenue-chart"></div></div>
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
      // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
      // IT'S ALL JUST JUNK FOR DEMO
      // ++++++++++++++++++++++++++++++++++++++++++

        var meses = @json($meses);
        var datosDesafio = @json($desafios);
        var datosAlcanzados = @json($alcanzados);

      const sales_chart_options = {
        series: [
          {
            name: 'Desafio',
            data: datosDesafio,
          },
          {
            name: 'Alcanzado',
            data: datosAlcanzados,
          },
        ],
        //tamaño de cada espacio
        chart: {
          height: 300,
          type: 'area',
          toolbar: {
            show: true,
          },
        },
        //leyendas
        legend: {
          show: true,
        },
        //colores de las lineas y los resultados en cada mes
        colors: ['#0d6efd', '#20c997'],
        dataLabels: {
          enabled: true,
        },
        //la linea de 1 punto a otro punto
        stroke: {
          curve: 'smooth',
        },
        //columna x
        xaxis: {
          type: 'category',
          categories: [
            'enero',
            'febrero',
            'marzo',
            'abril',
            'mayo',
            'junio',
            'julio',
            'agosto',
            'septiembre',
            'octubre',
            'noviembre',
            'diciembre',
          ],
        },
        tooltip: {
          x: {
            show: 'true',
          },
        },
      };
      const sales_chart = new ApexCharts(
        document.querySelector('#revenue-chart'),
        sales_chart_options,
      );
      sales_chart.render();
    </script> 

     
@endpush