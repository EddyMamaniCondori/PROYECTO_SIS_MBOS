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
              <div class="col-sm-6"><h3 class="mb-0">Crear Visita</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('visitas.index')}}">Visitas</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Crear Visitas</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('visitas.store')}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                    @csrf
                
                    <div class="row g-3">

                        <div class="col-md-6"> <!--ocupa la mitad del espacio-->

                            <label for="nombre_visitado" class="form-label">Nombre persona visitada:</label>
                            <input type="text" name="nombre_visitado" id="nombre_visitado" class="form-control" value="{{@old('nombre_visitado')}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('nombre_visitado')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                       <!-- Fecha de la visita -->
                        <div class="col-md-6">
                            <label for="fecha" class="form-label">Fecha de Visita:</label>
                            <input type="date" name="fecha" id="fecha" class="form-control" 
                                value="{{ old('fecha', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                            @error('fecha')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Hora -->
                        <div class="col-md-6">
                            <label for="hora" class="form-label">Hora:</label>
                            <input type="time" name="hora" id="hora" class="form-control" 
                                value="{{ old('hora', \Carbon\Carbon::now()->format('H:i')) }}">
                            @error('hora')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        <!-- Cantidad de presentes -->
                        <div class="col-md-6">
                            <label for="cant_present" class="form-label">Cantidad de Presentes:</label>
                            <input type="number" name="cant_present" id="cant_present" class="form-control" value="{{ old('cant_present', 1) }}">
                            @error('cant_present')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') }}">
                            @error('telefono')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Motivo -->
                        <div class="col-md-6">
                            <label for="motivo" class="form-label">Motivo:</label>
                            <input type="text" name="motivo" id="motivo" class="form-control" value="{{ old('motivo') }}">
                            @error('motivo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Descripción del lugar -->
                        <div class="col-md-12">
                            <label for="descripcion_lugar" class="form-label">Dirección o descripción del lugar:</label>
                            <input type="text" name="descripcion_lugar" id="descripcion_lugar" class="form-control" value="{{ old('descripcion_lugar') }}">
                            @error('descripcion_lugar')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Pastor (desplegable) -->
                         <input type="hidden" name="pastor_id" id="pastor_id" class="form-control" value="2">

                        
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