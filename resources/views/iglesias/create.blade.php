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
              <div class="col-sm-6"><h3 class="mb-0">Crear Iglesia</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('iglesias.index')}}">Iglesias</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Crear Iglesias</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('iglesias.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre de la Iglesia:</label>
                        <input type="text" name="nombre" id="nombre" 
                            class="form-control" value="{{ old('nombre') }}">
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Feligresía -->
                    <div class="col-md-6">
                        <label for="feligresia" class="form-label">Cantidad de feligreses:</label>
                        <input type="number" name="feligresia" id="feligresia" 
                            class="form-control" value="{{ old('feligresia', 0) }}">
                        @error('feligresia')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Feligrasía Asistente -->
                    <div class="col-md-6">
                        <label for="feligrasia_asistente" class="form-label">Asistencia promedio:</label>
                        <input type="number" name="feligrasia_asistente" id="feligrasia_asistente" 
                            class="form-control" value="{{ old('feligrasia_asistente', 0) }}">
                        @error('feligrasia_asistente')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Ciudad -->
                    <div class="col-md-6">
                        <label for="ciudad" class="form-label">Ciudad:</label>
                        <input type="text" name="ciudad" id="ciudad" 
                            class="form-control" value="{{ old('ciudad') }}">
                        @error('ciudad')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Barrio -->
                    <div class="col-md-6">
                        <label for="zona" class="form-label">zona:</label>
                        <input type="text" name="zona" id="zona" 
                            class="form-control" value="{{ old('zona') }}">
                        @error('zona')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- País -->
                    <div class="col-md-6">
                        <label for="calle" class="form-label">calle:</label>
                        <input type="text" name="calle" id="calle" 
                            class="form-control" value="{{ old('calle') }}">
                        @error('calle')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- País -->
                    <div class="col-md-6">
                        <label for="nro" class="form-label">nro:</label>
                        <input type="text" name="nro" id="nro" 
                            class="form-control" value="{{ old('nro') }}">
                        @error('nro')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Checkbox: ¿Tiene distrito? -->
                    <div class="col-md-12 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="tieneDistrito">
                            <label class="form-check-label" for="tieneDistrito">
                                ¿Asignar un distrito a esta iglesia?
                            </label>
                        </div>
                    </div>
                    <!-- Select de distritos (oculto al inicio) -->
                    <div class="col-md-6 mt-3" id="selectDistrito" style="display:none;">
                        <label for="id_distrito" class="form-label">Distrito:</label>
                        <select name="distrito_id" id="distrito_id" class="form-select">
                            <option value="">-- Seleccione un distrito --</option>
                            @foreach($distritos as $distrito)
                                <option value="{{ $distrito->id_distrito }}" 
                                    {{ old('distrito_id') == $distrito->id_distrito ? 'selected' : '' }}>
                                    {{ $distrito->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('distrito_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Botón Guardar -->
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('iglesias.index') }}" class="btn btn-secondary">Cancelar</a>
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