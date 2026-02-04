@extends('template')

@section('title', 'Editar Personal')

@push('css')
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <!-- CONTENIDO DEL Header-->
    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6"><h3 class="mb-0">Editar Personal</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item"><a href="{{ route('personales.index')}}">Personales</a></li>
              <li class="breadcrumb-item active" aria-current="page">Editar Personal</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!--contenido-->
    <div class="app-content">
      <div class="container-fluid">
        <form action="{{ route('personales.update', $persona->id_persona) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <h5 class="mb-0"><strong>Datos Generales</strong></h5>
                <hr>

                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $persona->nombre) }}">
                    @error('nombre')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="ape_paterno" class="form-label">Apellido Paterno: <span class="text-danger">*</span></label>
                    <input type="text" name="ape_paterno" id="ape_paterno" class="form-control" value="{{ old('ape_paterno', $persona->ape_paterno) }}">
                    @error('ape_paterno')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="ape_materno" class="form-label">Apellido Materno:</label>
                    <input type="text" name="ape_materno" id="ape_materno" class="form-control" value="{{ old('ape_materno', $persona->ape_materno) }}">
                    @error('ape_materno') 
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="fecha_nac" class="form-label">Fecha nacimiento: <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_nac" id="fecha_nac" class="form-control" value="{{ old('fecha_nac', $persona->fecha_nac) }}">
                    @error('fecha_nac')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="ci" class="form-label">CI: <span class="text-danger">*</span></label>
                    <input type="text" name="ci" id="ci" class="form-control" value="{{ old('ci', $persona->ci) }}">
                    @error('ci')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="celular" class="form-label">Celular:</label> <span class="text-danger">*</span>
                    <input type="text" name="celular" id="celular" class="form-control" value="{{ old('celular', $persona->celular) }}">
                    @error('celular')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="email" class="form-label">Correo electrónico: <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $persona->email) }}" required>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <h5 class="mb-0"><strong>Datos de dirección</strong></h5>
                <hr>

                <div class="col-md-4">
                    <label for="ciudad" class="form-label">Ciudad:</label> <span class="text-danger">*</span>
                    <input type="text" name="ciudad" id="ciudad" class="form-control" value="{{ old('ciudad', $persona->ciudad) }}">
                    @error('ciudad')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="zona" class="form-label">Zona:</label> <span class="text-danger">*</span>
                    <input type="text" name="zona" id="zona" class="form-control" value="{{ old('zona', $persona->zona) }}">
                    @error('zona')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label for="calle" class="form-label">Calle:</label>
                    <input type="text" name="calle" id="calle" class="form-control" value="{{ old('calle', $persona->calle) }}">
                    @error('calle')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label for="nro" class="form-label">Nro de puerta:</label>
                    <input type="text" name="nro" id="nro" class="form-control" value="{{ old('nro', $persona->nro) }}">
                    @error('nro')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <h5 class="mb-0"><strong>Datos Del Personal</strong></h5>
                <hr>

                <div class="col-md-6">
                    <label for="fecha_ingreso" class="form-label">Fecha de Ingreso:</label>
                    <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control" value="{{ old('fecha_ingreso', $persona->personal->fecha_ingreso) }}">
                    @error('fecha_ingreso')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="rol" class="form-label">Asignar Rol:</label>
                    <select name="rol" id="rol" class="form-select">
                        <option value="">-- Seleccione un rol --</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->name }}" {{ (old('rol', $persona->roles->pluck('name')->first()) == $rol->name) ? 'selected' : '' }}>
                                {{ $rol->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('rol')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <h5 class="mb-0"><strong>Cambio de Contraseña</strong></h5>
                <p class="text-primary">en caso de que no se quiera realizar el cambio de contraseña debe los espacios en blanco</p>
                <hr>
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
                    <a href="{{ route('personales.index') }}">
                        <button type="button" class="btn btn-secondary"> Cancelar </button>
                    </a>
                    <button type="submit" class="btn btn-primary"> Guardar </button>
                </div>
            </div>
        </form>
      </div>
    </div>
    <!--end::Container-->
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
