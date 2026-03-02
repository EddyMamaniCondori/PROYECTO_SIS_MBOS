@extends('template')


@section('title', 'ASEA')

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
              <div class="col-sm-6"><h3 class="mb-0">Unidades Educativas - ASEA - {{ $anio }}
                                    </h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Unidades Educativas</li>
                </ol>
              </div>
              <div class="row">
                <a href="{{route('asea.create')}}"><button type="button" class="btn btn-success"> <i class="fa-solid fa-plus"></i> &nbsp Nueva Unidad Educativa</button> </a><br>
               
              </div>
              <div class="row pt-2 pb-2">
                <a href="{{route('asea.indexdelete')}}"><button type="button" class="btn btn-danger"> <i class="bi bi-person-fill-x"></i> &nbsp Unidades Educativas Inhabilitados </button> </a><br>
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
                                Tabla de Unidades Educativas
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Capellan</th>
                                            <th>Gestion</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ues as $ue)
                                        <tr>
                                            <td>
                                                {{$ue->nombre}}
                                            </td>


                                            <td>
                                                @if (!is_null($ue->id_persona))
                                                    {{ $ue->nombre_c }}
                                                    &nbsp; {{ $ue->ape_paterno}}
                                                    &nbsp; {{ $ue->ape_materno}}
                                                @else
                                                    <span class="badge bg-warning mt-1 fs-6">
                                                        <i class="bi bi-exclamation-triangle-fill"></i> No Tiene Capellan
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                {{$ue->año}}
                                            </td>

                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                <form action="{{ route('asea.asignarcape',['id'=>$ue->id_ue])}}" method="POST">
                                                @csrf
                                                @method('POST')
                                                    <button type="submit" class="btn btn-primary"> <i class="bi bi-clock-history"></i>  Asignaciones</button>
                                                </form>

                                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModalEdit-{{$ue->id_ue}}"> <i class="bi bi-pencil-square"></i>Editar</button>

                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$ue->id_ue}}"> <i class="bi bi-trash-fill"></i>Eliminar</button>
                                            </td>
                                        </tr>

                                            <!-- Modal  ELIMINACION-->
                                            <div class="modal fade" id="confirmModal-{{$ue->id_ue}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de Confirmacion</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <strong style="color: red;">¿Seguro que quieres eliminar a este distrito? </strong><br>
                                                            <strong> Nombre: </strong> {{$ue->nombre}}
                                                            
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                            <form action="{{ route('asea.destroy',['asea'=>$ue->id_ue])}}" method="POST">
                                                                @method('DELETE')
                                                                @csrf
                                                            <button type="submit" class="btn btn-danger">Confirmar</button>
                                                            </form>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal  Actualizacion-->
                                            <div class="modal fade" id="confirmModalEdit-{{$ue->id_ue}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Distrito</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <form action="{{ route('asea.update', $ue->id_ue) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="nombre" class="form-label">Nombre del Distrito</label>
                                                                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $ue->nombre }}" required>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Capellan</th>
                                            <th>Año</th>
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