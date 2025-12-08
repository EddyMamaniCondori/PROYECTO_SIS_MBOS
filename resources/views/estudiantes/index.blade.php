@extends('template')


@section('title', 'Bautisos')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

<x-alerts/>
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Estudiantes Biblicos - {{$anio}}</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Estudiantes</li>
                </ol>
              </div>
              <div class="row">
                <a href="{{route('estudiantes.create')}}"><button type="button" class="btn btn-primary"> <i class="fa-solid fa-plus"></i> &nbsp Nuevo registro</button> </a><br>
              </div>
              <div class="row">
                <form action="{{ route('estudiantes.filtrar') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="anio">Seleccione gestión:</label>
                            @if ($anios->count() > 0)
                                <select name="anio" id="anio" class="form-control">
                                    @foreach ($anios as $an)
                                        <option value="{{ $an }}" {{ (($anioSeleccionado ?? null) == $an) ? 'selected' : '' }}>

                                            {{ $an }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                {{-- opcional: mostrar nada o un mensaje --}}
                                <p class="text-muted">No existen gestiones registradas.</p>
                            @endif


                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100">Filtrar</button>
                        </div>
                    </div>
                </form>
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
                                Tabla de Estudiantes biblicos
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Sexo</th>
                                            <th>Contacto</th>
                                            <th>Edad</th>
                                            <th>Celular</th>
                                            <th>Estado civil</th>
                                            <th>Ci</th>
                                            <th>Curso biblico</th>
                                            <th>Bautizado</th>
                                            <th>Iglesia</th>
                                            <th>acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($estudiantes as $estudiante)
                                        <tr>
                                            <td>
                                            {{$estudiante->id_est}} &nbsp {{$estudiante->nombre}} &nbsp {{$estudiante->ape_paterno}} &nbsp {{$estudiante->ape_materno}} 
                                            </td>
                                            <td>
                                                {{$estudiante->sexo}}
                                            </td>
                                            <td>
                                                {{$estudiante->opcion_contacto}}
                                            </td>
                                            <td>
                                                {{$estudiante->edad}}
                                            </td>
                                            <td>
                                                {{$estudiante->celular}}
                                            </td>
                                            <td> 
                                                {{$estudiante->estado_civil}}
                
                                            </td>
                                            <td> 
                                                {{$estudiante->ci}}
                                            </td>
                                            <td> 
                                                {{$estudiante->curso_biblico_usado}}
                                            </td>
                                            <td> 
                                                @if($estudiante->bautizado)
                                                    <i class="bi bi-check2-circle" style="color: green;"></i>
                                                @else
                                                    <i class="bi bi-x-circle" style="color: red"></i>
                                                @endif
                                            </td>
                                            <td> 
                                                {{$estudiante->nombre_iglesia}}
                                            </td>
                                            
                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                            
                                                <a href="{{ route('estudiantes.edit', $estudiante->id_est) }}" class="btn btn-warning">
                                                    <i class="bi bi-pencil-square"></i> Editar
                                                </a>

                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$estudiante->id_est}}">Eliminar</button>
                                            </td>
                                        </tr>
                                         <div class="modal fade" id="confirmModal-{{$estudiante->id_est}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de Confirmacion</h1>
                                                    
                                                </div>
                                                <div class="modal-body">
                                                    <strong style="color: red;">¿Seguro que quieres eliminar Estudiante de la biblia?. Despues no podra volver a recuperarlo </strong><br>
                                                    <strong> Nombre: </strong> {{$estudiante->id_est}} &nbsp {{$estudiante->nombre}} &nbsp {{$estudiante->ape_paterno}} &nbsp {{$estudiante->ape_materno}} 
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <form action="{{ route('estudiantes.destroy', ['estudiante' => $estudiante->id_est]) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Confirmar</button>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>nombre Completo</th>
                                            <th>sexo</th>
                                            <th>opcion de contacto</th>
                                            <th>edad</th>
                                            <th>celular</th>
                                            <th>estado civil</th>
                                            <th>ci</th>
                                            <th>curso biblico usado</th>
                                            <th>bautizado</th>
                                            <th>iglesia</th>
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