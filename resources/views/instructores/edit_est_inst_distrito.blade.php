@extends('template')


@section('title', 'Bautisos')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

<x-alerts />
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Desafios Instructores y Estudiantes Distritales - {{$anioActual}}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{route('instructores.mbos.distrital')}}">Desafios Instructores e Estudiantes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Asignacion de Desafios Instructores e Estudiantes</li>
                    </ol>
              </div>
            </div>
            <div class="row justify-content-end">
                <!-- El botón se mueve al final de la fila -->
                <div class="col-auto"> 
                    <a href="{{ route('instructores.mbos.distrital') }}">
                        <button type="button" class="btn btn-primary "> 
                            <i class="bi bi-arrow-left"></i> &nbsp;Volver 
                        </button>
                    </a>
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
                                    Tabla de Desafios Anuales por Iglesias
                                </div>
                                <div class="card-body">
                                    <table id="example-iglesias" class="display">
                                        <thead>
                                            <tr>
                                                <th>Cod desafio</th>
                                                <th>Iglesia</th>
                                                <th>Tipo</th>
                                                <th>Desafio Instructores</th>
                                                <th>Desafio Estudiantes</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($desafio_iglesias as $di)
                                            <tr @if($di->tipo === 'Filial') style="color: red;" @endif>
                                                
                                                <td>
                                                    {{ $di->id_desafio_iglesia}}  
                                                </td>
                                                <td>
                                                    {{ $di->nombre}}  
                                                </td>
                                                <td>
                                                    {{ $di->tipo}}  
                                                </td>
                                                <td>
                                                    {{ $di->desafio_instructores}}
                                                </td>
                                                <td>
                                                    {{ $di->desafio_estudiantes}}
                                                </td>    
                                                <td> 
                                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                        

                                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal-iglesias-{{$di->id_desafio_iglesia}}"> <i class="bi bi-pencil-square"></i> Asignar</button>

                                                    </div>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="confirmModal-iglesias-{{$di->id_desafio_iglesia}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de Confirmacion</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('desafios.update.est_inst', $di->id_desafio_iglesia) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <strong>Iglesia:</strong>  {{ $di->nombre}}  <br>
                                                                <strong>Tipo:</strong>  {{ $di->tipo}}  <br>
                                                            </div>
                                                            <hr>
                                                            <!-- Campo editable-->
                                                            <div class="mb-3">
                                                                <label for="desafio_instructores" class="form-label"><strong>Desafio Instructores :</strong></label>
                                                                <input type="number" class="form-control" name="desafio_instructores" id="desafio_instructores" value="{{ old('desafio_instructores', $di->desafio_instructores) }}" 
                                                                min="0" 
                                                                required
                                                                >
                                                                @error('desafio_instructores')
                                                                <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <!-- Campo editable -->
                                                            <div class="mb-3">
                                                                <label for="desafio_estudiantes" class="form-label"><strong>Desafio Estudiantes :</strong></label>
                                                                <input type="number" class="form-control" name="desafio_estudiantes" id="desafio_estudiantes" value="{{ old('desafio_estudiantes', $di->desafio_estudiantes) }}" 
                                                                min="0" required >
                                                                @error('desafio_estudiantes')
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
                                                <th>Cod desafio</th>
                                                <th>Iglesia</th>
                                                <th>Tipo</th>
                                                <th>Desafio Instructores</th>
                                                <th>Desafio Estudiantes</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                            </div>

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
        $('#example-iglesias').DataTable({
            scrollX: true,
            pageLength: 50,
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