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
              <div class="col-sm-6"><h3 class="mb-0">Iglesias</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Iglesias</li>
                </ol>
              </div>
                @php
                    // Arreglo con los nombres de los meses
                    $meses_array = [
                        1 => 'Enero',
                        2 => 'Febrero',
                        3 => 'Marzo',
                        4 => 'Abril',
                        5 => 'Mayo',
                        6 => 'Junio',
                        7 => 'Julio',
                        8 => 'Agosto',
                        9 => 'Septiembre',
                        10 => 'Octubre',
                        11 => 'Noviembre',
                        12 => 'Diciembre'
                    ];

                    // Sumar +1 al mes actual
                    $sig_mes_num = $ultimo->orden_mes + 1;

                    // Si pasa de 12, volver a 1 (enero)
                    if($sig_mes_num > 12){
                        $sig_mes_num = 1;
                    }

                    // Obtener el nombre del siguiente mes
                    $sig_mes_nombre = $meses_array[$sig_mes_num];
                @endphp

            
              <div class="row">
                        <a href="{{ route('remesas.crear', ['mes' => $sig_mes_num, 'anio' => $ultimo->anio]) }}">
                        <button type="button" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i> &nbsp; Habilitar Mes de {{ $sig_mes_nombre }}
                        </button>
                    </a>
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
                                Tabla de Remesas
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Mes</th>
                                            <th>Año</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($meses as $mes)
                                        <tr>
                                            <td>
                                            {{$mes->mes}} 
                                            <td>
                                                {{$mes->anio}}
                                            </td>
                                            <td>
                                                
                                                <div class="btn-group  justify-content-center" role="group" >
                                                    
                                                    <!-- Ver -->
                                                    <!-- Ver -->
                                                    <form action="{{ route('remesas.index_mes', ['mes' => $mes->mes, 'anio' => $mes->anio]) }}" method="GET" class="d-inline">
                                                        <button type="submit" class="btn btn-primary" title="Ver detalles">
                                                            <i class="bi bi-clipboard2-plus-fill"></i> Completar
                                                        </button>
                                                    </form>


                                                    <!-- Informe -->
                                                    <form action="#"  class="d-inline">
                                                        <button type="submit" class="btn btn-success" title="Generar informe">
                                                            <i class="bi bi-file-earmark-bar-graph"></i> Informe
                                                        </button>
                                                    </form>

                                                    <!-- Gráficos -->
                                                    <form action="#"  class="d-inline">
                                                        <button type="submit" class="btn btn-info" title="Ver gráficos">
                                                            <i class="bi bi-graph-up"></i> Gráficos
                                                        </button>
                                                    </form>

                                                    <!-- Cerrar Mes -->
                                                    <button type="button" 
                                                            class="btn btn-danger"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#confirmModal-{{ $mes->mes }}"
                                                            title="Cerrar mes">
                                                        <i class="bi bi-x-circle"></i> Finalizar
                                                    </button>

                                                </div>
                                            </td>

                                        </tr>

                                            <!-- Modal -->
                                            <div class="modal fade" id="confirmModal-{{$mes->mes}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de Confirmacion</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <strong style="color: red;">¿Seguro que quieres finalizar los registros de este mes ? </strong><br>
                                                    <strong> Mes: </strong> {{$mes->mes}} 
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <form action="#" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                    <button type="submit" class="btn btn-danger">Confirmar</button>
                                                    </form>
                                                    
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Mes</th>
                                            <th>Año</th>
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