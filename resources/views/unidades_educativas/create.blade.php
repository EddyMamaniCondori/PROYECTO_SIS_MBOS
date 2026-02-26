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
            <h5 class="mb-0"><strong>Datos Generales</strong></h5>
                    <hr>
                <div class="row g-3">
                    <div class="col-md-6"> <!--ocupa la mitad del espacio-->
                        <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span> </label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{@old('nombre')}}"> <!--name= debe tener mismo valor de la base de datos-->
                        @error('nombre')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>

                    <!-- Select de distritos (oculto al inicio) -->
                   <div class="col-md-6 mt-3" >
                        <label for="id_pastor" class="form-label">Pastor:</label> <br>
                      
                      <select data-size="9" title="-- Seleccione un pastor --" data-live-search="true" name="id_pastor" id="id_pastor" class="form-control selectpicker show-tick" >
                          @foreach($pastores as $pastor)
                              <option value="{{ $pastor->id_pastor }}" 
                                  {{ old('id_pastor') == $pastor->id_pastor ? 'selected' : '' }}>
                                  {{ $pastor->nombre }} {{ $pastor->ape_paterno }} {{ $pastor->ape_materno }}
                              </option>
                          @endforeach
                      </select>
                      @error('id_pastor')
                          <small class="text-danger">{{ $message }}</small>
                      @enderror
                  </div>

                  <h5 class="mb-0"><strong>Asignar a un grupo</strong></h5>
                    <hr>
                <span class="text-danger">En caso de no pertenecer a un grupo no llene este espacio</span>

                <!-- Select de grupos (oculto al inicio) -->
                <div class="col-md-6 mt-3" >
                    <label for="id_grupo" class="form-label">Grupo:</label>
                    <select data-size="9" title="-- Seleccione un grupo --" data-live-search="true" name="id_grupo" id="id_grupo" class="form-control selectpicker show-tick" >
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
                        <a href="{{route('distritos.index')}}"><button type="button" class="btn btn-danger"> Cancelar </button></a>
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