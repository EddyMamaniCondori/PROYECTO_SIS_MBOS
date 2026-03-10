@extends('template')


@section('title', 'Tablas')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--boottrap select-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
@endpush

@section('content')
    <x-alerts/>
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6"><h3 class="mb-0">Supervisores de Remesas de Distritos - 
                                                    @foreach ($anios as $a)
                                                        {{ $a->año }}
                                                    @endforeach

                                            </h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Responsables de Distritos</li>
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
                                            <th>pastor distrital</th>
                                            <th>Responsable</th>
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
                                                @if (!is_null($distrito->id_responsable_remesa))
                                                    {{ $distrito->nombre_responsable }}
                                                    &nbsp;{{ $distrito->ape_paterno_responsable }}
                                                    &nbsp;{{ $distrito->ape_materno_responsable }}
                                                @else
                                                    <span class="badge bg-warning mt-1 fs-6">
                                                        <i class="bi bi-exclamation-triangle-fill"></i> No Tiene Responsable Financiero
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
                                                @if ($distrito->id_responsable_remesa)
                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModalEdit-{{$distrito->id_distrito}}"> <i class="bi bi-pencil-square"></i>&nbsp;Cambiar</button>
                                                @else
                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModalEdit-{{$distrito->id_distrito}}"> <i class="bi bi-person-fill-add"></i>&nbsp;Asignar</button>
                                                @endif
                                            
                                            </td>
                                        </tr>
                                            <!-- Modal  Actualizacion-->
                                            <div class="modal fade" id="confirmModalEdit-{{$distrito->id_distrito}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Asignar un Responsable</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <form action="{{ route('remesa.asignar-responsable') }}" method="POST">
                                                            @csrf
                                                            @method('POST')
                                                            <div class="modal-body">
                                                                Escoge un Responsable para el distrito...<br>
                                                                <label for="id_personal" class="form-label">Pastores Disponibles:</label>
                                                                <select data-size="9" title="-- Seleccione un pastor --" data-live-search="true" name="id_persona" id="id_persona" class="form-control selectpicker show-tick" required>
                                                                    @foreach($personal as $per)
                                                                        <option value="{{ $per->id_persona }}">
                                                                           {{ $per->id_persona }}- {{ $per->nombre }} {{ $per->ape_paterno }} {{ $per->ape_materno }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <input type="hidden" name="id_distrito" id="id_distrito" value="{{$distrito->id_distrito}}">

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
                                            <th>pastor distrital</th>
                                            <th>Responsable</th>
                                            <th>grupo pequeño</th>
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush