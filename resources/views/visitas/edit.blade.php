@extends('template')


@section('title', 'Crear')

@push('css')
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--bootstrap select-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">



@endpush

@section('content')
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Editar Visita</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('visitas.index')}}">Visitas</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Editar Visitas</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('visitas.update', $visita->id_visita)}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <h5 class="mb-0"><strong>Datos Generales</strong></h5>
                        <hr>
                        <div class="col-md-4"> <!--ocupa la mitad del espacio-->

                            <label for="nombre_visitado" class="form-label">Nombre persona visitada:<span class="text-danger">*</span></label>
                            <input type="text" name="nombre_visitado" id="nombre_visitado" class="form-control" value="{{@old('nombre_visitado', $visita->nombre_visitado)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('nombre_visitado')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>  
                        <!-- Cantidad de presentes -->
                        <div class="col-md-2">
                            <label for="cant_present" class="form-label">Cantidad de Presentes:<span class="text-danger">*</span></label>
                            <input type="number" name="cant_present" id="cant_present" class="form-control" value="{{ old('cant_present', $visita->cant_present) }}">
                            @error('cant_present')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                       <!-- Fecha de la visita -->
                        <div class="col-md-3">
                            <label for="fecha_visita" class="form-label">Fecha de Visita:<span class="text-danger">*</span></label>
                            <input type="date" name="fecha_visita" id="fecha_visita" class="form-control" 
                                value="{{ old('fecha_visita', $visita->fecha_visita) }}">
                            @error('fecha_visita')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Hora -->
                        <div class="col-md-2">
                            <label for="hora" class="form-label">Hora:</label>
                            <input type="time" name="hora" id="hora" class="form-control" step="1" value="{{ old('hora', $visita->hora) }}">

                            @error('hora')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="motivo" class="form-label">Motivo: <span class="text-danger">*</span></label>
                            <select name="motivo" id="motivo" class="form-control" required>
                                <option value="">-- Seleccione --</option>
                                <option value="Pastoral" {{ old('motivo', $visita->motivo) == 'Pastoral' ? 'selected' : '' }}>Pastoral</option>
                                <option value="Evangelismo" {{ old('motivo', $visita->motivo) == 'Evangelismo' ? 'selected' : '' }}>Evangelismo</option>
                                <option value="Mayordomia" {{ old('motivo', $visita->motivo) == 'Mayordomia' ? 'selected' : '' }}>Mayordomia</option>
                                <option value="Coordinacion" {{ old('motivo', $visita->motivo) == 'Coordinacion' ? 'selected' : '' }}>Coordinacion</option>
                                <option value="otros" {{ old('motivo', $visita->motivo) == 'otros' ? 'selected' : '' }}>Otros</option>
                            </select>
                            @error('motivo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        <h5 class="mb-0"><strong>Datos de Direccion y Contacto</strong></h5>
                        <hr>
                        <!-- Teléfono -->
                        <div class="col-md-3">
                            <label for="telefono" class="form-label">Teléfono: <span class="text-danger">*</span> </label>
                            <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $visita->telefono) }}">
                            @error('telefono')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Descripción del lugar -->
                        <div class="col-md-5">
                            <label for="descripcion_lugar" class="form-label">Dirección o descripción del lugar: <span class="text-danger">*</span> </label>
                            <input type="text" name="descripcion_lugar" id="descripcion_lugar" class="form-control" value="{{ old('descripcion_lugar', $visita->descripcion_lugar) }}">
                            @error('descripcion_lugar')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mt-3" id="selectIglesia">
                            <label for="id_iglesia" class="form-label">Iglesia: <span class="text-danger">*</span> </label>
                            <select data-size="9" title="-- Seleccione un Iglesia --" data-live-search="true" name="id_iglesia" id="id_iglesia" class="form-control selectpicker show-tick" >                         
                                <option value="">-- Seleccione una iglesia --</option>
                                @foreach($iglesias as $iglesia)
                                    <option value="{{ $iglesia->id_iglesia }}" 
                                        {{ old('id_iglesia', $visita->id_iglesia) == $iglesia->id_iglesia ? 'selected' : '' }}>
                                        {{ $iglesia->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            @error('id_iglesia')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    
                        <div>
                            <a href="{{route('visitas.index')}}"><button type="button" class="btn btn-secondary"> <i class="bi bi-x"></i> Cancelar </button></a>
                            <button type="submit" class="btn btn-primary">  <i class="bi bi-bookmark"></i> Actualizar </button>
                        </div>
                    </div>

            </form>
            </div>
          </div>
          <!--end::Container-->
        </div>
        @endsection



@push('js')
        <!--bootstrap select-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush