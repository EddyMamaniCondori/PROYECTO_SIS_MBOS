@extends('template')


@section('title', 'Crear')

@push('css')
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--bootstrap select-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">



@endpush

@section('content')

@php
            $meses_array = [
                        1 => 'Enero',
                        2 => 'Febrero',
                        3 => 'Marzo',
                        4 => 'Abril',
                        5 => 'Mayo',
                        6 => 'Junio',
                        7 => 'Julio',
                        8 => 'Agosto',
                        9 => 'Septiembre',
                        10 => 'Octubre',
                        11 => 'Noviembre',
                        12 => 'Diciembre'
                    ];
            
@endphp

        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Crear Visita - {{ $meses_array[$mensual->mes]}}   </h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{route('visitas.index')}}">Visitas</a></li>
                  <li class="breadcrumb-item"><a href="{{route('visitas.index_mes')}}">Visitas Mes</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Registrar Visita</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">

            <!--begin::TABLA-->
            <form action="{{ route('visita_cape.store')}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                    @csrf
                
                    <div class="row g-3">
                        <input type="hidden" name="id_mensual" id="id_mensual" value="{{$mensual->id_mensual}}">
                        <h5 class="mb-0"><strong>Datos Generales</strong></h5>
                        <label class="text-primary">La fecha limite de registros es {{$mensual->fecha_limite}}</label>
                        <hr>
                        <div class="col-md-4"> <!--ocupa la mitad del espacio-->

                            <label for="nombre_visitado" class="form-label">Nombre persona visitada:<span class="text-danger">*</span></label>
                            <input type="text" name="nombre_visitado" id="nombre_visitado" class="form-control" value="{{@old('nombre_visitado')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('nombre_visitado')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>  
                        <!-- Cantidad de presentes -->
                        <div class="col-md-2">
                            <label for="cant_present" class="form-label">Cantidad de Presentes:<span class="text-danger">*</span></label>
                            <input type="number" name="cant_present" id="cant_present" class="form-control" value="{{ old('cant_present', 1) }}">
                            @error('cant_present')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        @php
                            use Carbon\Carbon;

                            // Construimos las fechas de inicio y fin del mes
                            $fechaInicioMes = Carbon::create($mensual->anio, $mensual->mes, 1)->format('Y-m-d');
                            $fechaFinMes = Carbon::create($mensual->anio, $mensual->mes, 1)->endOfMonth()->format('Y-m-d');
                        @endphp
                       <!-- Fecha de la visita -->
                        <div class="col-md-3">
                            <label for="fecha_visita" class="form-label">Fecha de Visita:<span class="text-danger">*</span></label>
                            <input type="date" name="fecha_visita" id="fecha_visita" class="form-control" 
                                value="{{ old('fecha_visita', \Carbon\Carbon::now()->format('Y-m-d')) }}" 
                                min="{{ $fechaInicioMes }}"
                                max="{{ $fechaFinMes }}">
                            @error('fecha_visita')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Hora -->
                        <div class="col-md-2">
                            <label for="hora" class="form-label">Hora:</label>
                            <input type="time" name="hora" id="hora" class="form-control" 
                                value="{{ old('hora', \Carbon\Carbon::now()->format('H:i')) }}">
                            @error('hora')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="motivo" class="form-label">Motivo: <span class="text-danger">*</span></label>
                            <select name="motivo" id="motivo" class="form-control" required>
                                <option value="">-- Seleccione --</option>
                                <option value="Pastoral" {{ old('motivo') == 'Pastoral' ? 'selected' : '' }}>Pastoral</option>
                                <option value="Evangelismo" {{ old('motivo') == 'Evangelismo' ? 'selected' : '' }}>Evangelismo</option>
                                <option value="Mayordomia" {{ old('motivo') == 'Mayordomia' ? 'selected' : '' }}>Mayordomia</option>
                                <option value="Coordinacion" {{ old('motivo') == 'Coordinacion' ? 'selected' : '' }}>Coordinacion</option>
                                <option value="otros" {{ old('motivo') == 'otros' ? 'selected' : '' }}>Otros</option>
                            </select>
                            @error('motivo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        <h5 class="mb-0"><strong>Datos de Direccion y Contacto</strong></h5>
                        <hr>
                        <!-- Teléfono -->
                        <div class="col-md-3">
                            <label for="telefono" class="form-label">Teléfono: <span class="text-danger">*</span> </label>
                            <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') }}">
                            @error('telefono')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Descripción del lugar -->
                        <div class="col-md-5">
                            <label for="descripcion_lugar" class="form-label">Dirección o descripción del lugar: <span class="text-danger">*</span> </label>
                            <input type="text" name="descripcion_lugar" id="descripcion_lugar" class="form-control" value="{{ old('descripcion_lugar') }}">
                            @error('descripcion_lugar')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <input type="hidden" name="id_ue" id="id_ue" value="{{$id_ue}}">
                    
                        <div>
                            <a href="{{route('visitas.llenar_mes', $mensual->id_mensual)}}"><button type="button" class="btn btn-secondary"> <i class="bi bi-x"></i> Cancelar </button></a>
                            <button type="submit" class="btn btn-primary">  <i class="bi bi-bookmark"></i> Guardar </button>
                        </div>
                    </div>

            </form>
            </div>
          </div>
          <!--end::Container-->
        </div>
        @endsection



@push('js')
        <!--bootstrap select-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush