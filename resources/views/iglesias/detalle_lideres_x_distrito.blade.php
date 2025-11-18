@extends('template')


@section('title', 'Tablas')

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
              <div class="col-sm-6"><h3 class="mb-0">Lideres Locales <br><label class="text-primary"><strong>Distrito: </strong> {{$distrito->nombre}}</label></h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="#">Lideres Locales Distritales</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Detalle Iglesias</li>
                </ol>
              </div>
              <div class="row">
                <a href="{{route('iglesias.lideres_locales')}}"><button type="button" class="btn btn-primary"> Volver</button> </a><br>
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
                                Tabla de Iglesias
                            </div>
                            <div class="card-body">
                                <table id="example" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Id_Iglesia</th>
                                            <th>Iglesia</th>
                                            <th>tipo</th>
                                            <th>Dir. Filial</th>
                                            <th>Dir. Congregación</th>
                                            <th>Anciano</th>
                                            <th>Diaconisas</th>
                                            <th>Diáconos</th>
                                            <th>EESS Adultos</th>
                                            <th>EESS Jóvenes</th>
                                            <th>EESS Niños</th>
                                            <th>GP</th>
                                            <th>Parejas Misioneras</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($iglesias as $resumen)
                                        <tr>
                                            <td>{{ $resumen->id_iglesia}}</td>
                                            <td>{{ $resumen->nombre}}</td>
                                            <td>{{ $resumen->tipo }}</td>
                                            <td>{{ $resumen->Dir_Filial?? 0}}</td>
                                            <td>{{ $resumen->Dir_congregacion ?? 0}}</td>
                                            <td>{{ $resumen->Anciano?? 0 }}</td>
                                            <td>{{ $resumen->Diaconisas?? 0 }}</td>
                                            <td>{{ $resumen->Diaconos ?? 0}}</td>
                                            <td>{{ $resumen->EESS_Adultos ?? 0}}</td>
                                            <td>{{ $resumen->EESS_Jovenes ?? 0}}</td>
                                            <td>{{ $resumen->EESS_Niños ?? 0}}</td>
                                            <td>{{ $resumen->GP ?? 0}}</td>
                                            <td>{{ $resumen->Parejas_misioneras ?? 0}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Id_Iglesia</th>
                                            <th>Iglesia</th>
                                            <th>tipo</th>
                                            <th>Dir. Filial</th>
                                            <th>Dir. Congregación</th>
                                            <th>Anciano</th>
                                            <th>Diaconisas</th>
                                            <th>Diáconos</th>
                                            <th>EESS Adultos</th>
                                            <th>EESS Jóvenes</th>
                                            <th>EESS Niños</th>
                                            <th>GP</th>
                                            <th>Parejas Misioneras</th>
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