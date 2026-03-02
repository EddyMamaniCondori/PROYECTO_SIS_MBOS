@extends('template')


@section('title', 'Bautisos')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

@php
            $meses_array = [
                        1 => 'Enero',
                        2 => 'Febrero',
                        3 => 'Marzo',
                        4 => 'Abril',
                        5 => 'Mayo',
                        6 => 'Junio',
                        7 => 'Julio',
                        8 => 'Agosto',
                        9 => 'Septiembre',
                        10 => 'Octubre',
                        11 => 'Noviembre',
                        12 => 'Diciembre'
                    ];
            
@endphp
<x-alerts/>
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">completa tus visitas - {{$anioActual}}</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Meses Visitas</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabla de Visitas
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>codigo DesfMensual</th>
                                            <th>Mes - Anio</th>
                                            <th>Desafio</th>
                                            <th>Alcanzado</th>
                                            <th>Limite de Registro</th>
                                            <th>acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($visitas as $visita)
                                        <tr>
                                            <td>
                                                {{$visita->id_mensual}}
                                            </td>
                                            <td>
                                                {{$meses_array[$visita->mes]}} - {{$visita->anio}}
                                            </td>
                                            <td>
                                                {{$visita->desafio_visitas}} 
                                            </td>
                                            <td>
                                                @if($visita->visitas_alcanzadas < $visita->desafio_visitas)
                                                    <span class="text-danger">{{ $visita->visitas_alcanzadas }}</span>  {{-- rojo --}}
                                                @elseif($visita->visitas_alcanzadas > $visita->desafio_visitas)
                                                    <span class="text-success">{{ $visita->visitas_alcanzadas }}</span> {{-- verde --}}
                                                @else
                                                    <span class="text-warning">{{ $visita->visitas_alcanzadas }}</span> {{-- amarillo si es igual --}}
                                                @endif
                                            </td>
                                            <td>
                                                {{$visita->fecha_limite}}
                                            </td>
                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                    <!--<a href="{{ route('visitas.llenar_mes', $visita->id_mensual) }}" class="btn btn-primary">
                                                        <i class="bi bi-pencil-square"></i> Completar
                                                    </a>-->
                                                    @if (now()->startOfDay()->lte(\Carbon\Carbon::parse($visita->fecha_limite)->startOfDay()))
                                                        <a href="{{ route('visita_cape.llenar_mes', $visita->id_mensual) }}" class="btn btn-success">
                                                            <i class="bi bi-archive"></i> Completar
                                                        </a>
                                                    @else
                                                        <a href="{{ route('visita_cape.llenar_mes', $visita->id_mensual) }}" class="btn btn-primary">
                                                            <i class="bi bi-pencil-square"></i> Ver
                                                        </a>
                                                    @endif
                                            </td>   
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Mes - Anio</th>
                                            <th>Desafio</th>
                                            <th>Alcanzado</th>
                                            <th>Limite de Registro</th>
                                            <th>acciones</th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>


            <div class="card-body">
          </div>
          <!--end::Container-->
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