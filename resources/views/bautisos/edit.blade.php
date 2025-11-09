@extends('template')


@section('title', 'Bautisos')

@push('css')
        <!--bootstrap select-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

@endpush

@section('content')

@if (session('success'))
    <script>
        const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
        });
        Toast.fire({
        icon: "success",
        title: "{{ session('success') }}"
        });
    </script>
@endif
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Editar Bautiso</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li> 
                  <li class="breadcrumb-item" ><a href="{{ route('bautisos.index') }}" >Bautisos</a></li>
                  <li class="breadcrumb-item" ><a href="{{ route('bautisos.show', $id_distrito) }}" >Bautiso Distrital</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Editar bautizo</li>
                </ol>

              </div>
            </div>
            <div class="row">
                <div class="container-fluid">
                        <form action="{{ route('bautisos.update', $bautizo->id_bautiso)}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                        @csrf
                        @method('PUT') 
                            <div class="row g-3">
                                 <input type="hidden" name="id_distrito" id="id_distrito" value="{{$id_distrito}}">
                                   
                                <h5 class="mb-0"><strong>Datos del Bautizo</strong></h5>
                                <div class="col-md-3">
                                    <label for="fecha_bautizo" class="form-label">Fecha de Bautizo: <span class="text-danger">*</span> </label>
                                    <input type="date" name="fecha_bautizo" class="form-control" value="{{ old('fecha_bautizo', $bautizo->fecha_bautizo) }}">
                                    @error('fecha_bautizo')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- Bautizado -->
                                <div class="col-md-3">
                                    <label for="tipo" class="form-label">Tipo: <span class="text-danger">*</span> </label>
                                    <select name="tipo" id="tipo" class="form-select">
                                        <option value="bautizo" {{ old('tipo', $bautizo->tipo) == 'bautizo' ? 'selected' : '' }}>Bautizo</option>
                                        <option value="profesion de fe" {{ old('tipo', $bautizo->tipo) == 'profesion de fe' ? 'selected' : '' }}>Profesión de fe</option>
                                        <option value="rebautismo" {{ old('tipo', $bautizo->tipo) == 'rebautismo' ? 'selected' : '' }}>Rebautismo</option>
                                    </select>
                                    @error('tipo')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-3 mt-3" id="selectIglesia">
                                    <label for="id_iglesia" class="form-label">Iglesia: <span class="text-danger">*</span> </label>
                                    <select data-size="9" title="-- Seleccione un Iglesia --" data-live-search="true" name="id_iglesia" id="id_iglesia" class="form-control selectpicker show-tick" >                         
                                        <option value="">-- Seleccione una iglesia --</option>
                                        @foreach($iglesias as $iglesia)
                                            <option value="{{ $iglesia->id_iglesia }}" 
                                                {{ old('id_iglesia', $bautizo->id_iglesia) == $iglesia->id_iglesia ? 'selected' : '' }}>
                                                {{ $iglesia->nombre  }}/{{ $iglesia->tipo  }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('id_iglesia')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <a href="{{ route('bautisos.show', $id_distrito) }}" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-success">  <i class="bi bi-bookmark"></i> Guardar </button>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                
            </div>
          </div>
        </div>
        <!--contenido-->
        @endsection



@push('js')

        <!--bootstrap select-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@endpush