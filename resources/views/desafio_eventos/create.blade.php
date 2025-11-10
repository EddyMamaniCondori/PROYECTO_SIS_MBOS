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
              <div class="col-sm-6"><h3 class="mb-0">Crear Evento</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('desafio_eventos.index')}}">Eventos</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Crear Evento</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('desafio_eventos.store')}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
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
                        <!--celular-->
                           @php
                            use Carbon\Carbon;
                        @endphp
                        <div class="col-md-4">
                            <label for="anio" class="form-label">Año: <span class="text-danger"> * </span></label>
                            <input type="number" name="anio" id="anio" class="form-control" value="{{@old('anio', \Carbon\Carbon::now()->format('Y'))}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('anio')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <h5 class="mb-0"><strong>Datos de Organizacion</strong></h5>
                        <hr>
                     
                        <!--fecha-->
                        <div class="col-md-4">
                            <label for="fecha_inicio" class="form-label">Fecha Inicio: <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{@old('fecha_inicio', \Carbon\Carbon::now()->format('Y-m-d'))}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('fecha_inicio')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="fecha_final" class="form-label">Fecha Finalizacion: <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_final" id="fecha_final" class="form-control" value="{{@old('fecha_final', \Carbon\Carbon::now()->format('Y-m-d'))}}"> <!--name= debe tener mismo valor de la base de datos-->
                            @error('fecha_final')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        
                        <div>
                            <a href="{{route('desafio_eventos.index')}}"><button type="button" class="btn btn-secondary"> Cancelar </button></a>
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