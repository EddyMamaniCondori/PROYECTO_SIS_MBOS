@extends('template')


@section('title', 'Tablas')

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
              <div class="col-sm-6"><h3 class="mb-0">ASIGNACIONES DISTRITALES &nbsp {{$mes}} del {{$anio}}</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Desafios Mensuales</li>
                </ol>
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
                                Tabla de Distritos
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>pastor distrital</th>
                                            <th>grupo pequeño</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distritos as $distrito)
                                        <tr>
                                            <td>
                                             &nbsp {{$distrito->nombre}}
                                            </td>
                                            <td>
                                                {{$distrito->id_pastor}} &nbsp {{$distrito->nombre_pastor}} &nbsp {{$distrito->ape_paterno_pastor}} &nbsp {{$distrito->ape_materno_pastor}}
                                            </td>

                                            <td>
                                                
                                                  &nbsp {{ $distrito->dist_nombre ? $distrito->dist_nombre : 'Sin GP asignado' }}
                                            </td>

                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                Asignar
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Asignacion de Desafioa a Iglesias</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
                                                        <hr> <center><h3>ROSAS PAMPA</h3></center>
                                                        <div class="row g-3">
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
                                                        </div>
                                                        <hr> <center><h3>SANTA ROSA</h3></center>
                                                        <div class="row g-3">
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
                                                        </div>
                                                        <hr> <center><h3>SANTIAGO II</h3></center>
                                                        <div class="row g-3">
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
                                                        </div>
                                                        <hr> <center><h3>MINERO</h3></center>
                                                        <div class="row g-3">
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
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="button" class="btn btn-primary">Guardar</button>
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>




                                                <form action="" method="get">
                                                    <button type="submit" class="btn btn-warning">Editar</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>pastor distrital</th>
                                            <th>grupo pequeño</th>
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