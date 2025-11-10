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
              <div class="col-sm-6"><h3 class="mb-0">Crear Personal</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('personales.index')}}">Personales</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Crear Personales</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('personales.store')}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                    @csrf
                
                    <div class="row g-3">
                        <h5 class="mb-0"><strong>Datos Generales</strong></h5>
                        <hr>

                        <div class="col-md-6"> <!--ocupa la mitad del espacio-->

                            <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{@old('nombre')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('nombre')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="ape_paterno" class="form-label">Apellido Paterno: <span class="text-danger">*</span></label>
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
                        <div class="col-md-4">
                            <label for="fecha_nac" class="form-label">Fecha nacimiento: <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_nac" id="fecha_nac" class="form-control" value="{{@old('fecha_nac')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('fecha_nac')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <!--celular-->
                        <div class="col-md-4">
                            <label for="ci" class="form-label">CI: <span class="text-danger"> * </span></label>
                            <input type="text" name="ci" id="ci" class="form-control" value="{{@old('ci')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('ci')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <!--edad-->
                        <div class="col-md-4">
                            <label for="celular" class="form-label">Celular:</label> <span class="text-danger">*</span>
                            <input type="text" name="celular" id="celular" class="form-control" value="{{@old('celular')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('celular')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    
                        <h5 class="mb-0"><strong>Datos de direccion</strong></h5>
                        <hr>
                        <div class="col-md-4">
                            <label for="ciudad" class="form-label">Ciudad:</label> <span class="text-danger">*</span>
                            <input type="text" name="ciudad" id="ciudad" class="form-control" value="{{@old('ciudad')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('ciudad')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="zona" class="form-label">Zona:</label> <span class="text-danger">*</span>
                            <input type="text" name="zona" id="zona" class="form-control" value="{{@old('zona')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('zona')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label for="calle" class="form-label">Calle:</label>
                            <input type="text" name="calle" id="calle" class="form-control" value="{{@old('calle')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('calle')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label for="nro" class="form-label">Nro de puerta:</label>
                            <input type="text" name="nro" id="nro" class="form-control" value="{{@old('nro')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('nro')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <h5 class="mb-0"><strong>Datos Del Personal</strong></h5>
                        <hr>
                        <!--domicilio-->
                        <div class="col-md-6">
                            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso:</label>
                            <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control" value="{{@old('fecha_ingreso')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('fecha_ingreso')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div>
                            <a href="{{route('personales.index')}}"><button type="button" class="btn btn-secondary"> Cancelar </button></a>
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