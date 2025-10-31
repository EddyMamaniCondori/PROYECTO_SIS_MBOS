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
                <div class="col-sm-6"><h3 class="mb-0">Asignacion Anual Pr. Distritales {{ $anio}}
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{route('distritos.index')}}">Distritos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Asignaciones Distritales</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div >
                    <a href="{{route('distritos.finalizar_asignaciones', $anio)}}"><button type="button" class="btn btn-primary">
                        &nbsp; Finalizar Asignaciones  {{ $anio}} </button></a>
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
                                Tabla de Distritos Asignables
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>pastor distrital</th>
                                            <th style="display:none;">sw_estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distritos as $distrito)
                                                <tr style="{{ $distrito->sw_estado ? 'background-color: #5DA87D;' : '' }}">
                                                    <td>
                                                    {{$distrito->id_distrito_asignaciones}} &nbsp {{$distrito->nombre}}
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
                                                    <td style="display:none;">
                                                        {{ $distrito->sw_estado ? 'true' : 'false' }}
                                                    </td>
                                                    <td> 
                                                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                            @if (!is_null($distrito->id_pastor))
                                                                <a href="{{ route('distritos.mantener', $distrito->id_distrito_asignaciones) }}" class="btn btn-success">
                                                                    Mantener
                                                                </a>
                                                            @endif
                                                            <!-- Liberar asignación -->
                                                            @if (!is_null($distrito->id_pastor))
                                                                <a href="{{ route('distritos.liberar_anual', $distrito->id_distrito_asignaciones) }}" class="btn btn-warning">
                                                                    Liberar Pastor
                                                                </a>
                                                            @endif
                                                            
                                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop-{{$distrito->id_distrito_asignaciones}}">
                                                                Cambiar
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                         
                                                <!-- Modal -->
                                                    <div class="modal fade" id="staticBackdrop-{{$distrito->id_distrito_asignaciones}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel">DISTRITO - {{$distrito->nombre}}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('distritos.cambiaranual', $distrito->id_distrito_asignaciones) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                Escoge un pastor para el distrito..
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
                                            <th style="display:none;">sw_estado</th>
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
            // Inicializar DataTable y guardar la instancia en "table"
            var table = $('#example').DataTable({
                scrollX: true,
                pageLength: 100,
                language: {
                    search: "Buscar:",
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
                },
                columnDefs: [
                    {
                        targets: [2], // índice de la columna oculta sw_estado
                        visible: false,
                        orderDataType: "boolean-last"
                    }
                ]
            });

            // Función de orden personalizada para booleanos
            $.fn.dataTable.ext.order['boolean-last'] = function(settings, colIndex) {
                return table.column(colIndex, {order:'index'}).data().map(function(value) {
                    return value === true || value === 'true' ? 1 : 0; // false primero, true al final
                });
            };

            // Aplicar orden inicial: primero por sw_estado (columna 2), luego por pastor (columna 1)
            table.order([[2, 'asc'], [1, 'asc']]).draw();
        });
    </script>

    <!--bootstrap select-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
  
@endpush