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
              <div class="col-sm-6"><h3 class="mb-0">Crear Desafio Mensual</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('desafios.index')}}">Desafios Mensuales</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Crear DesafioMensual</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('desafios.store')}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                    @csrf
                
                    <div class="row g-3">
                        <!-- Mes -->
                        <div class="col-md-6 mb-3">
                            <label for="mes" class="form-label">Mes:</label>
                            <select name="mes" id="mes" class="form-select">
                                <option value="">Seleccione un mes</option>
                                @foreach (['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $mes)
                                    <option value="{{ $mes }}" {{ old('mes') == $mes ? 'selected' : '' }}>{{ $mes }}</option>
                                @endforeach
                            </select>
                            @error('mes')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Año -->
                        <div class="col-md-6 mb-3">
                            <label for="anio" class="form-label">Año:</label>
                            <input type="number" name="anio" id="anio" class="form-control" value="{{ old('anio', date('Y')) }}">
                            @error('anio')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Desafíos -->
                        <div class="col-md-6 mb-3">
                            <label for="desafio_visitacion" class="form-label">Desafío Visitación:</label>
                            <input type="number" name="desafio_visitacion" id="desafio_visitacion" class="form-control" value="{{ old('desafio_visitacion', 0) }}">
                            @error('desafio_visitacion')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="desafio_bautiso" class="form-label">Desafío Bautismo:</label>
                            <input type="number" name="desafio_bautiso" id="desafio_bautiso" class="form-control" value="{{ old('desafio_bautiso', 0) }}">
                            @error('desafio_bautiso')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="desafio_inst_biblicos" class="form-label">Desafío Instructores Bíblicos:</label>
                            <input type="number" name="desafio_inst_biblicos" id="desafio_inst_biblicos" class="form-control" value="{{ old('desafio_inst_biblicos', 0) }}">
                            @error('desafio_inst_biblicos')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="desafios_est_biblicos" class="form-label">Desafío Estudiantes Bíblicos:</label>
                            <input type="number" name="desafios_est_biblicos" id="desafios_est_biblicos" class="form-control" value="{{ old('desafios_est_biblicos', 0) }}">
                            @error('desafios_est_biblicos')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Alcanzados -->
                        <div class="col-md-6 mb-3">
                            <label for="visitas_alcanzadas" class="form-label">Visitas Alcanzadas:</label>
                            <input type="number" name="visitas_alcanzadas" id="visitas_alcanzadas" class="form-control" value="{{ old('visitas_alcanzadas', 0) }}">
                            @error('visitas_alcanzadas')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="bautisos_alcanzados" class="form-label">Bautismos Alcanzados:</label>
                            <input type="number" name="bautisos_alcanzados" id="bautisos_alcanzados" class="form-control" value="{{ old('bautisos_alcanzados', 0) }}">
                            @error('bautisos_alcanzados')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="instructores_alcanzados" class="form-label">Instructores Alcanzados:</label>
                            <input type="number" name="instructores_alcanzados" id="instructores_alcanzados" class="form-control" value="{{ old('instructores_alcanzados', 0) }}">
                            @error('instructores_alcanzados')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="estudiantes_alcanzados" class="form-label">Estudiantes Alcanzados:</label>
                            <input type="number" name="estudiantes_alcanzados" id="estudiantes_alcanzados" class="form-control" value="{{ old('estudiantes_alcanzados', 0) }}">
                            @error('estudiantes_alcanzados')
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

                        <div class="col-md-6 mt-3" >
                            <label for="pastor_id" class="form-label">Iglesia:</label>
                            <select name="pastor_id" id="pastor_id" class="form-select">
                                <option value="">-- Seleccione un Pastor --</option>
                                @foreach($pastores as $pastor)
                                    <option value="{{ $pastor->id_pastor }}" 
                                        {{ old('pastor_id') == $pastor->id_pastor}}>
                                        {{ $pastor->persona->nombre }}  {{ $pastor->persona->ape_paterno }}  {{ $pastor->persona->ape_materno }}
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