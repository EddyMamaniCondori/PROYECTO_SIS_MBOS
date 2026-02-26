@extends('template')


@section('title', 'Visitas')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

<x-alerts/>
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0"> Tus Visitas - {{$anioActual}}</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Visitas</li>
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
                                Tabla de Visitas
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Iglesia</th>
                                            <th>P / F visitada</th>
                                            <th>Fecha de visita</th>
                                            <th>Presentes</th>
                                            <th>Telefono</th>
                                            <th>Hora</th>
                                            <th>Motivo</th>
                                            <th>Descripcion del lugar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($visitas as $visita)
                                        <tr>
                                            <td>
                                               
                                            </td>
                                            <td>
                                                {{$visita->nombre_visitado}} 
                                            </td>
                                            <td>
                                                {{$visita->fecha_visita}}
                                            </td>
                                            <td>
                                                {{$visita->cant_present}}
                                            </td>
                                            <td>
                                                {{$visita->telefono}}
                                            </td>
                                            <td> 
                                                {{$visita->hora}}
                
                                            </td>
                                            <td> 
                                                {{$visita->motivo}}
                                            </td>
                                            <td> 
                                                {{$visita->descripcion_lugar}}
                                            </td>
                                        </tr>
                                         
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Iglesia</th>
                                            <th>P / F visitada</th>
                                            <th>Fecha de visita</th>
                                            <th>Presentes</th>
                                            <th>Telefono</th> 
                                            <th>Hora</th>
                                            <th>Motivo</th>
                                            <th>Descripcion del lugar</th>
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