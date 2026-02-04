@extends('template')


@section('title', 'Editar')

@push('css')
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Editar Pastor</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('pastores.index')}}">Pastores</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Editar Pastores</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('pastores.update', $pastor->id_persona)}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <h5 class="mb-0"><strong>Datos Generales</strong></h5>
                        <hr>

                        <div class="col-md-6"> <!--ocupa la mitad del espacio-->

                            <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{@old('nombre', $pastor->nombre)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('nombre')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="ape_paterno" class="form-label">Apellido Paterno: <span class="text-danger">*</span></label>
                            <input type="text" name="ape_paterno" id="ape_paterno" class="form-control" value="{{@old('ape_paterno', $pastor->ape_paterno)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('ape_paterno')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="ape_materno" class="form-label">Apellido Materno:</label>
                            <input type="text" name="ape_materno" id="ape_materno" class="form-control" value="{{@old('ape_materno', $pastor->ape_materno)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('ape_materno') 
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        
                        
                        <!--fecha-->
                        <div class="col-md-4">
                            <label for="fecha_nac" class="form-label">Fecha nacimiento: <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_nac" id="fecha_nac" class="form-control" value="{{@old('fecha_nac',  $pastor->fecha_nac)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('fecha_nac')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <!--celular-->
                        <div class="col-md-4">
                            <label for="ci" class="form-label">CI: <span class="text-danger"> * </span></label>
                            <input type="text" name="ci" id="ci" class="form-control" value="{{@old('ci',  $pastor->ci)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('ci')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <!--edad-->
                        <div class="col-md-4">
                            <label for="celular" class="form-label">Celular:</label> <span class="text-danger">*</span>
                            <input type="text" name="celular" id="celular" class="form-control" value="{{@old('celular',  $pastor->celular)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('celular')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <!--edad-->
                        <div class="col-md-4">
                            <label for="email" class="form-label">Correo electrónico:</label> <span class="text-danger">*</span>
                            <input type="text" name="email" id="email" class="form-control" value="{{@old('email',  $pastor->email)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('email')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    
                        <h5 class="mb-0"><strong>Datos de direccion</strong></h5>
                        <hr>
                        <div class="col-md-4">
                            <label for="ciudad" class="form-label">Ciudad:</label> <span class="text-danger">*</span>
                            <input type="text" name="ciudad" id="ciudad" class="form-control" value="{{@old('ciudad',  $pastor->ciudad)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('ciudad')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="zona" class="form-label">Zona:</label> <span class="text-danger">*</span>
                            <input type="text" name="zona" id="zona" class="form-control" value="{{@old('zona',  $pastor->zona)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('zona')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label for="calle" class="form-label">Calle:</label>
                            <input type="text" name="calle" id="calle" class="form-control" value="{{@old('calle',  $pastor->calle)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('calle')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label for="nro" class="form-label">Nro de puerta:</label>
                            <input type="text" name="nro" id="nro" class="form-control" value="{{@old('nro',  $pastor->nro)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('nro')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <h5 class="mb-0"><strong>Datos Pastorales</strong></h5>
                        <hr>
                        <!--domicilio-->
                        <div class="col-md-6">
                            <label for="fecha_contratacion" class="form-label">Fecha de contratacion:</label>
                            <input type="date" name="fecha_contratacion" id="fecha_contratacion" class="form-control" value="{{@old('fecha_contratacion',  $pastor->fecha_contratacion)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('fecha_contratacion')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_ordenacion" class="form-label">Fecha ordenacion:</label>
                            <input type="date" name="fecha_ordenacion" id="fecha_ordenacion" class="form-control" value="{{@old('fecha_ordenacion',  $pastor->fecha_ordenacion)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('fecha_ordenacion')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="cargo" class="form-label">Cargo:</label>
                            <input type="text" name="cargo" id="cargo" class="form-control" value="{{@old('cargo',  $pastor->cargo)}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('cargo')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <h5 class="mb-0"><strong>Cambio de Contraseña</strong></h5><hr>
                        <p class="text-primary">en caso de que no se quiera realizar el cambio de contraseña debe los espacios en blanco</p>
                        
                        <div class="col-md-6 position-relative">
                            <label for="password" class="form-label">Nueva Contraseña:</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                    <i class="bi bi-eye-slash" id="icon-password"></i>
                                </button>
                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 position-relative">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña:</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                    <i class="bi bi-eye-slash" id="icon-password_confirmation"></i>
                                </button>
                            </div>
                        </div>
                
                        <div>
                            <a href="{{route('pastores.index')}}"><button type="button" class="btn btn-danger"> Cancelar </button></a>
                            <button type="submit" class="btn btn-primary"> Actualizar </button>
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
document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = document.getElementById(`icon-${targetId}`);
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    });
});
</script>

@endpush