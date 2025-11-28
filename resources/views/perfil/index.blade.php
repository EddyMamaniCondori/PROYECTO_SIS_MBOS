@extends('template')

@section('title', 'Mi Perfil')

@push('css')
<style>
    .perfil-card {
        background-color: var(--bs-card-bg, var(--bs-body-bg)) !important;
        color: var(--bs-body-color) !important;
        border-radius: 16px;
        max-width: 620px;
        margin: auto;
        padding-bottom: 30px;
    }

    .perfil-photo {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(var(--bs-body-color-rgb), 0.15);
    }

    .input-mode {
        background-color: var(--bs-body-bg) !important;
        color: var(--bs-body-color) !important;
        border: 1px solid rgba(0,0,0,0.12);
    }

    .perfil-header {
        border-radius: 16px 16px 0 0;
        padding: 25px 15px;
        background: linear-gradient(135deg, #003366, #0275d8);
        text-align: center;
        color: white;
    }

    .btn-upload {
        margin-top: 10px;
        font-size: 14px;
    }
</style>

   <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')



<div class="container py-4">
<x-alerts/>
    <div class="card perfil-card shadow-lg">

        {{-- HEADER / FOTO --}}
        <div class="perfil-header">
            <img src="{{ Auth::user()->foto_perfil 
            ? Storage::url(Auth::user()->foto_perfil) 
            : asset('img/user-default.png') }}"
                 class="perfil-photo mb-2"
                 alt="Foto de Perfil">
            <h4 class="fw-bold">{{ Auth::user()->nombre }} {{ Auth::user()->ape_paterno }} {{ Auth::user()->ape_materno }}</h4>
            <span class="inline-block px-3 py-1 text-xs font-medium text-white bg-indigo-600 rounded-full shadow-md mb-1">
                           {{ auth()->user()->getRoleNames()->implode(', ') }}
            </span>
            <p class="mb-0">{{ Auth::user()->email }}</p>

            <form action="{{ route('profile.updatePhoto')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="btn btn-light btn-sm btn-upload fw-bold">
                    Subir nueva foto
                    <input type="file" name="photo" class="d-none" accept="image/*" onchange="this.form.submit()">
                </label>
            </form>
        </div>

        {{-- CUERPO DEL FORM --}}
        <div class="card-body px-4">

            <form action="{{ route('profile.updateData') }}" method="POST">
                @csrf
                @method('PUT')

                <h5 class="fw-bold text-primary mb-3">Datos Personales</h5>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input class="form-control input-mode"
                               value="{{ Auth::user()->nombre }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido Paterno</label>
                        <input class="form-control input-mode"
                               value="{{ Auth::user()->ape_paterno }}" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Apellido Materno</label>
                        <input class="form-control input-mode"
                               value="{{ Auth::user()->ape_materno }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date"
                               class="form-control input-mode"
                               name="fecha_nac"
                               value="{{ Auth::user()->fecha_nac }}">
                    </div>
                    
                </div>
                <div class="col-md-6">
                        <label class="form-label">CI</label>
                        <input class="form-control input-mode"
                               value="{{ Auth::user()->ci }}" readonly>
                </div>

                <h5 class="fw-bold text-primary mt-4 mb-3">Contacto</h5>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Celular</label>
                        <input class="form-control input-mode"
                               name="celular"
                               value="{{ Auth::user()->celular }}">
                    </div>
                    
                </div>

                <h5 class="fw-bold text-primary mt-4 mb-3">Dirección</h5>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Ciudad</label>
                        <input class="form-control input-mode"
                               name="ciudad"
                               value="{{ Auth::user()->ciudad }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Zona</label>
                        <input class="form-control input-mode"
                               name="zona"
                               value="{{ Auth::user()->zona }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Calle / Nro</label>
                        <input class="form-control input-mode"
                               name="calle"
                               placeholder="Calle"
                               value="{{ Auth::user()->calle }}">
                        <input class="form-control input-mode mt-1"
                               name="nro"
                               placeholder="Nro"
                               value="{{ Auth::user()->nro }}">
                    </div>
                </div>

                


                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-success px-4 fw-bold">Guardar Cambios</button>
                </div>
            </form>
            <hr>
            <form action="{{ route('profile.updatePassword') }}" method="POST">
                @csrf
                @method('PUT')
                <h5 class="fw-bold text-primary mt-4 mb-3">Seguridad</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Correo</label>
                            <input class="form-control input-mode"
                                type="email"
                                value="{{ Auth::user()->email }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nueva Contraseña</label>
                            <input id="password" 
                                class="form-control input-mode" 
                                name="password" 
                                type="password" 
                                placeholder="Opcional">
                            <small id="passwordHelp" class="form-text mt-1"></small>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Confirmar Contraseña</label>
                            <input id="password_confirmation" 
                                class="form-control input-mode" 
                                name="password_confirmation" 
                                type="password" 
                                placeholder="Repite la contraseña">
                            <small id="confirmHelp" class="form-text mt-1"></small>
                            @error('apassword_confirmation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Fortaleza</label>

                            <div class="progress mt-1" style="height: 8px;">
                                <div id="passwordStrength" class="progress-bar" 
                                    role="progressbar" 
                                    style="width: 0%;"></div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button class="btn btn-primary px-4 fw-bold">Actualizar Contraseña</button>
                    </div>
            </form>

        </div>

    </div>
</div>
@endsection
@push('js')
<script>
document.addEventListener("DOMContentLoaded", function() {

    const password = document.getElementById("password");
    const confirm = document.getElementById("password_confirmation");
    const strengthBar = document.getElementById("passwordStrength");
    const passwordHelp = document.getElementById("passwordHelp");
    const confirmHelp = document.getElementById("confirmHelp");

    function checkStrength(value) {
        let score = 0;

        if (value.length >= 6) score += 30;
        if (/[A-Z]/.test(value)) score += 30;
        if (/[0-9]/.test(value)) score += 40;

        return score;
    }

    password.addEventListener("input", function () {
        const value = password.value;

        // Evaluar fuerza
        const strength = checkStrength(value);
        strengthBar.style.width = strength + "%";

        if (strength < 40) {
            strengthBar.className = "progress-bar bg-danger";
            passwordHelp.textContent = "Contraseña débil (mín 6 caracteres, 1 número, 1 mayúscula)";
            passwordHelp.style.color = "var(--bs-danger)";
        } 
        else if (strength < 80) {
            strengthBar.className = "progress-bar bg-warning";
            passwordHelp.textContent = "Contraseña aceptable";
            passwordHelp.style.color = "var(--bs-warning)";
        } 
        else {
            strengthBar.className = "progress-bar bg-success";
            passwordHelp.textContent = "Contraseña fuerte";
            passwordHelp.style.color = "var(--bs-success)";
        }

        // Comparación con confirmación
        if (confirm.value.length > 0) {
            validateConfirmation();
        }
    });

    function validateConfirmation() {
        if (confirm.value === password.value) {
            confirmHelp.textContent = "Las contraseñas coinciden";
            confirmHelp.style.color = "var(--bs-success)";
        } else {
            confirmHelp.textContent = "Las contraseñas NO coinciden";
            confirmHelp.style.color = "var(--bs-danger)";
        }
    }

    confirm.addEventListener("input", validateConfirmation);
});
</script>
@endpush
