@extends('template')

@section('title', 'Tabla Distrital - Remesas')

@push('css')

<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="content-wrapper">

  <div class="content-header">
    <div class="container-fluid">
      <h2 class="mb-3">Reporte Distrital de Remesas ({{ $anio }})</h2>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">

      {{-- Buttons export --}}
      <div class="mb-3">
        <a href="{{ route('remesas.tabla.excel.direct')}}" class="btn btn-success" >
          <i class="fa-solid fa-file-excel"></i> Exportar Excel
        </a>

        <a href="{{ route('remesas.tabla.pdf')}}" class="btn btn-danger" target="_blank">
          <i class="fa-solid fa-file-pdf"></i> Exportar PDF
        </a>
      </div>
      <div class="app-content">
        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-table me-1"></i>
                <h3 class="card-title">Tabla Distrital</h3>
                </div>
                <div class="card-body">
                <table id="tablaDistrital" class="table table-bordered table-striped display">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Distrito</th>
                        <th>Desafío</th>
                        <th>Total Anual</th>
                        <th>Diferencia</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($result as $i => $r)
                        @php
                        $dif = $r->total_anual - $r->blanco_monto;
                        @endphp
                        <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $r->nombre_distrito }}</td>
                        <td>{{ number_format($r->blanco_monto, 0, ',', '.') }} Bs</td>
                        <td>{{ number_format($r->total_anual, 0, ',', '.') }} Bs</td>
                        <td class="{{ $dif < 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($dif, 0, ',', '.') }} Bs
                        </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Distrito</th>
                        <th>Desafío</th>
                        <th>Total Anual</th>
                        <th>Diferencia</th>
                    </tr>
                    </tfoot>
                </table>
                </div>
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
        $('#tablaDistrital').DataTable({
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
@endpush
