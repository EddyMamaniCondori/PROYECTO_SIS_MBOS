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
              <div class="col-sm-6"><h3 class="mb-0">Dashboard General</h3></div>
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
              
              <div class="col-lg-6 connectedSortable">
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Instructores Biblicos Mensuales</h3></div>
                  <div class="card-body"><div id="chart-instructores"></div></div>
                </div>
                
              </div>


              <div class="col-lg-6 connectedSortable">
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Bautisos Mensuales</h3></div>
                  <div class="card-body"><div id="chart-bautisos"></div></div>
                </div>
                
              </div>

            </div>
            <div class="row">
              <div class="col-lg-6 connectedSortable">
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Estudiantes Biblicos Mensuales</h3></div>
                  <div class="card-body"><div id="chart-estudiantes"></div></div>
                </div>
                
              </div>


              <div class="col-lg-6 connectedSortable">
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Visitas Mensuales</h3></div>
                  <div class="card-body"><div id="chart-visitas"></div></div>
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
        var datosDesafio_bau = @json($desafios_bau);
        var datosAlcanzados_bau = @json($alcanzados_bau);

        var datosDesafio_est = @json($desafios_est);
        var datosAlcanzados_est = @json($alcanzados_est);

        var datosDesafio_ins = @json($desafios_ins);
        var datosAlcanzados_ins = @json($alcanzados_ins);

        var datosDesafio_vis = @json($desafios_vis);
        var datosAlcanzados_vis = @json($alcanzados_vis);
      //bautisos
      const sales_chart_options = {
        series: [
          {
            name: 'Desafio',
            data: datosDesafio_bau,
          },
          {
            name: 'Alcanzado',
            data: datosAlcanzados_bau,
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
        document.querySelector('#chart-bautisos'),
        sales_chart_options,
      );
      sales_chart.render();


      // VISITAS

      const visitas_chart_options = {
        series: [
          {
            name: 'Desafio',
            data: datosDesafio_vis,
          },
          {
            name: 'Alcanzado',
            data: datosAlcanzados_vis,
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
      const visitas_chart = new ApexCharts(
        document.querySelector('#chart-visitas'),
        visitas_chart_options,
      );
      visitas_chart.render();
      //ESTUDIANTES

      
      const estudiantes_chart_options = {
        series: [
          {
            name: 'Desafio',
            data: datosDesafio_est,
          },
          {
            name: 'Alcanzado',
            data: datosAlcanzados_est,
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
      const estudiantes_chart = new ApexCharts(
        document.querySelector('#chart-estudiantes'),
        estudiantes_chart_options,
      );
      estudiantes_chart.render();

      //INSTRUCTROES

      
      const instructores_chart_options = {
        series: [
          {
            name: 'Desafio',
            data: datosDesafio_ins,
          },
          {
            name: 'Alcanzado',
            data: datosAlcanzados_ins,
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
      const instructores_chart = new ApexCharts(
        document.querySelector('#chart-instructores'),
        instructores_chart_options,
      );
      instructores_chart.render();
    
    </script> 

    
     
@endpush