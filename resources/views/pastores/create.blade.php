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
              <div class="col-sm-6"><h3 class="mb-0">Crear Pastor</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('pastores.index')}}">Pastores</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Crear Pastores</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('pastores.store')}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
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
                        

                        <!--fecha-->
                        <div class="col-md-6">
                            <label for="fecha_nac" class="form-label">Fecha nacimiento:</label>
                            <input type="date" name="fecha_nac" id="fecha_nac" class="form-control" value="{{@old('fecha_nac')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('fecha_nac')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <!--celular-->
                        <div class="col-md-6">
                            <label for="ci" class="form-label">CI:</label>
                            <input type="text" name="ci" id="ci" class="form-control" value="{{@old('ci')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('ci')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <!--edad-->
                        <div class="col-md-6">
                            <label for="edad" class="form-label">Edad:</label>
                            <input type="text" name="edad" id="edad" class="form-control" value="{{@old('edad')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('edad')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <!--domicilio-->
                        
                        <!--domicilio-->
                        <div class="col-md-6">
                            <label for="fecha_ordenacion" class="form-label">Fecha ordenacion:</label>
                            <input type="date" name="fecha_ordenacion" id="ffecha_ordenacion" class="form-control" value="{{@old('fecha_ordenacion')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('fecha_ordenacion')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="cargo" class="form-label">Cargo:</label>
                            <input type="text" name="cargo" id="cargo" class="form-control" value="{{@old('cargo')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('cargo')
                                <small class="text-danger">{{$message}}</small>
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