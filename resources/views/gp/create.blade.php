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
              <div class="col-sm-6"><h3 class="mb-0">Crear Grupo Pequeño</h3></div>
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
            <form action="{{ route('grupo.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre del gp:</label>
                        <input type="text" name="nombre" id="nombre" 
                            class="form-control" value="{{ old('nombre') }}">
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                     <input type="hidden" name="nro_distritos" id="nro_distritos" class="form-control" value="0">

                    
                    <!-- Checkbox-->
                    <div class="col-md-12 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="tieneDistrito">
                            <label class="form-check-label" for="tieneDistrito">
                                ¿Desea asignar un Pator Consejeroa este grupo?
                            </label>
                        </div>
                    </div>
                    <!-- Select de distritos (oculto al inicio) -->
                    <div class="col-md-6 mt-3" id="selectDistrito" style="display:none;">
                        <label for="administrativo_id" class="form-label">Distrito:</label>
                        <select name="administrativo_id" id="administrativo_id" class="form-select">
                            <option value="">-- Seleccione un administrativo --</option>
                            @foreach($administrativos as $administrativo)
                                <option value="{{ $administrativo->id_persona }}" 
                                    {{ old('administrativo_id') == $administrativo->id_persona ? 'selected' : '' }}>
                                    {{ $administrativo->persona->nombre }} {{ $administrativo->persona->ape_paterno }} {{ $administrativo->persona->ape_materno }}
                                </option>
                            @endforeach
                        </select>
                        @error('administrativo_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Botón Guardar -->
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary">Guardar</button>
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


<script>
    document.getElementById('tieneDistrito').addEventListener('change', function() {
        const selectDistrito = document.getElementById('selectDistrito');
        if (this.checked) {
            selectDistrito.style.display = 'block';
        } else {
            selectDistrito.style.display = 'none';
            document.getElementById('id_distrito').value = ''; // se limpia el valor
        }
    });


</script>



@endpush