@extends('template')


@section('title', 'Bautisos')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
              <div class="col-sm-6"><h3 class="mb-0">Desafios Mensuales</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Desafios Mensuales</li>
                </ol>
              </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                    <button type="button" class="btn btn-primary"data-bs-toggle="modal" data-bs-target="#staticBackdrop"> <i class="fa-solid fa-plus"></i> &nbsp Añadir nuevo Desafio Biblico</button>
                     <br>
                    </div>


                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Crear nuevo desafio</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Ingrese El desafio Mensual que quieres ingresar.
                            <form action="{{ route('desafios.store_mes') }}" method="POST">
                                @csrf
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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            
                                <button type="submit" class="btn btn-primary">Confirmar</button>
                            
                        </div>
                        </form>
                        </div>
                    </div>
                    </div>
                
                
                
                </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabla de Desafios Mensuales
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                         <tr>
                                            <th>Mes</th>
                                            <th>Año</th>
                                            <th>Estado</th>
                                            <th>Acciones</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($desafios as $desafio)
                                        <tr>
                                            <td>
                                                {{ $desafio->mes }} 
                                            </td>
                                            <td class="text-center">
                                                {{ $desafio->anio}}
                                            </td>
                                            <td class="text-center">
                                                -
                                            </td>
                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                <form action="{{ route('desafios.show_mes', ['mes' => $desafio->mes, 'anio' => $desafio->anio]) }}" method="GET">
                                                    <button type="submit" class="btn btn-primary">Asignar Desafíos</button>
                                                </form>
                                                
                                                <form action="" method="get">
                                                    <button type="submit" class="btn btn-success">Ver Resultados</button>
                                                </form>
                                                <form action="" method="get">
                                                    <button type="submit" class="btn btn-warning">Editar</button>
                                                </form>
                                                <button type="button" class="btn btn-danger" >Eliminar</button>
                                                
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Mes</th>
                                            <th>Año</th>
                                            <th>Estado</th>
                                            <th>Acciones</th> 
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>


            <div class="card-body">
          </div>
          <!--end::Container-->
        </div>
        @endsection



@push('js')
    <!--JQUERY-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!--data table-->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script>
    $(document).ready(function() {
        $('#example').DataTable({
            scrollX: true,
            language: {
                search: "Buscar:",   // Cambia el texto de "Search"
                lengthMenu: "Mostrar _MENU_ registros por página",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "No hay registros disponibles",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                zeroRecords: "No se encontraron resultados",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                },
            }
        });
    });
</script>
@endpush