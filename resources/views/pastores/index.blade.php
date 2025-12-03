@extends('template')


@section('title', 'Tablas')

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
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Pastores</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Pastores</li>
                </ol>
              </div>
              <div class="row">
                <a href="{{route('pastores.create')}}"><button type="button" class="btn btn-primary"> <i class="bi bi-person-plus-fill"></i> &nbsp Añadir nuevo registro </button> </a><br>
              </div>
              <div class="row pt-3">
                <a href="{{route('pastores.indexdelete')}}"><button type="button" class="btn btn-danger"> <i class="bi bi-person-fill-x"></i> &nbsp Pastores Desactivados </button> </a><br>
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
                                Tabla de Pastores
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Nombre Completo</th>
                                            <th>ci</th>
                                            <th>cargo</th>
                                            <th>celular</th>
                                            <th>edad</th>
                                            <th>Informacion</th>
                                            <th>foto</th>
                                            <th>acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($personas as $persona)
                                        <tr>
                                            <td>
                                            {{$persona->id_persona}} &nbsp {{$persona->nombre}} &nbsp {{$persona->ape_paterno}} &nbsp  {{$persona->ape_materno }}
                                            </td>
                                            <td>
                                                {{$persona->ci}}
                                            </td>
                                            <td>
                                                {{$persona->cargo}}
                                            </td>
                                            <td>
                                                {{$persona->celular}}
                                            </td>
                                            <td> 
                                                {{ \Carbon\Carbon::parse($persona->fecha_nac)->age }}

                                            </td>
                                           
                                            <td> 
                                                @if ($persona->fecha_ordenacion)
                                                    <span class="badge bg-success fs-6">
                                                        <i class="bi bi-check-circle-fill"></i> Pastor Ordenado
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger fs-6">
                                                        <i class="bi bi-x-circle-fill"></i> No Ordenado
                                                    </span>
                                                @endif
                                                <br>
                                                @if ($persona->fecha_contratacion)
                                                    <span class="badge bg-primary mt-1 fs-6">
                                                        <i class="bi bi-briefcase-fill"></i> Pastor Contratado
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning mt-1 fs-6" >
                                                        <i class="bi bi-exclamation-triangle-fill"></i> No Contratado
                                                    </span>
                                                @endif
                                            </td>
                                             <td>
                                                <img src="{{ $persona->foto_perfil ? Storage::url($persona->foto_perfil) : asset('img/user-default.png') }}" class="shadow" alt="User Image" height="120px"/>
                                            </td>
                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                
                                                    

                                                    <a href="{{ route('pastores.edit', $persona->id_persona) }}" class="btn btn-warning">
                                                        <i class="bi bi-pencil-square"></i> Editar
                                                    </a>

                                                    @if(!$persona->asignado)
                                                        <form action="{{ route('administrativos.store') }}" method="POST">
                                                        @csrf
                                                            <input type="hidden" id="id_persona" name="id_persona" value="{{ $persona->id_persona}}" >
                                                            <button type="submit" class="btn btn-success"> <i class="bi bi-person-up"></i> Administrativo</button>
                                                        </form>
                                                    @endif

                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$persona->id_persona}}"> <i class="bi bi-trash3-fill"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="confirmModal-{{$persona->id_persona}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de Confirmacion</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <strong style="color: red;">¿Seguro que quieres eliminar a este pastor? </strong><br>
                                                    <strong> Nombre: </strong> {{$persona->nombre}} &nbsp {{$persona->ape_paterno}} &nbsp  {{$persona->ape_materno }}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <form action="{{ route('pastores.destroy',['pastore'=>$persona->id_persona])}}" method="POST">
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
                                            <th>Nombre Completo</th>
                                            <th>ci</th>
                                            <th>cargo</th>
                                            <th>celular</th>
                                            <th>edad</th>
                                            <th>Informacion</th>
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