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
              <div class="col-sm-6"><h3 class="mb-0">Distritos Inhabilitados

                                    </h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('distritos.index')}}">Distritos</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Distritos Inhabilitados</li>
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
                                Tabla de Distritos
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>nro de iglesias</th>
                                            <th>pastor distrital</th>
                                            <th>grupo pequeño</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distritos as $distrito)
                                        <tr>
                                            <td>
                                             {{$distrito->nombre}}
                                            </td>
                                            <td>
                                                {{$distrito->nro_iglesias}}
                                            </td>
                                            <td>
                                                @if (!is_null($distrito->id_pastor))
                                                    {{ $distrito->nombre_pastor }}
                                                    &nbsp;{{ $distrito->ape_paterno_pastor }}
                                                    &nbsp;{{ $distrito->ape_materno_pastor }}
                                                @else
                                                    <span class="badge bg-warning mt-1 fs-6">
                                                        <i class="bi bi-exclamation-triangle-fill"></i> No Tiene Pastor Distrital
                                                    </span>
                                                @endif

                                            </td>
                                            <td>
                                                {{ $distrito->id_grupo }} &nbsp;

                                                @if ($distrito->dist_nombre)
                                                    {{ $distrito->dist_nombre }}
                                                @else
                                                    <span class="badge bg-warning mt-1 fs-6">
                                                        <i class="bi bi-exclamation-triangle-fill"></i> Sin Grupo
                                                    </span>
                                                @endif
                                            </td>

                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                <form action="{{ route('distritos.historial', $distrito->id_distrito) }}" method="get">
                                                    <button type="submit" class="btn btn-primary"> <i class="bi bi-clock-history"></i>  Historial</button>
                                                </form>

                                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$distrito->id_distrito}}"> <i class="bi bi-person-fill-check"></i>Reactivar</button>
                                            </td>
                                        </tr>

                                            <!-- Modal -->
                                            <div class="modal fade" id="confirmModal-{{$distrito->id_distrito}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de Confirmacion</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <strong style="color: green;">¿Seguro que quieres Reactivar a este distrito? </strong><br>
                                                    <center><strong> <h3>Nombre: </strong> {{$distrito->nombre}}</h3></center>
                                                    <hr>
                                                    
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <form action="{{ route('distritos.reactive',['id'=>$distrito->id_distrito])}}" method="POST">
                                                        @csrf
                                                    <button type="submit" class="btn btn-success">Confirmar</button>
                                                    </form>
                                                    
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>nro de iglesias</th>
                                            <th>pastor distrital</th>
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