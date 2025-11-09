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
              <div class="col-sm-6"><h3 class="mb-0">Editar Estudiant Biblico</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('estudiantes.index')}}">Estudiantes Biblicos</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Editar Estudiante Biblico</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('estudiantes.update', $estudiante->id_est)}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                    @csrf
                    @method('PUT') 
                    <div class="row g-3">
                        <h5 class="mb-0"><strong>Datos Generales</strong></h5>
                        <hr>
                        <!-- Nombre -->
                        <div class="col-md-4">
                            <label for="nombre" class="form-label">Nombre:  <span class="text-danger">*</span> </label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $estudiante->nombre) }}">
                            @error('nombre')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Apellido Paterno -->
                        <div class="col-md-4">
                            <label for="ape_paterno" class="form-label">Apellido Paterno: <span class="text-danger">*</span> </label>
                            <input type="text" name="ape_paterno" id="ape_paterno" class="form-control" value="{{ old('ape_paterno', $estudiante->ape_paterno) }}">
                            @error('ape_paterno')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Apellido Materno -->
                        <div class="col-md-4">
                            <label for="ape_materno" class="form-label">Apellido Materno:</label>
                            <input type="text" name="ape_materno" id="ape_materno" class="form-control" value="{{ old('ape_materno', $estudiante->ape_materno) }}">
                            @error('ape_materno')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Sexo -->
                        <div class="col-md-3">
                            <label for="sexo" class="form-label">Sexo: <span class="text-danger">*</span> </label>
                            <select name="sexo" id="sexo" class="form-select">
                                <option value="">-- Seleccione --</option>
                                <option value="M" {{ old('sexo', $estudiante->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo', $estudiante->sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                            @error('sexo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                                                <!-- CI -->
                        <div class="col-md-4">
                            <label for="ci" class="form-label">Cédula de Identidad:</label>
                            <input type="text" name="ci" id="ci" class="form-control" value="{{ old('ci', $estudiante->ci) }}">
                            @error('ci')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                                                <!-- Estado civil -->
                        <div class="col-md-3">
                            <label for="estado_civil" class="form-label">Estado Civil: <span class="text-danger">*</span></label>
                            <select name="estado_civil" id="estado_civil" class="form-control" required>
                                <option value="">-- Seleccione --</option>
                                <option value="SOLTERO" {{ old('estado_civil', $estudiante->estado_civil) == 'SOLTERO' ? 'selected' : '' }}>SOLTERO</option>
                                <option value="CASADO" {{ old('estado_civil', $estudiante->estado_civil) == 'CASADO' ? 'selected' : '' }}>CASADO</option>
                                <option value="DIVORCIADO" {{ old('estado_civil', $estudiante->estado_civil) == 'DIVORCIADO' ? 'selected' : '' }}>DIVORCIADO</option>
                            </select>
                            @error('estado_civil')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        <h5 class="mb-0"><strong>Datos de direccion y contacto </strong></h5>
                        <hr>
                        <!-- Opción de contacto -->
                        <div class="col-md-5">
                            <label for="opcion_contacto" class="form-label">Opción de contacto <span class="text-primary">(correo, telefono, etc.)</span> :</label>
                            <input type="text" name="opcion_contacto" id="opcion_contacto" class="form-control" value="{{ old('opcion_contacto', $estudiante->opcion_contacto) }}">
                            @error('opcion_contacto')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Edad -->
                        <div class="col-md-2">
                            <label for="edad" class="form-label">Edad:</label>
                            <input type="number" name="edad" id="edad" class="form-control" value="{{ old('edad', $estudiante->edad) }}" min="0">
                            @error('edad')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Celular -->
                        <div class="col-md-3">
                            <label for="celular" class="form-label">Celular: <span class="text-danger">*</span> </label>
                            <input type="text" name="celular" id="celular" class="form-control" value="{{ old('celular', $estudiante->celular) }}">
                            @error('celular')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        <h5 class="mb-0"><strong>Datos del curso biblico </strong></h5>
                        <hr>

                        <!-- Curso bíblico usado -->
                        <div class="col-md-6">
                            <label for="curso_biblico_usado" class="form-label">
                                Curso Bíblico Usado: <span class="text-danger">*</span>
                            </label>

                            <input list="lista_cursos" name="curso_biblico_usado" id="curso_biblico_usado"
                                class="form-control" required
                                value="{{ old('curso_biblico_usado', $estudiante->curso_biblico_usado) }}"
                                placeholder="Seleccione o escriba el curso...">

                            <datalist id="lista_cursos">
                                <option value="Fe de Jesús">
                                <option value="Yo creo">
                                <option value="Otros">
                            </datalist>

                            @error('curso_biblico_usado')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Bautizado -->
                        <div class="col-md-6">
                            <label for="bautizado" class="form-label">¿Bautizado?: <span class="text-danger">*</span> </label>
                            <select name="bautizado" id="bautizado" class="form-select">
                                <option value="0" {{ old('bautizado', $estudiante->bautizado) == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('bautizado', $estudiante->bautizado) == 1 ? 'selected' : '' }}>Sí</option>
                            </select>
                            @error('bautizado')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mt-3" id="selectIglesia">
                            <label for="id_iglesia" class="form-label">Iglesia: <span class="text-danger">*</span> </label>
                            <select data-size="9" title="-- Seleccione una iglesia --" data-live-search="true" name="id_iglesia" id="id_iglesia" class="form-control selectpicker show-tick" >                         
                                <option value="">-- Seleccione una iglesia --</option>
                                @foreach($iglesias as $iglesia)
                                    <option value="{{ $iglesia->id_iglesia }}" 
                                        {{ (old('id_iglesia', $estudiante->id_iglesia ?? '') == $iglesia->id_iglesia) ? 'selected' : '' }}>
                                        {{ $iglesia->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            @error('id_iglesia')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    
                        <div>
                            <a href="{{route('estudiantes.index')}}"><button type="button" class="btn btn-secondary"> <i class="bi bi-x"></i> Cancelar </button></a>
                            <button type="submit" class="btn btn-primary">  <i class="bi bi-bookmark"></i> Guardar </button>
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