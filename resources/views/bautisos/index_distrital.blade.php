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
              <div class="col-sm-6"><h3 class="mb-0">Bautisos - {{$distrito->nombre}} - {{$anioActual}}</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('bautisos.index')}}"">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Bautisos Distrital</li>
                </ol>
              </div>
            </div>
            <div class="row">
                <div class="container-fluid">
                    <div class="card mb-4 p-4">
                        <form action="{{ route('bautisos.store')}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                        @csrf
                            <div class="row g-3">
                                 <input type="hidden" name="id_distrito" id="id_distrito" value="{{$distrito->id_distrito}}">
                                   
                                <h5 class="mb-0"><strong>Datos del Bautizo</strong></h5>
                                <div class="col-md-2">
                                    <label for="fecha_bautizo" class="form-label">Fecha de Bautizo: <span class="text-danger">*</span> </label>
                                    <input type="date" name="fecha_bautizo" class="form-control" value="{{ old('fecha_bautizo') }}">
                                    @error('fecha_bautizo')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- Bautizado -->
                                <div class="col-md-2">
                                    <label for="tipo" class="form-label">Tipo: <span class="text-danger">*</span> </label>
                                    <select name="tipo" id="tipo" class="form-select">
                                        <option value="bautizo" {{ old('tipo') == 'bautizo' ? 'selected' : '' }}>Bautizo</option>
                                        <option value="profesion de fe" {{ old('tipo') == 'profesion de fe' ? 'selected' : '' }}>Profesión de fe</option>
                                        <option value="rebautismo" {{ old('tipo') == 'rebautismo' ? 'selected' : '' }}>Rebautismo</option>
                                    </select>
                                    @error('tipo')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-3 mt-2" id="selectIglesia">
                                    <label for="id_iglesia" class="form-label">Iglesia: <span class="text-danger">*</span> </label>
                                    <select data-size="9" title="-- Seleccione un Iglesia --" data-live-search="true" name="id_iglesia" id="id_iglesia" class="form-control selectpicker show-tick" >                         
                                        <option value="">-- Seleccione una iglesia --</option>
                                        @foreach($iglesias as $iglesia)
                                            <option value="{{ $iglesia->id_iglesia }}" 
                                                {{ old('id_iglesia') == $iglesia->id_iglesia ? 'selected' : '' }}>
                                                {{ $iglesia->nombre  }}/{{ $iglesia->tipo  }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_iglesia')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!--AQUI EMPIEZA LOS DESAFIOS DE EVENTOS DISPONIBLES-->
                                <div class="col-md-2 mt-3" id="selectDesafioEvento">
                                    @if(isset($desafio_eventos) && $desafio_eventos->count() > 0)
                                        <label for="id_desafio_evento" class="form-label">
                                            Desafío Evento: <span class="text-danger">*</span>
                                        </label>
                                        <select data-size="9" 
                                                title="-- Seleccione un Desafío Evento --"
                                                data-live-search="true" 
                                                name="id_desafio_evento" 
                                                id="id_desafio_evento" 
                                                class="form-control selectpicker show-tick">
                                            <option value="null">-- Seleccione un desafío evento --</option>
                                            @foreach($desafio_eventos as $evento)
                                                <option value="{{ $evento->id_desafio_evento }}" 
                                                    {{ old('id_desafio_evento') == $evento->id_desafio_evento ? 'selected' : '' }}>
                                                    {{ $evento->nombre }} ({{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }} - 
                                                    {{ \Carbon\Carbon::parse($evento->fecha_final)->format('d/m/Y') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_desafio_evento')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    @else
                                        {{-- Si no hay eventos disponibles --}}
                                        <input type="hidden" name="id_desafio_evento" value="null">
                                        <div class="alert alert-warning mt-2">
                                            No hay desafíos de eventos disponibles en esta fecha.
                                        </div>
                                    @endif
                                </div>
                                <!--AQUI TERMINA LOS DESAFIOS DE EVENTOS DISPONIBLES-->
                                <div class="col-md-2">
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
                                Tabla de Bautisos
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Iglesia</th>
                                            <th>Tipo</th>
                                            <th>Tipo</th>
                                            <th>fecha de bautizo</th>
                                            <th>acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bautisos as $bautiso)
                                        <tr>
                                            <td>
                                            {{$bautiso->nombre_iglesia}} 
                                            </td>
                                            <td>
                                                {{$bautiso->tipo_iglesia}}
                                            </td>
                                            <td>
                                                {{$bautiso->tipo}}
                                            </td>
                                            <td>
                                                {{$bautiso->fecha_bautizo}}
                                            </td>
                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                <a href="{{ route('bautisos.edit', $bautiso->id_bautiso) }}" class="btn btn-warning">
                                                    <i class="bi bi-pencil-square"></i> Editar
                                                </a>
                                                <form action="{{ route('bautisos.destroy', ['bautiso' => $bautiso->id_bautiso]) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Iglesia</th>
                                            <th>Tipo</th>
                                            <th>Tipo</th>
                                            <th>fecha de bautizo</th>
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