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
                <div class="col-sm-6"><h3 class="mb-0">Asignaciones Distritales @foreach ($anios as $a)
                                                        {{ $a->año}}
                                                    @endforeach</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Distritos</li>
                    <li class="breadcrumb-item active" aria-current="page">Asignaciones Distritales</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="row">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Habilitar Asiganciones distritales @foreach ($anios as $a)
                                                            {{ $a->año+1}}
                                                        @endforeach
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Habilitar Asignaciones</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Estas seguro que quieres habilitar las asiganaciones distritales para el @foreach ($anios as $a)
                                                                    {{ $a->año+1}}
                                                                @endforeach
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <form action="{{ route('distritos.copiar.diriges') }}" method="get">
                                        <button type="submit" class="btn btn-success">
                                            Habilitar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal FIN-->
                    
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
                                            <th>pastor distrital</th>
                                            <th>fecha de asignacion</th>
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
                                                     {{$distrito->fecha_asignacion}}
                                                    </td>
                                                    <td> 
                                                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                            @if (!is_null($distrito->id_pastor))
                                                                <a href="{{ route('distritos.liberar', $distrito->id_distrito) }}" class="btn btn-danger">
                                                                Liberar Pastor
                                                                </a>
                                                            @endif
                                                            <!-- Liberar asignación -->
                                                            

                                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#staticBackdrop-{{$distrito->id_distrito}}">
                                                                Cambiar
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                         
                                                <!-- Modal -->
                                                    <div class="modal fade" id="staticBackdrop-{{$distrito->id_distrito}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                            <center><h5 class="modal-title" id="staticBackdropLabel" style="color: #0D1073"><strong>  DISTRITO - {{$distrito->nombre}}</strong></h5></center>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('distritos.cambiar', $distrito->id_distrito) }}" method="POST">
                                                            @csrf

                                                            <div class="modal-body">
                                                                @if (!is_null($distrito->id_pastor))
                                                                    <p style="color: red;"><strong>  IMPORTANTE:</strong> Recuerda que cuando realices el cambio al pastor <strong>{{ $distrito->nombre_pastor }}
                                                                    &nbsp;{{ $distrito->ape_paterno_pastor }}
                                                                    &nbsp;{{ $distrito->ape_materno_pastor }} &nbsp;</strong> dejara de tener acceso a las funciones en el sistema a este distrito</p>
                                                                    <P style="color: orange;"> OBS 2: el pastor {{ $distrito->nombre_pastor }}
                                                                        &nbsp;{{ $distrito->ape_paterno_pastor }}
                                                                        &nbsp;{{ $distrito->ape_materno_pastor }} &nbsp;Pasara al historial del distrito de {{$distrito->nombre}} con: <br>
                                                                        fecha inicio = {{$distrito->fecha_asignacion}} <br>
                                                                        fecha fin = {{ \Carbon\Carbon::now()->format('d/m/Y') }} <br>
                                                                    </P>
                                                                    <hr>
                                                                @endif
                                                                Escoge un pastor para el distrito...<br>
                                                                <label for="id_pastor" class="form-label">Pastores Disponibles:</label>
                                                                <select data-size="9" title="-- Seleccione un pastor --" data-live-search="true" name="id_pastor" id="id_pastor" class="form-control selectpicker show-tick" >
                                                                    @foreach($pastores_libres as $pastor)
                                                                        <option value="{{ $pastor->id_persona }}">
                                                                            {{ $pastor->nombre }} {{ $pastor->ape_paterno }} {{ $pastor->ape_materno }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                                <button type="submit" class="btn btn-primary">CONFIRMAR</button>
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
        <!--bootstrap select-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
  

@endpush