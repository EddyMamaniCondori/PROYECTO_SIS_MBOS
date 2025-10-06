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
              <div class="col-sm-6"><h3 class="mb-0">Crear Distrito</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('distritos.index')}}">Distritos</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Crear Distritos</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('distritos.store')}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
            @csrf
                <div class="row g-3">
                    <div class="col-md-6"> <!--ocupa la mitad del espacio-->
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{@old('nombre')}}"> <!--name= debe tener mismo valor de la base de datos-->
                        @error('nombre')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <!-- Select de distritos (oculto al inicio) -->
                   <div class="col-md-6 mt-3">
                      <label for="id_pastor" class="form-label">Pastor:</label>
                      <select name="id_pastor" id="id_pastor" class="form-select">
                          <option value="">-- Seleccione un pastor --</option>
                          @foreach($pastores as $pastor)
                              <option value="{{ $pastor->id_pastor }}" 
                                  {{ old('id_pastor') == $pastor->id_pastor ? 'selected' : '' }}>
                                  {{ $pastor->persona->nombre }} {{ $pastor->persona->ape_paterno }} {{ $pastor->persona->ape_materno }}
                              </option>
                          @endforeach
                      </select>
                      @error('id_pastor')
                          <small class="text-danger">{{ $message }}</small>
                      @enderror
                  </div>


                  <div class="col-md-12 mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="tieneGrupo">
                        <label class="form-check-label" for="tieneGrupo">
                            ¿Asignar a un grupo pequeño existente? 
                        </label>
                    </div>
                </div>

                <!-- Select de grupos (oculto al inicio) -->
                <div class="col-md-6 mt-3" id="selectGrupo" style="display:none;">
                    <label for="id_grupo" class="form-label">Grupo:</label>
                    <select name="id_grupo" id="id_grupo" class="form-select">
                        <option value="">-- Seleccione un grupo pequeño --</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id_grupo }}" 
                                {{ old('id_grupo') == $grupo->id_grupo ? 'selected' : '' }}>
                                {{ $grupo->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_grupo')
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


<script>
    document.getElementById('tieneGrupo').addEventListener('change', function() {
        const selectDistrito = document.getElementById('selectGrupo');
        if (this.checked) {
            selectDistrito.style.display = 'block';
        } else {
            selectDistrito.style.display = 'none';
            document.getElementById('id_distrito').value = ''; // se limpia el valor
        }
    });


</script>



@endpush