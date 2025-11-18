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
              <div class="col-sm-6"><h3 class="mb-0">Iglesias</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Iglesias</li>
                </ol>
              </div>
              <div class="row">
                <a href="{{route('iglesias.index_pastores/asignacion_lideres')}}"><button type="button" class="btn btn-success"> <i class="fa-solid fa-plus"></i> &nbsp Asignacion Masiva Lideres Locales</button> </a><br>
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
                                Tabla de Iglesias
                            </div>
                            <div class="card-body">
                                <table id="example" class="displays">
                                    <thead>
                                        <tr>
                                            <th>Cod </th>
                                            <th>Nombre </th>
                                            <th>Tipo</th>
                                            <th>Lugar</th>
                                            <th>Distrito</th>
                                            <th>direcion</th>
                                            <th>feligresia</th>
                                            <th>feligresia asistente</th>
                                            
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($iglesias as $iglesia)
                                        <tr>
                                            <td>
                                                {{$iglesia->codigo}}
                                            </td>
                                            <td>
                                             {{$iglesia->nombre}} 
                                            </td>
                                            <td>
                                                {{$iglesia->tipo}}
                                            </td>
                                            <td>
                                                {{$iglesia->lugar}}
                                            </td>
                                            <td>
                                                 @if (!is_null($iglesia->distrito_id))
                                                    {{ $iglesia->nombre_distrito }}
                                                @else
                                                    <span class="badge bg-warning mt-1 fs-6">
                                                        <i class="bi bi-exclamation-triangle-fill"></i> Sin Distrito
                                                    </span>
                                                @endif
                                            </td>
                                            <td> 
                                                {{$iglesia->ciudad}} &nbsp {{$iglesia->zona}} &nbsp {{$iglesia->calle}} &nbsp {{$iglesia->nro}}
                                            </td>
                                            <td>
                                                {{$iglesia->feligresia}}
                                            </td>
                                            <td>
                                                {{$iglesia->feligresia_asistente}}
                                            </td>
                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                                <form action="{{ route('iglesias.edit', $iglesia->id_iglesia)}}" method="get">
                                                    <button type="submit" class="btn btn-warning">Editar</button>
                                                </form>
                    
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Cod </th>
                                            <th>Nombre </th>
                                            <th>Tipo</th>
                                            <th>Lugar</th>
                                            <th>Distrito</th>
                                            <th>direcion</th>
                                            <th>feligresia</th>
                                            <th>feligresia asistente</th>
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