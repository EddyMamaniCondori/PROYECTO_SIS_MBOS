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
              <div class="col-sm-6"><h3 class="mb-0">Desafios Distritales - {{$anioActual}}</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Desafios Distritales</li>
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
                                Tabla de Desafios Mensuales
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                         <tr>
                                            <th>Distrito</th>
                                            <th>Pastor</th>
                                            <th>anio</th>
                                            <th>acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($desafios as $desafio)
                                        <tr>
                                            <td>
                                                {{ $desafio->id_desafio }}  /{{ $desafio->id_distrito }}   {{ $desafio->nombre_distrito }}  
                                            </td>
                                            <td>
                                                {{ $desafio->nombre_p }} {{ $desafio->ape_paterno_p }}  {{ $desafio->ape_materno_p }}
                                            </td>
                                            <td class="text-center">
                                                {{ $desafio->anio}}
                                            </td>      
                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                    <form action="#" method="GET">
                                                        <button type="submit" class="btn btn-primary"> <i class="bi bi-graph-up"></i> Ver Avance</button>
                                                    </form>

                                                    <a href="{{ route('desafios.index_distrital', $desafio->id_desafio) }}" class="btn btn-success">
                                                        <i class="bi bi-pencil-square"></i> Asignar
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Distrito</th>
                                            <th>Pastor</th>
                                            <th>anio</th>
                                            <th>acciones</th>
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