@extends('template')


@section('title', 'Bautisos')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

@if (session('success'))
    <script>
        const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
        });
        Toast.fire({
        icon: "success",
        title: "{{ session('success') }}"
        });
    </script>
@endif
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


        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Desafio - Distrito {{$distrito->nombre_d}} </h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Desafios Distritales</li>
                </ol>
              </div>
            </div>

            <div class="row">
                <div class="card p-3 col-md-4">
                    <p><strong>Distrito: &nbsp; </strong> {{$distrito->nombre_d}}</p>
                    <p><strong>Pastor: &nbsp;</strong>{{$distrito->nombre_p}} {{$distrito->ape_paterno}} {{$distrito->ape_materno}}</p>
                    <p><strong>Cantidad Iglesias: &nbsp;</strong>{{$distrito->nro_iglesias}}</p>
                    <p><strong>Año: &nbsp;</strong>{{$distrito->año}}</p>
                </div>
                <div class="card p-3 col-4">
                    <div class="justify-content-center align-items-center g-2">
                        <h4>Desafio Anual de Bautisos</h4>
                    </div>
                    <div>
                        <label class="form-label">Desafío Bautizos:</label>
                        <input type="number"  class="form-control" value="{{ old('desafio_bautizo',$desafio->desafio_bautizo) }}" readonly>
                    </div>
                </div>
                <div class="card p-3 col-4">
                    <a href="{{route('desafios.index')}}"><button type="button" class="btn btn-secondary"> <i class="bi bi-x"></i> Volver </button></a>
                    <br>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal-bautizo">
                    <i class="bi bi-pencil-square"></i>Cambiar
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal-bautizo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Cambiar Desafio Anual de Bautizos  </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('desafios.update', $desafio->id_desafio)}}" method="POST">
                        @csrf
                        @method('PUT') 
                            <div class="modal-body">
                                <div class="col-md-6 mb-3">
                                    <label  class="form-label">Desafío Bautizos:</label>
                                    <input type="number" id="desafio_bautizo" name="desafio_bautizo" min="0" class="form-control" value="{{ old('desafio_bautizo', $desafio->desafio_bautizo) }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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

            <div class="row">
                <hr>
                <div class="row justify-content-center align-items-center g-2">
                    <h4>Desafio de Visitas mensuales Distrital</h4>
                </div>
                <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-table me-1"></i>
                                    Tabla de Desafios Mensuales
                                </div>
                                <div class="card-body">
                                    <table id="example" class="display">
                                        <thead>
                                            <tr>
                                                <th>Cod Desafio Mensual</th>
                                                <th>Mes - Año</th>
                                                <th>Desafio</th>
                                                <th>Alcanzado</th>
                                                <th>Fecha limite</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($visitas as $visita)
                                            <tr>
                                                <td>
                                                    {{ $visita->id_mensual}}
                                                </td>
                                                <td>
                                                    {{$meses_array[$visita->mes]}} - {{ $visita->anio}}   
                                                </td>
                                                <td>
                                                    {{ $visita->desafio_visitas}}
                                                </td>
                                                <td>
                                                    {{ $visita->visitas_alcanzadas}}
                                                </td>
                                                <td>
                                                    {{ $visita->fecha_limite}}
                                                </td>    
                                                <td> 
                                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModalmensual-{{$visita->id_mensual}}"> <i class="bi bi-pencil-square"></i> Asignar</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="confirmModalmensual-{{$visita->id_mensual}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de Confirmacion</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('mensuales_desafios.update', $visita->id_mensual) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <strong>Mes:</strong> {{$meses_array[$visita->mes]}} <br>
                                                                <strong>Anio:</strong> {{ $visita->anio}}   
                                                            </div>
                                                            <hr>
                                                            <!-- Campo editable -->
                                                            <div class="mb-3">
                                                                <label for="desafio_visitas" class="form-label"><strong>Desafio Visitas :</strong> </label>
                                                                <input  type="number" class="form-control" name="desafio_visitas" id="desafio_visitas" value="{{ old('desafio_visitas', $visita->desafio_visitas) }}" 
                                                                min="0"  required >
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
                                                <th>Cod Desafio Mensual</th>
                                                <th>Mes - Año</th>
                                                <th>Desafio</th>
                                                <th>Alcanzado</th>
                                                <th>Fecha limite</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                            </div>
            </div>

            <div class="row">
                <hr>
                <div class="row mb-3">
                    <div class="col-6 justify-content-center align-items-center g-2">
                        <h4>Desafio Anual por Iglesias</h4>
                    </div>
                    <div class="col-6 justify-content-center align-items-center g-2">
                        <form action="{{ route('desafios.completar-faltantes', $desafio->id_desafio) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                               <i class="bi bi-building-add"></i>  Completar iglesias faltantes
                            </button>
                        </form>
                    </div>
                </div>
                

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
                                            <tr @if($di-> tipo_iglesia === 'Filial') style="color: red;" @endif>
                                                
                                                <td>
                                                    {{ $di->id_desafio_iglesia}}  
                                                </td>
                                                <td>
                                                    {{ $di->nombre_iglesia}}  
                                                </td>
                                                <td>
                                                    {{ $di->tipo_iglesia}}  
                                                </td>
                                                <td>
                                                    {{ $di->desafio_instructores}}
                                                </td>
                                                <td>
                                                    {{ $di->desafio_estudiantes}}
                                                </td>    
                                                <td> 
                                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                        
                                                        <form action="{{ route('anual_iglesias.destroy', $di->id_desafio_iglesia) }}" 
                                                            method="POST" 
                                                            style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit" class="btn btn-danger" 
                                                                    onclick="return confirm('¿Seguro que deseas eliminar este desafío?')">
                                                                <i class="bi bi-trash"></i> Eliminar
                                                            </button>
                                                        </form>

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
                                                    <form action="{{ route('anual_iglesias.update', $di->id_desafio_iglesia) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <strong>Iglesia:</strong>  {{ $di->nombre_iglesia}}  <br>
                                                                <strong>Tipo:</strong>  {{ $di->tipo_iglesia}}  <br>
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

    <script>
    $(document).ready(function() {
        $('#example-iglesias').DataTable({
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