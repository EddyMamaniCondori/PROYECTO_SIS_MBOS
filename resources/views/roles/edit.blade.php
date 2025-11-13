@extends('template')

@section('title', 'Editar Rol')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap Select -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
@endpush

@section('content')
    <!-- CONTENIDO DEL HEADER -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Editar Rol</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar Rol</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENIDO -->
    <div class="app-content">
        <div class="container-fluid">
            <!-- FORMULARIO -->
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <h5 class="mb-0"><strong>Datos Generales</strong></h5>
                    <hr>

                    <!-- Nombre -->
                    <div class="col-md-4">
                        <label for="name" class="form-label">Nombre del Rol: <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" 
                               value="{{ old('name', $role->name) }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <h5 class="mb-0"><strong>Permisos para este Rol:</strong></h5>
                    <hr>

                    <!-- Permisos -->
                    <div class="col-md-12">
                        @foreach ($permisos as $permiso)
                            <div class="form-check">
                                <input type="checkbox" 
                                       name="permission[]" 
                                       id="permiso_{{ $permiso->id }}" 
                                       class="form-check-input"
                                       value="{{ $permiso->name }}"
                                       {{ in_array($permiso->id, $rolePermissions) ? 'checked' : '' }}>
                                <label for="permiso_{{ $permiso->id }}" class="form-check-label">
                                    {{ $permiso->name }}
                                </label>
                            </div>
                        @endforeach

                        @error('permission')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x"></i> Cancelar
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-bookmark"></i> Actualizar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <!-- Bootstrap Select -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush
