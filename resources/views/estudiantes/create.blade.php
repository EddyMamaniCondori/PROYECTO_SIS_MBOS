@extends('template')


@section('title', 'Crear')

@push('css')
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Crear Estudiantes Biblicos</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('estudiantes.index')}}">Estudiantes Biblicos</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Crear Estudiantes Biblicos</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('estudiantes.store')}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                    @csrf
                
                    <div class="row g-3">
                        <!-- Nombre -->
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}">
                            @error('nombre')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Apellido Paterno -->
                        <div class="col-md-6">
                            <label for="ape_paterno" class="form-label">Apellido Paterno:</label>
                            <input type="text" name="ape_paterno" id="ape_paterno" class="form-control" value="{{ old('ape_paterno') }}">
                            @error('ape_paterno')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Apellido Materno -->
                        <div class="col-md-6">
                            <label for="ape_materno" class="form-label">Apellido Materno:</label>
                            <input type="text" name="ape_materno" id="ape_materno" class="form-control" value="{{ old('ape_materno') }}">
                            @error('ape_materno')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Sexo -->
                        <div class="col-md-6">
                            <label for="sexo" class="form-label">Sexo:</label>
                            <select name="sexo" id="sexo" class="form-select">
                                <option value="">-- Seleccione --</option>
                                <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                            @error('sexo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Opción de contacto -->
                        <div class="col-md-6">
                            <label for="opcion_contacto" class="form-label">Opción de contacto:</label>
                            <input type="text" name="opcion_contacto" id="opcion_contacto" class="form-control" value="{{ old('opcion_contacto') }}">
                            @error('opcion_contacto')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Edad -->
                        <div class="col-md-6">
                            <label for="edad" class="form-label">Edad:</label>
                            <input type="number" name="edad" id="edad" class="form-control" value="{{ old('edad') }}">
                            @error('edad')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Celular -->
                        <div class="col-md-6">
                            <label for="celular" class="form-label">Celular:</label>
                            <input type="text" name="celular" id="celular" class="form-control" value="{{ old('celular') }}">
                            @error('celular')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Estado civil -->
                        <div class="col-md-6">
                            <label for="estado_civil" class="form-label">Estado Civil:</label>
                            <input type="text" name="estado_civil" id="estado_civil" class="form-control" value="{{ old('estado_civil') }}">
                            @error('estado_civil')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- CI -->
                        <div class="col-md-6">
                            <label for="ci" class="form-label">Cédula de Identidad (CI):</label>
                            <input type="text" name="ci" id="ci" class="form-control" value="{{ old('ci') }}">
                            @error('ci')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Curso bíblico usado -->
                        <div class="col-md-6">
                            <label for="curso_biblico_usado" class="form-label">Curso Bíblico Usado:</label>
                            <input type="text" name="curso_biblico_usado" id="curso_biblico_usado" class="form-control" value="{{ old('curso_biblico_usado') }}">
                            @error('curso_biblico_usado')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Bautizado -->
                        <div class="col-md-6">
                            <label for="bautizado" class="form-label">¿Bautizado?:</label>
                            <select name="bautizado" id="bautizado" class="form-select">
                                <option value="0" {{ old('bautizado') == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('bautizado') == 1 ? 'selected' : '' }}>Sí</option>
                            </select>
                            @error('bautizado')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mt-3" id="selectIglesia">
                            <label for="iglesia_id" class="form-label">Iglesia:</label>
                            <select name="iglesia_id" id="iglesia_id" class="form-select">
                                <option value="">-- Seleccione una iglesia --</option>
                                @foreach($iglesias as $iglesia)
                                    <option value="{{ $iglesia->id_iglesia }}" 
                                        {{ old('iglesia_id') == $iglesia->id_iglesia ? 'selected' : '' }}>
                                        {{ $iglesia->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            @error('iglesia_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    
                        <div>
                            <button type="submit" class="btn btn-primary"> Guardar </button>
                        </div>
                    </div>

            </form>
            </div>
          </div>
          <!--end::Container-->
        </div>
        @endsection



@push('js')




@endpush