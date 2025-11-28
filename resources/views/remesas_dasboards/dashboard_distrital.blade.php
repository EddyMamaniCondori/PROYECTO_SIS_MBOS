{{-- resources/views/remesas_dasboards/dashboard_distrital_adminlte.blade.php --}}
@extends('template') {{-- o la plantilla donde cargas AdminLTE --}}

@section('title', 'Dashboard Distrital - Remesas')

@push('css')
  {{-- AdminLTE + FontAwesome + DataTables CSS --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.4/dist/css/adminlte.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
  <style>
    /* Estilos menores para mejor presentación */
    .kpi { min-height: 90px; }
    .chart-card .card-body { height: 360px; }
    .apexcharts-canvas { width:100% !important; }
  </style>
@endpush

@section('content')
<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard Distrital <small class="text-muted">Remesas {{ now()->year }}</small></h1>
        </div>
        <div class="col-sm-6 text-end">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">

      <!-- KPI Row -->
      <div class="row">
        {{-- KPI: Totales agregados (calculados en blade a modo demostrativo).
             Puedes reemplazar por valores calculados en el controlador. --}}
        @php
          $totalAlcanzado = array_sum(array_map(fn($d)=>($d['totales']['alcanzado'] ?? 0), $dataDistritos));
          $totalDesafio = array_sum(array_map(fn($d)=>($d['totales']['desafio'] ?? 0), $dataDistritos));
          $distritosActivos = count($dataDistritos);
          $porcCumplimiento = $totalDesafio > 0 ? round($totalAlcanzado / $totalDesafio * 100) : 0;
        @endphp

        <div class="col-lg-3 col-6">
          <div class="small-box bg-info kpi">
            <div class="inner">
              <h3>{{ number_format($totalAlcanzado, 0, ',', '.') }} <sup style="font-size: 14px">Bs</sup></h3>
              <p>Total Alcanzado</p>
            </div>
            <div class="icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-primary kpi">
            <div class="inner">
              <h3>{{ number_format($totalDesafio, 0, ',', '.') }} <sup style="font-size: 14px">Bs</sup></h3>
              <p>Total Desafío (Blancos)</p>
            </div>
            <div class="icon"><i class="fa-solid fa-bullseye"></i></div>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-success kpi">
            <div class="inner">
              <h3>{{ $distritosActivos }}</h3>
              <p>Distritos</p>
            </div>
            <div class="icon"><i class="fa-solid fa-map-location-dot"></i></div>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning kpi">
            <div class="inner">
              <h3>{{ $porcCumplimiento }}<sup style="font-size: 14px">%</sup></h3>
              <p>Porcentaje cumplimiento</p>
            </div>
            <div class="icon"><i class="fa-solid fa-chart-line"></i></div>
          </div>
        </div>
      </div>

      <!-- Main Row: Table + Charts -->
      <div class="row">
        <!-- Tabla -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><i class="fa-solid fa-table-list me-2"></i> Tabla de Remesas por Distrito</h3>
              <div class="card-tools">
                <button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Distrito</th>
                    <th>Desafío (Blanco)</th>
                    <th>Alcanzado</th>
                    <th>Diferencia</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($dataDistritos as $i => $d)
                    <tr>
                      <td>{{ $i + 1 }}</td>
                      <td>{{ $d['nombre_distrito'] }}</td>
                      <td>
                        @if(($d['totales']['desafio'] ?? 0) > 0)
                          {{ number_format($d['totales']['desafio'], 0, ',', '.') }} Bs
                        @else
                          <span class="text-muted">No definido</span>
                        @endif
                      </td>
                      <td>{{ number_format($d['totales']['alcanzado'] ?? 0, 0, ',', '.') }} Bs</td>
                      <td>
                        @php $diff = $d['totales']['diferencia'] ?? 0; @endphp
                        <span class="{{ $diff < 0 ? 'text-danger' : 'text-success' }}">
                          {{ number_format($diff, 0, ',', '.') }} Bs
                        </span>
                      </td>
                      <td>
                        <button class="btn btn-sm btn-info ver-graficas" data-index="{{ $i }}">
                          <i class="fa-solid fa-chart-simple"></i> Ver
                        </button>
                        <button class="btn btn-sm btn-secondary btn-modal-large" data-index="{{ $i }}">
                          <i class="fa-solid fa-expand"></i>
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Distrito</th>
                    <th>Desafío (Blanco)</th>
                    <th>Alcanzado</th>
                    <th>Diferencia</th>
                    <th>Acciones</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>

        <!-- Charts column -->
        <div class="col-lg-6">
          <div class="card chart-card">
            <div class="card-header">
              <h3 class="card-title">Remesa mensual distrital</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <div id="revenue-chart-mensual" style="height:320px"></div>
            </div>
          </div>

          <div class="card chart-card mt-3">
            <div class="card-header">
              <h3 class="card-title">Remesa Distrital - Blancos vs Alcanzado</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <div id="revenue-chart-baras" style="height:360px"></div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Modal grande para gráficas -->
<div class="modal fade" id="modalGraficasGrandes" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Gráficas Distritales</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-7">
            <div id="modal-chart-mensual" style="height:450px"></div>
          </div>
          <div class="col-md-5">
            <div id="modal-chart-baras" style="height:450px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('js')
  {{-- jQuery, Bootstrap, AdminLTE, DataTables, ApexCharts --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.4/dist/js/adminlte.min.js"></script>

  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script>

  <script>
    $(function () {
      // Datos enviados desde el controlador
      const dataDistritos = @json($dataDistritos ?? []);
      const meses = {!! json_encode($meses ?? ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']) !!};
      const anioActual = {{ now()->year }};

      // Contenedores
      const mensualContainer = document.querySelector('#revenue-chart-mensual');
      const barrasContainer = document.querySelector('#revenue-chart-baras');
      const modalMensual = document.querySelector('#modal-chart-mensual');
      const modalBaras = document.querySelector('#modal-chart-baras');

      // Instancias de charts
      let chartMensual = null;
      let chartBarras = null;
      let modalChartMensual = null;
      let modalChartBaras = null;

      // --- Inicialización segura DataTable ---
      if (!$.fn.DataTable.isDataTable('#example')) {
        $('#example').DataTable({
          responsive: true,
          scrollX: true,
          language: { url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" },
          pageLength: 10,
        });
      }

      // Funciones utilitarias
      function formatInt(val) {
        return Math.round(val).toLocaleString('es-BO');
      }
      function getColor(porcentaje) {
        if (porcentaje >= 80) return '#4CAF50';
        if (porcentaje >= 60) return '#FFC107';
        return '#F44336';
      }

      // --- Render / Update Mensual (area) ---
      function renderOrUpdateMensual(targetEl, series) {
        const options = {
          series: series,
          chart: { height: 420, type: 'area', toolbar: { show: true } },
          colors: ['#1E88E5'],
          dataLabels: { enabled: false },
          stroke: { curve: 'smooth', width: 3 },
          xaxis: { categories: meses },
          yaxis: {
            labels: { formatter: val => Math.round(val).toLocaleString('es-BO') },
            title: { text: 'Monto (Bs)' }
          },
          tooltip: { y: { formatter: val => formatInt(val) } },
          fill: { type: 'gradient', gradient: { shadeIntensity: 0.5, opacityFrom: 0.6, opacityTo: 0.0 } }
        };

        // si es el modal distinto tamaño
        if (targetEl === modalMensual) options.chart.height = 450;
        if (targetEl === mensualContainer) options.chart.height = 320;

        const isModal = (targetEl === modalMensual);

        if (isModal) {
          if (modalChartMensual) {
            modalChartMensual.updateOptions(options);
            modalChartMensual.updateSeries(series);
          } else {
            modalChartMensual = new ApexCharts(targetEl, options);
            modalChartMensual.render();
          }
        } else {
          if (chartMensual) {
            chartMensual.updateOptions(options);
            chartMensual.updateSeries(series);
          } else {
            chartMensual = new ApexCharts(targetEl, options);
            chartMensual.render();
          }
        }
      }

      // --- Render / Update Barras ---
      function renderOrUpdateBarras(targetEl, series) {
        const options = {
          series: series,
          chart: { type: 'bar', height: 420, toolbar: { show: true } },
          plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 6 } },
          dataLabels: { enabled: true, formatter: val => formatInt(val), style: { colors: ['#000'] } },
          xaxis: { categories: ['Año ' + anioActual] },
          yaxis: { labels: { formatter: val => Math.round(val).toLocaleString('es-BO') }, title: { text:'Monto (Bs)' } },
          tooltip: { y: { formatter: val => formatInt(val) } },
          legend: { position: 'top', horizontalAlign: 'center' },
          colors: ['#0288D1','#43A047','#E53935']
        };

        const isModal = (targetEl === modalBaras);
        if (isModal) options.chart.height = 450;
        if (targetEl === barrasContainer) options.chart.height = 360;

        if (isModal) {
          if (modalChartBaras) {
            modalChartBaras.updateOptions(options);
            modalChartBaras.updateSeries(series);
          } else {
            modalChartBaras = new ApexCharts(targetEl, options);
            modalChartBaras.render();
          }
        } else {
          if (chartBarras) {
            chartBarras.updateOptions(options);
            chartBarras.updateSeries(series);
          } else {
            chartBarras = new ApexCharts(targetEl, options);
            chartBarras.render();
          }
        }
      }

      // Render default (primer distrito si existe) - evita mostrar todo vacío
      if (dataDistritos.length > 0) {
        const defaultDistrito = dataDistritos[0];
        renderOrUpdateMensual(mensualContainer, defaultDistrito.series_mensual || [{ name:'Remesas Totales', data: Array(12).fill(0) }]);
        renderOrUpdateBarras(barrasContainer, defaultDistrito.series_baras || [
          { name: 'Blanco Anual', data: [defaultDistrito.totales?.desafio ?? 0] },
          { name: 'Alcanzado', data: [defaultDistrito.totales?.alcanzado ?? 0] },
          { name: 'Diferencia', data: [defaultDistrito.totales?.diferencia ?? 0] },
        ]);
      }

      // Delegación de eventos: Ver gráficas quick view
      $(document).on('click', '.ver-graficas', function (e) {
        e.preventDefault();
        const idx = parseInt($(this).data('index'), 10);
        if (isNaN(idx) || !dataDistritos[idx]) return;

        const d = dataDistritos[idx];
        const seriesMensual = d.series_mensual && d.series_mensual.length ? d.series_mensual : [{ name:'Remesas Totales', data: Array(12).fill(0) }];
        const seriesBaras = d.series_baras && d.series_baras.length ? d.series_baras : [
          { name: 'Blanco Anual', data: [d.totales?.desafio ?? 0] },
          { name: 'Alcanzado', data: [d.totales?.alcanzado ?? 0] },
          { name: 'Diferencia', data: [d.totales?.diferencia ?? 0] },
        ];

        renderOrUpdateMensual(mensualContainer, seriesMensual);
        renderOrUpdateBarras(barrasContainer, seriesBaras);

        // hacer scroll suave
        mensualContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });

      // Abrir modal grande con gráficas
      const modalEl = $('#modalGraficasGrandes');
      $(document).on('click', '.btn-modal-large', function (e) {
        e.preventDefault();
        const idx = parseInt($(this).data('index'), 10);
        if (isNaN(idx) || !dataDistritos[idx]) return;
        const d = dataDistritos[idx];

        const seriesMensual = d.series_mensual && d.series_mensual.length ? d.series_mensual : [{ name:'Remesas Totales', data: Array(12).fill(0) }];
        const seriesBaras = d.series_baras && d.series_baras.length ? d.series_baras : [
          { name: 'Blanco Anual', data: [d.totales?.desafio ?? 0] },
          { name: 'Alcanzado', data: [d.totales?.alcanzado ?? 0] },
          { name: 'Diferencia', data: [d.totales?.diferencia ?? 0] },
        ];

        // Render modal charts (se crean si no existen)
        renderOrUpdateMensual(modalMensual, seriesMensual);
        renderOrUpdateBarras(modalBaras, seriesBaras);

        modalEl.modal('show');
      });

      // Si el modal se cierra, opcionalmente destruir charts del modal para liberar memoria
      modalEl.on('hidden.bs.modal', function () {
        // Si quieres destruir y recrear al abrir, descomenta:
        // if (modalChartMensual) { modalChartMensual.destroy(); modalChartMensual = null; }
        // if (modalChartBaras)  { modalChartBaras.destroy(); modalChartBaras = null; }
      });

    }); // end $()
  </script>
@endpush
