@extends('template')


@section('title', 'Bautisos')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!--bootstrap select-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

@endpush

@section('content')

<x-alerts/>

        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Bautismos - {{$unidad_educativas->nombre}} - {{$anioActual}}</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('bautisos.index')}}">Bautismos</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Bautismos Capellanes</li>
                </ol>
              </div>
            </div>
            <div class="row">
                <div class="container-fluid">
                    <div class="card mb-4 p-4">
                        <form action="{{ route('bautizos_cape.store')}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                        @csrf
                            <div class="row g-3">
                                 <input type="hidden" name="id_ue" id="id_ue" value="{{$unidad_educativas->id_ue}}">
                                   
                                <h5 class="mb-0"><strong>Datos del Bautismos</strong></h5>
                                <div class="col-md-3">
                                    <label for="fecha_bautizo" class="form-label">Fecha de Bautismo: <span class="text-danger">*</span> </label>
                                    <input type="date" name="fecha_bautizo" class="form-control" value="{{ old('fecha_bautizo', now()->format('Y-m-d')) }}">
                                    @error('fecha_bautizo')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                
                                <!-- Bautizado -->
                                <div class="col-md-2 ">
                                    <label for="cant_bautizo" class="form-label">Bautismos: <span class="text-danger">*</span></label>
                                    <input type="number" 
                                        name="cant_bautizo" 
                                        id="cant_bautizo" 
                                        class="form-control" 
                                        value="{{ old('cant_bautizo', 0) }}" 
                                        min="0" 
                                        required>
                                    @error('cant_bautizo')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- Profesion de fe -->
                                <div class="col-md-2 ">
                                    <label for="cant_profesion" class="form-label">Profesión de fe: <span class="text-danger">*</span></label>
                                    <input type="number" 
                                        name="cant_profesion" 
                                        id="cant_profesion" 
                                        class="form-control" 
                                        value="{{ old('cant_profesion', 0) }}" 
                                        min="0" 
                                        required>
                                    @error('cant_profesion')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- rebautismos -->
                                <div class="col-md-2 ">
                                    <label for="cant_rebautismo" class="form-label">Rebautismo: <span class="text-danger">*</span></label>
                                    <input type="number" 
                                        name="cant_rebautismo" 
                                        id="cant_rebautismo" 
                                        class="form-control" 
                                        value="{{ old('cant_rebautismo', 0) }}" 
                                        min="0" 
                                        required>
                                    @error('cant_rebautismo')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                
                                <!--AQUI TERMINA LOS DESAFIOS DE EVENTOS DISPONIBLES-->
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-success">  <i class="bi bi-bookmark"></i> Guardar </button>

                                    <a href="{{ route('bautisos.index') }}" class="btn btn-secondary"> <i class="bi bi-arrow-left"></i> Volver</a>
                                </div>
                            </div>
                            
                        </form>
                    </div>
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
                                Tabla de Bautismos
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Tipo</th>
                                            <th>Fecha de bautismo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bautizos_capellanes as $bautiso)
                                        <tr>
                                            <td>
                                            {{$bautiso->id_bautiso}} 
                                            </td>
                                            <td>
                                                {{$bautiso->tipo}}
                                            </td>
                                            <td>
                                                {{$bautiso->fecha_bautizo}}
                                            </td>
                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#confirmModalEdit-{{$bautiso->id_bautiso}}"> <i class="bi bi-pencil-square"></i>&nbsp;Editar</button>
                                                    <form action="{{ route('bautisos_cape.destroy', ['id' => $bautiso->id_bautiso]) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger"> <i class="bi bi-trash3"></i>&nbsp;Eliminar</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Modal  Actualizacion-->
                                            <div class="modal fade" id="confirmModalEdit-{{$bautiso->id_bautiso}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Bautiso</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <form action="{{ route('bautizos_cape.update', $bautiso->id_bautiso) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="id_ue" value="{{$unidad_educativas->id_ue}}">
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="tipo" class="form-label">Tipo de Bautizo</label>
                                                                    <select class="form-select" id="tipo" name="tipo" required>
                                                                        <option value="bautizo" {{ $bautiso->tipo == 'bautizo' ? 'selected' : '' }}>Bautizo</option>
                                                                        <option value="profesion de fe" {{ $bautiso->tipo == 'profesion de fe' ? 'selected' : '' }}>Profesión de fe</option>
                                                                        <option value="rebautismo" {{ $bautiso->tipo == 'rebautismo' ? 'selected' : '' }}>Rebautismo</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="nombre" class="form-label">Fecha de bautismo:</label>
                                                                    <input type="date" class="form-control" id="fecha_bautizo" name="fecha_bautizo" value="{{$bautiso->fecha_bautizo}}" required>
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
                                            <th>Codigo</th>
                                            <th>Tipo</th>
                                            <th>Fecha de bautismo</th>
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
            ordering: false, 
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