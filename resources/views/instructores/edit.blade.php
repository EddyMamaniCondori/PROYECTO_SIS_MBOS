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
              <div class="col-sm-6"><h3 class="mb-0">Editar Instructor Biblico</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('instructores.index')}}">Instructor Biblicos</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Editar Instructor Biblico</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('instructores.update', $instructor->id_instructor)}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                    @csrf
                    @method('PUT') 
                    <div class="row g-3">
                        <h5 class="mb-0"><strong>Datos Generales</strong></h5>
                        <hr>
                        <!-- Nombre -->
                        <div class="col-md-4">
                            <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span> </label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $instructor->nombre) }}">
                            @error('nombre')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Apellido Paterno -->
                        <div class="col-md-4">
                            <label for="ape_paterno" class="form-label">Apellido Paterno: <span class="text-danger">*</span></label>
                            <input type="text" name="ape_paterno" id="ape_paterno" class="form-control" value="{{ old('ape_paterno', $instructor->ape_paterno) }}">
                            @error('ape_paterno')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Apellido Materno -->
                        <div class="col-md-4">
                            <label for="ape_materno" class="form-label">Apellido Materno:</label>
                            <input type="text" name="ape_materno" id="ape_materno" class="form-control" value="{{ old('ape_materno', $instructor->ape_materno) }}">
                            @error('ape_materno')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Sexo -->
                        <div class="col-md-3">
                            <label for="sexo" class="form-label">Sexo: <span class="text-danger">*</span> </label>
                            <select name="sexo" id="sexo" class="form-select">
                                <option value="">-- Seleccione --</option>
                                <option value="M" {{ old('sexo', $instructor->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo', $instructor->sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                            @error('sexo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Opción de contacto -->
                        <div class="col-md-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento: <span class="text-danger">*</span> </label>
                            <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', $instructor->fecha_nacimiento) }}">
                            @error('fecha_nacimiento')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <h5 class="mb-0"><strong>Datos de Contacto</strong></h5>
                        <hr>
                        <!-- Celular -->
                        <div class="col-md-3">
                            <label for="celular" class="form-label">Celular:<span class="text-danger">*</span></label>
                            <input type="text" name="celular" id="celular" class="form-control" value="{{ old('celular', $instructor->celular) }}">
                            @error('celular')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mt-3" id="selectIglesia">
                            <label for="iglesia_id" class="form-label">Iglesia: <span class="text-danger">*</span></label>
                            <select data-size="9" title="-- Seleccione un Iglesia --" data-live-search="true" name="id_iglesia" id="id_iglesia" class="form-control selectpicker show-tick" >                         
                                <option value="">-- Seleccione una iglesia --</option>
                                @foreach($iglesias as $iglesia)
                                    <option value="{{ $iglesia->id_iglesia }}" 
                                        {{ old('id_iglesia', $instructor->id_iglesia) == $iglesia->id_iglesia ? 'selected' : '' }}>
                                        {{ $iglesia->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            @error('id_iglesia')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    
                        <div><a href="{{route('instructores.index')}}"><button type="button" class="btn btn-secondary"> <i class="bi bi-x"></i> Cancelar </button></a>
                            
                            <button type="submit" class="btn btn-primary"> <i class="bi bi-bookmark"></i> Guardar </button>
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