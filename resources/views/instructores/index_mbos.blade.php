@extends('template')


@section('title', 'Bautisos')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

<x-alerts />
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Desafios Instructores y Estudiantes Distritales - {{$anioActual}}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Desafios Instructores e Estudiantes</li>
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
                                Tabla de Desafios Distritales
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                         <tr>
                                            <th>Distrito</th>
                                            <th>Desf Estudiantes</th>
                                            <th>Estudiantes Alcanzados</th>
                                            <th>Desf Instructores</th>
                                            <th>Instructores Alcanzados</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($resultados as $desafio)
                                        <tr>
                                            <td>
                                                {{ $desafio->id_desafio }} /{{ $desafio->id_distrito }}   {{ $desafio->nombre}}  
                                            </td>
                                            <td>
                                                {{ $desafio->total_desafios_estudiantes }}
                                            </td>
                                            <td>
                                                {{ $desafio->total_estudiantes_alcanzados}}
                                            </td> 
                                            <td>
                                                {{ $desafio->total_desafio_instructores }}
                                            </td>
                                            <td>
                                                {{ $desafio->total_instructores_alcanzados}}
                                            </td>       
                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                    <a href="{{ route('desafios.asignacion.distrital.masivo', $desafio->id_desafio) }}"
                                                        class="btn btn-primary">
                                                        <i class="bi bi-folder-plus"></i> Asignacion Masiva 
                                                    </a>

                                                    <a href="{{ route('desafios.asignacion.distrital', $desafio->id_desafio) }}" class="btn btn-success">
                                                        <i class="bi bi-pencil-square"></i> Ver desafios
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Distrito</th>
                                            <th>Desf Estudiantes</th>
                                            <th>Estudiantes Alcanzados</th>
                                            <th>Desf Instructores</th>
                                            <th>Instructores Alcanzados</th>
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