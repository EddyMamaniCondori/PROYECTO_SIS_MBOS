@extends('template')


@section('title', 'Tablas')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--bootstrap select-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">


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
              <div class="col-sm-6"><h3 class="mb-0">Asignaciones de Iglesias</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{route('iglesias.index')}}">Iglesias</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Asignaciones de Iglesias</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
            <div class="container-fluid">
                <!--begin::TABLA-->
                <div class="row">
                    <hr>
                    <div class="d-flex align-items-center justify-content-between">
                        
                        <h5 class="mb-0">
                            <i class="bi bi-check2-circle me-2"></i> <strong>Iglesias Asignadas a un Distrito</strong> 
                        </h5>
                        <span>
                            Total: {{ count($iglesiasConDistrito) }}
                        </span>
                    </div>

                    <div class="card mb-4 mt-4">
                        

                                    <div class="card-header">
                                        <i class="fas fa-table me-1"></i>
                                        Tabla de Iglesias
                                    </div>
                                    <div class="card-body">
                                        <table id="example" class="display">
                                            <thead>
                                                <tr>
                                                    <th>Cod </th>
                                                    <th>Nombre </th>
                                                    <th>Distrito</th>
                                                    <th>Tipo</th>
                                                    <th>Lugar</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($iglesiasConDistrito as $iglesia)
                                                <tr>
                                                    <td>
                                                        {{$iglesia->id_iglesia}}/{{$iglesia->codigo}}
                                                    </td>
                                                    <td>
                                                    {{$iglesia->nombre}} 
                                                    </td>
                                                    <td>
                                                        @if (!is_null($iglesia->distrito_id))
                                                            {{$iglesia->distrito_id}}/{{ $iglesia->nombre_distrito }}
                                                        @else
                                                            <span class="badge bg-warning mt-1 fs-6">
                                                                <i class="bi bi-exclamation-triangle-fill"></i> Sin Distrito
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$iglesia->tipo}}
                                                    </td>
                                                    <td>
                                                        {{$iglesia->lugar}}
                                                    </td>
                                                    <td> 
                                                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">


                                                            <form action="{{ route('iglesias.liberar', $iglesia->id_iglesia) }}" method="POST">@csrf
                                                                <button type="submit" class="btn btn-danger"> <i class="bi bi-unlock"></i>  &nbsp; Liberar</button>
                                                            </form>

                                                            <button type="button" 
                                                                    class="btn btn-warning btn-open-modal" 
                                                                    data-id="{{$iglesia->id_iglesia}}" 
                                                                    data-nombre="{{$iglesia->nombre}}" 
                                                                    data-tipo="cambiar">
                                                                <i class="bi bi-arrow-repeat"></i>&nbsp;  Cambiar
                                                            </button>
                                                         </div>
                                                    </td>
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
                                                    <th>Acciones</th>
                                                </tr>
                                            </tfoot>
                                        </table>

                                    </div>
                    </div>
                </div>







                <div class="row">
                    <hr>
                    <div class=" mb-3 d-flex align-items-center justify-content-between">
                        
                        <h5 class="mb-0">
                             <i class="bi bi-exclamation-circle me-2"></i> <strong>Iglesias sin Asignacion</strong> 
                        </h5>
                        <span>
                            Total: {{ count($iglesiasSinDistrito) }}
                        </span>
                    </div>

                    <div class="card mb-4">

                                    <div class="card-header">
                                        <i class="fas fa-table me-1"></i>
                                        Tabla de Iglesias
                                    </div>
                                    <div class="card-body">
                                        <table id="example1" class="display">
                                            <thead>
                                                <tr>
                                                    <th>Cod </th>
                                                    <th>Nombre </th>
                                                    <th>Distrito</th>
                                                    <th>Tipo</th>
                                                    <th>Lugar</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($iglesiasSinDistrito as $iglesiaS)
                                                <tr>
                                                    <td>
                                                        {{$iglesiaS->id_iglesia}} / {{$iglesiaS->codigo}}
                                                    </td>
                                                    <td>
                                                    {{$iglesiaS->nombre}} 
                                                    </td>
                                                    <td>    
                                                        @if (!is_null($iglesiaS->distrito_id))
                                                             {{$iglesiaS->distrito_id}} / {{ $iglesiaS->nombre_distrito }}
                                                        @else
                                                            <span class="badge bg-warning mt-1 fs-6">
                                                                <i class="bi bi-exclamation-triangle-fill"></i> Sin Distrito
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$iglesiaS->tipo}}
                                                    </td>
                                                    <td>
                                                        {{$iglesiaS->lugar}}
                                                    </td>

                                                    <td> 
                                                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                            <button type="button" 
                                                                    class="btn btn-success btn-open-modal" 
                                                                    data-id="{{$iglesiaS->id_iglesia}}" 
                                                                    data-nombre="{{$iglesiaS->nombre}}" 
                                                                    data-tipo="asignar">
                                                                <i class="bi bi-link"></i> &nbsp; Asignar
                                                            </button>

                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Cod </th>
                                                    <th>Nombre </th>
                                                    <th>Distrito</th>
                                                    <th>Tipo</th>
                                                    <th>Lugar</th>
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

        <!-- MODAL ÚNICO REUTILIZABLE -->
        <div class="modal fade" id="modalDistrito" tabindex="-1" aria-labelledby="modalDistritoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <form id="formDistrito" method="POST" action="">
                @csrf
                <div class="modal-header">
                <h5 class="modal-title" id="modalDistritoLabel">Asignar Distrito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                <p id="modalTextoAccion"></p>

                <label for="distrito_id" class="form-label">Distritos Disponibles:</label>
                <select data-size="9" title="-- Seleccione un Distrito --" data-live-search="true" 
                        name="distrito_id" id="modal_distrito_id" 
                        class="form-control selectpicker show-tick">
                    @foreach($distritos as $distrito)
                        <option value="{{ $distrito->id_distrito }}">
                            {{ $distrito->id_distrito }}/{{ $distrito->nombre }}
                        </option>
                    @endforeach
                </select>

                <input type="hidden" name="id_iglesia" id="modal_id_iglesia">
                <input type="hidden" name="accion" id="modal_accion">
                </div>

                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-danger" id="modal_btn_confirmar">Confirmar</button>
                </div>
            </form>
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

    $(document).ready(function() {
        $('#example1').DataTable({
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
     <!--bootstrap select-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
  
    <script>
        $(document).ready(function() {
            // Cuando se hace clic en un botón de abrir modal
            $(document).on('click', '.btn-open-modal', function() {
                const id = $(this).data('id');
                const nombre = $(this).data('nombre');
                const tipo = $(this).data('tipo');

                $('#modal_id_iglesia').val(id);
                $('#modal_accion').val(tipo);

                let textoAccion = '';
                let titulo = '';
                let colorBoton = '';

                if (tipo === 'cambiar') {
                    textoAccion = `<strong style="color:red;">¿Seguro que quieres cambiar el distrito de la iglesia?</strong><br>
                                <strong>Nombre:</strong> ${nombre}`;      
                    titulo = 'Cambiar Distrito';
                    colorBoton = 'btn-warning';
                } else {
                    textoAccion = `<strong style="color:green;">Vamos a asignar un distrito a la iglesia:</strong><br>
                                <strong>Nombre:</strong> ${nombre}`;
                    titulo = 'Asignar Distrito';
                    colorBoton = 'btn-success';
                }

                $('#modalDistritoLabel').text(titulo);
                $('#modalTextoAccion').html(textoAccion);
                $('#modal_btn_confirmar')
                    .removeClass('btn-danger btn-success btn-warning')
                    .addClass(colorBoton);

                // Aquí defines la ruta de destino según tu lógica
                // Por ejemplo: /iglesias/{id}/asignar o /iglesias/{id}/cambiar
                const actionUrl = tipo === 'cambiar'
                    ? `/iglesias/${id}/cambiar-distrito`
                    : `/iglesias/${id}/asignar-distrito`;
                $('#formDistrito').attr('action', actionUrl);

                // Mostrar el modal
                const modal = new bootstrap.Modal(document.getElementById('modalDistrito'));
                modal.show();
            });
        });
    </script>


@endpush