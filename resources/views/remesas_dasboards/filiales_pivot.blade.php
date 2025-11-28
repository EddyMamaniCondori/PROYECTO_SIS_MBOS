@extends('template')

@section('title', 'Reporte Mensual Filiales')

@push('css')
<!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
<style>
  .table thead th {
    background: #012a4a;
    color: white;
    text-align: center;
  }
  .kpi-box {
    min-height: 80px;
    color: white;
    padding: 15px;
    border-radius: 8px;
  }
</style>
@endpush

@section('content')
<x-alerts/>
<div class="content-wrapper">

  <div class="content-header">
    <div class="container-fluid">
      <h2 class="mb-2">Reporte de Remesas Mensuales de Filiales ({{ $anio }})</h2>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">

      <!-- BOTONES -->
      <div class="mb-3">
        <a href="{{ route('remesas.filiales.excel') }}" class="btn btn-success">
          <i class="fa-solid fa-file-excel"></i> Exportar Excel
        </a>

        <a href="{{ route('remesas.filiales.pdf') }}" class="btn btn-danger" target="_blank">
          <i class="fa-solid fa-file-pdf"></i> Exportar PDF
        </a>
      </div>
      <!-- KPIs -->
      <div class="row">

        <div class="col-lg-3 col-6">
          <div class="kpi-box bg-primary">
            <h4>Total Remesas</h4>
            <h3>{{ number_format($totales['total'], 0, ',', '.') }} Bs</h3>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="kpi-box bg-success">
            
            <h4>Filiales registradas</h4>
            <h3>{{ count($result) }}</h3>
          </div>
        </div>

      </div>

      <!-- TABLA PRINCIPAL -->
      <div class="card mt-3">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
          <h4 class="card-title">Tabla Mensual</h4>
        </div>

        <div class="card-body">
          <table id="example" class="table table-bordered table-hover table-striped">
            <thead>
              <tr>
                <th>Iglesia</th>
                <th>Distrito</th>
                <th>Ene</th><th>Feb</th><th>Mar</th><th>Abr</th>
                <th>May</th><th>Jun</th><th>Jul</th><th>Ago</th>
                <th>Sep</th><th>Oct</th><th>Nov</th><th>Dic</th>
                <th>Total</th>
              </tr>
            </thead>

            <tbody>
              @foreach($result as $r)
                <tr>
                  <td>{{ $r->codigo }} - {{ $r->nombre }}</td>
                  <td>{{ $r->distrito }}</td>
                  <td>{{ number_format($r->enero, 0, ',', '.') }}</td>
                  <td>{{ number_format($r->febrero, 0, ',', '.') }}</td>
                  <td>{{ number_format($r->marzo, 0, ',', '.') }}</td>
                  <td>{{ number_format($r->abril, 0, ',', '.') }}</td>
                  <td>{{ number_format($r->mayo, 0, ',', '.') }}</td>
                  <td>{{ number_format($r->junio, 0, ',', '.') }}</td>
                  <td>{{ number_format($r->julio, 0, ',', '.') }}</td>
                  <td>{{ number_format($r->agosto, 0, ',', '.') }}</td>
                  <td>{{ number_format($r->septiembre, 0, ',', '.') }}</td>
                  <td>{{ number_format($r->octubre, 0, ',', '.') }}</td>
                  <td>{{ number_format($r->noviembre, 0, ',', '.') }}</td>
                  <td>{{ number_format($r->diciembre, 0, ',', '.') }}</td>
                  <td class="fw-bold">{{ number_format($r->total_anual, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>

          </table>
        </div>
      </div>

      
      <div class="row">
        <!-- TOP 10 IGLESIAS -->
        <div class="card mt-4">
        <div class="card-header bg-success text-white">
            <h4 class="card-title">Top 10 Filiales con Mayor Remesa</h4>
        </div>
        <div class="card-body">
            <div id="grafica-top10" style="height:450px;"></div>
        </div>
        </div>
      </div>

    </div>
  </div>

</div>
@endsection

@push('js')
    <!--JQUERY-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!--data table-->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script>
    $(document).ready(function() {
        $('#example').DataTable({
            scrollX: true,
            order: false,
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

 <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous" ></script>
      <!-- ChartJS -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const top10 = @json($top10);
    var chartTop10 = new ApexCharts(document.querySelector("#grafica-top10"), {
      series: [{
        name: "Total Anual",
        data: top10.map(t => t.total_anual)
      }],
      chart: {
        type: 'bar',
        height: 450
      },
      colors: ['#3A3ADE'],
      plotOptions: {
        bar: { horizontal: true, borderRadius: 5 }
      },
      xaxis: {
        categories: top10.map(t => t.nombre),
        labels: { formatter: val => Math.round(val).toLocaleString('es-BO') }
      },
      tooltip: {
        y: { formatter: val => Math.round(val).toLocaleString('es-BO') }
      }
    });
    chartTop10.render();

  });
</script>


@endpush
