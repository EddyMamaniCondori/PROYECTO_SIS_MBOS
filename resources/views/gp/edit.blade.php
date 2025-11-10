@extends('template')


@section('title', 'Crear')

@push('css')
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    


@endpush

@section('content')
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Editar Grupo Pequeño</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('grupo.index')}}">Grupos Pequeños</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Crear Grupos Pequeños</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('grupo.update', $grupo->id_grupo) }}" method="POST">
                @csrf
                @method('PUT')
                 <h5 class="mb-0"><strong>Datos Generales</strong></h5>
                    <hr>
                <div class="row g-3">
                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre del gp: <span class="text-danger">*</span>  </label>
                        <input type="text" name="nombre" id="nombre" 
                            class="form-control" value="{{ old('nombre', $grupo->nombre) }}">
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Checkbox-->
                    <h5 class="mb-0"><strong>Asignar a un Administrativo</strong></h5>
                    <hr>
                    <!-- Select de distritos (oculto al inicio) -->
                    <div class="col-md-6 mt-3" >
                        <label for="administrativo_id" class="form-label">Pastor Administrativo:</label>
                    
                        <select data-size="9" title="-- Seleccione un grupo --" data-live-search="true"  name="administrativo_id" id="administrativo_id" class="form-control selectpicker show-tick">
                            @foreach($administrativos as $administrativo)
                                <option value="{{ $administrativo->id_persona }}" 
                                    {{ old('administrativo_id', $grupo->administrativo_id) == $administrativo->id_persona ? 'selected' : '' }}>
                                    {{ $administrativo->nombre }} {{ $administrativo->ape_paterno }} {{ $administrativo->ape_materno }}
                                </option>
                            @endforeach
                        </select>
                        @error('administrativo_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Botón Guardar -->
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('grupo.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form> 
            </div>
          </div>
          <!--end::Container-->
        </div>
        @endsection



@push('js')



<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
  



@endpush