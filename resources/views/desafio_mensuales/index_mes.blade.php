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
              <div class="col-sm-6"><h3 class="mb-0">Desafios Mensuales de Visitacion - {{$anio}}</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Desafios Mensuales</li>
                </ol>
              </div>
                <div class="row">
                    
                    
                    
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal-mes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Desafio de {{$meses_array[$siguienteMes]}} </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('desafios_mensuales.store')}}" method="POST">
                        @csrf
                            <div class="modal-body">
                                <input type="hidden" name="mes" value="{{$siguienteMes}}">
                                <input type="hidden" name="anio" value="{{$anio}}">
                                <div class="col-md-6 mb-3">
                                    <label  class="form-label">Fecha Limite:</label>
                                    <input type="date" id="fecha_limite" name="fecha_limite" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Habilitar</button>
                            </div>
                            </div>
                        </form>
                    </div>
                    </div>

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
                                Tabla de Desafios Mensuales
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                         <tr>
                                            <th>Mes</th>
                                            <th>Año</th>
                                            <th>fecha limite</th>
                                            <th>Acciones</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mensuales as $mensual)
                                        <tr>
                                            <td>
                                                {{$meses_array[$mensual->mes]}}
                                            </td>
                                            <td class="text-center">
                                                {{ $mensual->anio}}
                                            </td>
                                             @php
                                                $fechaLimite = \Carbon\Carbon::parse($mensual->fecha_limite);
                                                $fechaHoy = \Carbon\Carbon::now();
                                                if ($fechaLimite->greaterThanOrEqualTo($fechaHoy)) {
                                                    $claseColor = 'text-success'; // Verde
                                                } else {
                                                    $claseColor = 'text-danger'; // Rojo
                                                }
                                            @endphp
                                            <td >
                                                <span class="{{ $claseColor }} fw-bold">{{ $fechaLimite->format('d/m/Y') }} </span>
                                            </td>
                                            <td> 
                                                <div class="btn-group btn-group" role="group" aria-label="Acciones Mensuales">
                                                    
                                                    <!-- 1. Ver Avance (Acción Principal de Lectura) -->
                                                    @can('graficos x mes MBOS-desafios mensuales')
                                                    <a href="{{ route('mensuales.dashboard', ['mes' => $mensual->mes, 'anio' => $mensual->anio]) }}"
                                                    class="btn btn-success" title="Ver gráficas y resumen">
                                                        <i class="bi bi-bar-chart"></i> Ver Avance
                                                    </a>
                                                    @endcan

                                                    <!-- 2. Asignación Masiva (Acción Importante de Escritura) -->
                                                    @can('editar desafios mes masivo-desafios mensuales')
                                                    <a href="{{ route('mensuales.asignar_desafio.masivo', ['mes' => $mensual->mes, 'anio' => $mensual->anio]) }}"
                                                    class="btn btn-info" title="Asignar desafíos a todas las iglesias">
                                                        <i class="bi bi-diagram-3"></i> Asignación Masiva
                                                    </a>
                                                    @endcan

                                                    <!-- 3. Asignar/Detalle (Acción de Escritura Menos Masiva) -->
                                                    @can('editar desafios-desafios mensuales')
                                                    <a href="{{ route('mensuales.asignar_desafio', ['mes' => $mensual->mes, 'anio' => $mensual->anio]) }}"
                                                    class="btn btn-primary" title="Asignar o editar desafíos individualmente">
                                                        <i class="bi bi-pencil-square"></i> Asignar
                                                    </a>
                                                    @endcan
                                                    
                                                    <!-- 4. Editar (Abre Modal de Edición, Acción secundaria) -->
                                                    @can('editar fechas-desafios mensuales')
                                                    <button type="button" 
                                                            class="btn btn-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#confirmModal-{{$mensual->mes}}"
                                                            title="Editar la fecha límite del mes"> 
                                                        <i class="bi bi-calendar"></i> Editar
                                                    </button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="confirmModal-{{$mensual->mes}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                      <h1 class="modal-title fs-5" id="exampleModalLabel">Cambiar fecha limite</h1>
                                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <form action="{{ route('desafios_mensuales.update', $mensual->mes)}}" method="POST">
                                                  @csrf
                                                  @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <strong>Mes:</strong> {{$meses_array[$mensual->mes]}}<br>
                                                            <strong>Año:</strong> {{$mensual->anio}}
                                                        </div>
                                                        <hr>
                                                        <input type="hidden" name="mes" value="{{ $mensual->mes}}">
                                                        <input type="hidden" name="anio" value="{{ $mensual->anio}}">
                                                        <!-- Campo editable -->
                                                        <div class="mb-3">
                                                            <label for="fecha_limite" class="form-label">
                                                            <strong>Fecha Limite:</strong>
                                                            </label>
                                                            <input 
                                                            type="date" 
                                                            class="form-control" 
                                                            name="fecha_limite" 
                                                            id="fecha_limite"  
                                                            required value="{{ old('fecha_limite', $mensual->fecha_limite) }}"
                                                            >
                                                            @error('fecha_limite')
                                                              <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-success">Confirmar</button>
                                                    </div>
                                                  </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Mes</th>
                                            <th>Año</th>
                                            <th>fecha limite</th>
                                            <th>Acciones</th>  
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