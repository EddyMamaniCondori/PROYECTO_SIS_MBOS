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
              <div class="col-sm-6"><h3 class="mb-0">Lideres Locales por Distritos</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Lideres Locales Distritales</li>
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
                                Tabla de Iglesias
                            </div>
                            <div class="card-body">
                                <table id="example" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID Distrito</th>
                                            <th>Nombre</th>
                                            <th>Total Iglesias</th>
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
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($resumenes as $resumen)
                                        <tr>
                                            <td>{{ $resumen->distrito_id }}</td>
                                            <td>{{ $resumen->nombre }}</td>
                                            <td>{{ $resumen->total_iglesias }}</td>
                                            <td>{{ $resumen->total_filial }}</td>
                                            <td>{{ $resumen->total_congregacion }}</td>
                                            <td>{{ $resumen->total_anciano }}</td>
                                            <td>{{ $resumen->total_diaconisas }}</td>
                                            <td>{{ $resumen->total_diaconos }}</td>
                                            <td>{{ $resumen->total_adultos }}</td>
                                            <td>{{ $resumen->total_jovenes }}</td>
                                            <td>{{ $resumen->total_ninos }}</td>
                                            <td>{{ $resumen->total_gp }}</td>
                                            <td>{{ $resumen->total_parejas }}</td>

                                            <td>
                                                <a href="{{ route('iglesias.lideres_locales.detalle', $resumen->distrito_id) }}" 
                                                class="btn btn-primary btn-sm">
                                                    Ver detalle
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID Distrito</th>
                                            <th>Nombre</th>
                                            <th>Total Iglesias</th>
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