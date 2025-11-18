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
              <div class="col-sm-6"><h3 class="mb-0">Editar Iglesia</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('iglesias.index')}}">Iglesias</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Editar Iglesias</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('iglesias.update', $iglesia->id_iglesia) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <h5 class="mb-0"><strong>Datos Generales </strong></h5>
                    <hr>
                    <!-- Nombre -->
                    <div class="col-md-4">
                        <label for="nombre" class="form-label">Nombre de la Iglesia: <strong><span class="text-danger">*</span></strong></label>
                        <input type="text" name="nombre" id="nombre" 
                            class="form-control" value="{{@old('nombre', $iglesia->nombre)}}" 
                            @cannot('editar-iglesias')
                                disabled 
                            @endcannot>
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="codigo" class="form-label">Codigo: <strong><span class="text-danger">*</span></strong></label>
                        <input type="number" name="codigo" id="codigo" 
                            class="form-control" value="{{ old('codigo', $iglesia->codigo)}}"  min="0"  step="1" @cannot('editar-iglesias')
                                disabled 
                            @endcannot>
                        @error('codigo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="col-md-3 mt-4">
                        <label for="tipo" class="form-label">Tipo: <strong><span class="text-danger">*</span></strong> </label>
                        <select name="tipo" id="tipo" class="form-select" required @cannot('editar-iglesias')
                                disabled 
                            @endcannot>
                            <option value="">-- Seleccione un tipo --</option>
                            <option value="Iglesia" {{ old('tipo', $iglesia->tipo) == 'Iglesia' ? 'selected' : '' }}>Iglesia</option>
                            <option value="Grupo" {{ old('tipo', $iglesia->tipo) == 'Grupo' ? 'selected' : '' }}>Grupo</option>
                            <option value="Filial" {{ old('tipo', $iglesia->tipo) == 'Filial' ? 'selected' : '' }}>Filial</option>
                        </select>

                        @error('tipo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-3 mt-4">
                        <label for="lugar" class="form-label">Lugar: <strong><span class="text-danger">*</span></strong></label>
                        <select name="lugar" id="lugar" class="form-select" required @cannot('editar-iglesias')
                                disabled 
                            @endcannot>
                            <option value="">-- Seleccione un lugar--</option>
                            <option value="ALTIPLANO" {{ old('lugar', $iglesia->lugar) == 'ALTIPLANO' ? 'selected' : '' }}>Altiplano</option>
                            <option value="EL ALTO" {{ old('lugar', $iglesia->lugar) == 'EL ALTO' ? 'selected' : '' }}>El Alto</option>
                        </select>
                        @error('lugar')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Feligresía -->
                    <div class="col-md-2">
                        <label for="feligresia" class="form-label">Cantidad de feligreses:</label>
                        <input type="number" name="feligresia" id="feligresia" 
                            class="form-control" value="{{ old('feligresia',$iglesia->feligresia )  }}" min="0"  step="1" @cannot('editar-iglesias')
                                disabled 
                            @endcannot>
                        @error('feligresia')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Feligrasía Asistente -->
                    <div class="col-md-2">
                        <label for="feligresia_asistente" class="form-label">Asistencia promedio:</label>
                        <input type="number" name="feligresia_asistente" id="feligresia_asistente" 
                            class="form-control" value="{{ old('feligresia_asistente', $iglesia->feligresia_asistente) }}" min="0"  step="1">
                        @error('feligresia_asistente')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Ciudad -->
                    <h5 class="mb-0"><strong>Datos De Direccion </strong></h5>
                    <hr>

                    <div class="col-md-4">
                        <label for="ciudad" class="form-label">Ciudad: <strong><span class="text-danger">*</span></strong></label>
                        <input type="text" name="ciudad" id="ciudad" 
                            class="form-control" value="{{ old('ciudad', $iglesia->ciudad) }}">
                        @error('ciudad')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- Barrio -->
                    <div class="col-md-3">
                        <label for="zona" class="form-label">zona: <strong><span class="text-danger">*</span></strong></label>
                        <input type="text" name="zona" id="zona" 
                            class="form-control" value="{{ old('zona', $iglesia->zona )}}">
                        @error('zona')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- País -->
                    <div class="col-md-3">
                        <label for="calle" class="form-label">calle:</label>
                        <input type="text" name="calle" id="calle" 
                            class="form-control" value="{{ old('calle', $iglesia->calle) }}">
                        @error('calle')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- País -->
                    <div class="col-md-2">
                        <label for="nro" class="form-label">nro:</label>
                        <input type="text" name="nro" id="nro" 
                            class="form-control" value="{{ old('nro', $iglesia->nro)  }}">
                        @error('nro')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    @can('asignaciones-iglesias')
                        
                    
                    <!-- Checkbox: ¿Tiene distrito? -->
                    <h5 class="mb-0"><strong>Datos Relacionales </strong></h5>
                    <hr> 
                    <span class="text-danger">En caso de no pertenecer a un distrito no llene este espacio</span>
                    <!-- Select de distritos (oculto al inicio) -->
                    <div class="col-md-6 mt-3">
                        <label for="id_distrito" class="form-label">Distrito:</label>
                        <select data-size="9" title="-- Seleccione un distrito --" data-live-search="true" name="distrito_id" id="distrito_id" class="form-control selectpicker show-tick" >
                        @foreach($distritos as $distrito)
                                <option 
                                    value="{{ $distrito->id_distrito }}"
                                    {{ (old('distrito_id', $iglesia->distrito_id) == $distrito->id_distrito) ? 'selected' : '' }}>
                                    {{ $distrito->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('distrito_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    @endcan
                    <!-- Botón Guardar -->
                    <div class="col-12 mt-3">
                        @canany(['editar-iglesias','editar pastor-iglesias'])
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        @endcanany
                        
                        @can('ver-iglesias')
                            <a href="{{ route('iglesias.index') }}" class="btn btn-secondary">Cancelar</a>
                        @endcan
                        @can('ver pastor-iglesias')
                            <a href="{{ route('iglesias.index_pastores') }}" class="btn btn-secondary">Cancelar</a>
                        @endcan
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