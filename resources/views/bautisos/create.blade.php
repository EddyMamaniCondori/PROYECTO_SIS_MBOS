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
              <div class="col-sm-6"><h3 class="mb-0">Crear Bautiso</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('bautisos.index')}}">Bautisos</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Crear Bautisos</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('bautisos.store')}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                    @csrf
                
                    <div class="row g-3">

                        <div class="col-md-6"> <!--ocupa la mitad del espacio-->

                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{@old('nombre')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('nombre')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="ape_paterno" class="form-label">Apellido Paterno:</label>
                            <input type="text" name="ape_paterno" id="ape_paterno" class="form-control" value="{{@old('ape_paterno')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('ape_paterno')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="ape_materno" class="form-label">Apellido Materno:</label>
                            <input type="text" name="ape_materno" id="ape_materno" class="form-control" value="{{@old('ape_materno')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('ape_materno') 
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="sexo" class="form-label">sexo:</label>
                            <input type="text" name="sexo" id="sexo" class="form-control" value="{{@old('sexo')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('sexo') 
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <!--fecha-->
                        <div class="col-md-6">
                            <label for="fecha_nacimiento" class="form-label">Fecha nacimiento:</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" value="{{@old('fecha_nacimiento')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('fecha_nacimiento')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <!--fecha bautizo-->
                        <div class="col-md-6">
                            <label for="fecha_bautizo" class="form-label">Fecha de Bautizo:</label>
                            <input type="date" name="fecha_bautizo" id="fecha_bautizo" class="form-control" value="{{@old('fecha_bautizo')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('fecha_bautizo')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="estudiante_biblico" class="form-label">¿Es estudiante bíblico?</label>
                            <select name="estudiante_biblico" id="estudiante_biblico" class="form-select">
                                <option value="1" {{ old('estudiante_biblico') == '1' ? 'selected' : '' }}>Sí</option>
                                <option value="0" {{ old('estudiante_biblico') == '0' ? 'selected' : '' }}>No</option>
                            </select>

                            @error('estudiante_biblico')
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