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
              <div class="col-sm-6"><h3 class="mb-0">Historial Distrito  {{$distrito->nombre}} </h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Distritos</li>
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
                                Historial Anual
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>gestion</th>
                                            <th>pastor distrital</th>
                                            <th>Fecha asignacion</th>
                                            <th>Fecha finalizacion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {{$distrito->año}} 
                                            </td>
                                            <td>
                                            {{$distrito->id_pastor}} &nbsp {{ $distrito->nombre_p }}
                                                    &nbsp;{{ $distrito->ape_paterno }}
                                                    &nbsp;{{ $distrito->ape_materno }}
                                            </td>
                                            <td>
                                                {{$distrito->fecha_asignacion}}
                                            </td>
                                            <td>
                                                <strong style="color:green">En curso</strong>
                                            </td>
                                        </tr>

                                        @foreach ($historial as $dirige)
                                        <tr>
                                            <td>
                                                {{$dirige->año}} 
                                            </td>
                                            <td>
                                            {{$dirige->id_pastor}} &nbsp {{ $dirige->nombre }}
                                                    &nbsp;{{ $dirige->ape_paterno }}
                                                    &nbsp;{{ $dirige->ape_materno }}
                                            </td>
                                            <td>
                                                {{$dirige->fecha_asignacion}}
                                            </td>
                                            <td>
                                                {{$dirige->fecha_finalizacion}}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>gestion</th>
                                            <th>pastor distrital</th>
                                            <th>Fecha asignacion</th>
                                            <th>Fecha finalizacion</th>
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
            ordering: false,
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