@extends('template')


@section('title', 'Crear')

@push('css')
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

       <!--bootstrap select-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">


@endpush

@section('content')
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Crear Rol</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('roles.index')}}">Roles</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Crear Rol</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <form action="{{ route('roles.store')}}" method="POST" > <!--en actiion va que accion vamos ha acer con este formulario una ves de click en guardar-->
                    @csrf
                    <div class="row g-3">
                        <h5 class="mb-0"><strong>Datos Generales</strong></h5>
                        <hr>
                        <!-- Nombre -->
                        <div class="col-md-4">
                            <label for="name" class="form-label">Nombre del Rol: <span class="text-danger">*</span> </label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <h5 class="mb-0"><strong>Permisos para este Rol:</strong></h5>
                        <hr>

                        <!-- Celular -->
                        <div class="col-md-12">
                            <label for="celular" class="form-label">Celular:<span class="text-danger">*</span></label>
                            
                            @foreach ($permisos as $item)
                                <div class="form-check">
                                    <input type="checkbox" name="permission[]" id="{{$item->id}}" class="form-check-input" value="{{$item->name}}">
                                    <label for="{{$item->id}}" class="form-check-label">{{$item->name}}</label>
                                </div>
                            @endforeach
                            @error('permission')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        

                    
                        <div><a href="{{route('roles.index')}}"><button type="button" class="btn btn-secondary"> <i class="bi bi-x"></i> Cancelar </button></a>
                            
                            <button type="submit" class="btn btn-primary"> <i class="bi bi-bookmark"></i> Guardar </button>
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