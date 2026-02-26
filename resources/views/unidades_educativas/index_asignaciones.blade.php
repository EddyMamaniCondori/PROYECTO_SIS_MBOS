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

    <x-alerts/>
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Asignar Capellanes - {{ $anio}}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('asea.index') }}">Unidades Educativas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Asignacion de Capellanes</li>
                    </ol>
                </div>
            </div>
            <div class="row p-1">
                <div class="col-md-10">
                    <h2>
                    Unidad Educativa {{$colegio->nombre}}
                </h2>
                </div>
                <div class="col-md-2">
                <a href="{{route('asea.index')}}"><button type="button" class="btn btn-success"> <i class="bi bi-arrow-bar-left"></i> &nbsp Volver</button> </a><br>
               
                </div>
                
            </div>
            <div class="row p-3">
                <div class="card p-3">
                <form action="{{ route('asea.addcape')}}" method="post" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                @csrf
                <h5 class="mb-2 text-primary"><strong>Agregar Capellan</strong></h5>
                    <div class="row ">
                        
                        <!-- Select de distritos (oculto al inicio) -->
                        <div class="col-md-6" >
                            
                            <label for="id_pastor" class="form-label">Seleccione al Capellan:</label> <br>
                            <select data-size="9" title="-- Seleccione un pastor --" data-live-search="true" name="id_pastor" id="id_pastor" class="form-control selectpicker show-tick" required>
                                @foreach($pastores_libres as $pastor)
                                    <option value="{{ $pastor->id_persona }}" 
                                        {{ old('id_pastor') == $pastor->id_persona ? 'selected' : '' }}>
                                        {{ $pastor->nombre }} {{ $pastor->ape_paterno }} {{ $pastor->ape_materno }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pastor')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="hidden" name="id_ue"  id="id_ue" value="{{$colegio->id_ue}}">
                            <input type="hidden" name="anio"  id="anio" value="{{$anio}}">
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">  <i class="bi bi-person-plus-fill"></i> &nbsp;Añadir como Capellan </button>
                        </div>
                        
                    </div>
                    </form>
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
                                Tabla de Capellanes
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Nombre Completo</th>
                                            <th>celular</th>
                                            <th>fecha_asignacion</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($capellanes as $cape)
                                                <tr>
                                                    <td>
                                                      {{ $cape->nombre }}
                                                            &nbsp;{{ $cape->ape_paterno }}
                                                            &nbsp;{{ $cape->ape_materno }}
                                                    </td>
                                                    <td>
                                                        {{ $cape->celular }}
                                                    </td>
                                                    <td>
                                                        {{$cape->created_at}}
                                                    </td>
                                                    <td> 
                                                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                            @if (!is_null($cape->id_pastor))

                                                                <form action="{{ route('asea.liberarcape', [$colegio->id_ue, $cape->id_pastor, $anio]) }}" method="POST" style="display: inline;">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger">
                                                                        <i class="bi bi-person-dash"></i> &nbsp; Liberar Pastor
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Nombre Completo</th>
                                            <th>celular</th>
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