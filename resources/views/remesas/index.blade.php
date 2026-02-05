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
              <div class="col-sm-6"><h3 class="mb-0">Remesas</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Remesas</li>
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
                    $sig_mes_num = $ultimo->mes + 1;
                    $anio = $ultimo->anio;
                    // Si pasa de 12, volver a 1 (enero)
                    if($sig_mes_num > 12){
                        $sig_mes_num = 1;
                        $anio = $anio + 1;
                    }
                    // Obtener el nombre del siguiente mes
                    $sig_mes_nombre = $meses_array[$sig_mes_num];
                @endphp
<x-alerts />
            
              <div class="row">
                    <!-- Button trigger modal -->
                    <div class="col-4">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="fa-solid fa-plus"></i> &nbsp; Habilitar Mes de {{ $sig_mes_nombre }} - {{ $anio }}
                    </button>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Habilitar Remesas de {{ $sig_mes_nombre }} - {{ $anio }}</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('remesas.crear') }}" method="POST"> <!-- Agregar method POST -->
                            @csrf
                                <div class="modal-body">
                                        <div class="col-md-4">
                                            <label for="fecha_limite" class="form-label">Fecha límite: <span class="text-danger">*</span></label>
                                            <input 
                                                type="date" 
                                                name="fecha_limite" 
                                                id="fecha_limite" 
                                                class="form-control"
                                                min="{{ $anio }}-{{ str_pad($sig_mes_num, 2, '0', STR_PAD_LEFT) }}-01"
                                               value="{{ $anio }}-{{ str_pad($sig_mes_num, 2, '0', STR_PAD_LEFT) }}-{{ cal_days_in_month(CAL_GREGORIAN, $sig_mes_num, $anio) }}"
                                                required
                                            >
                                        </div>
                                        <input type="hidden" name="mes" value="{{ $sig_mes_num }}">
                                        <input type="hidden" name="anio" value="{{ $anio }}">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
                                Tabla de Remesas
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Mes</th>
                                            <th>Año</th>
                                            <th>Fecha limite</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($meses as $mes)
                                        <tr>
                                            <td>
                                            {{$mes->nombre_mes}} 
                                            <td>
                                                {{$mes->anio}}
                                            </td>

                                            <td>
                                                {{$mes->fecha_limite}}
                                            </td>
                                            <td>
                                                
                                                <div class="btn-group  justify-content-center" role="group" >
                                                    
                                                    
                                                    <!-- Ver -->
                                                    <form action="{{ route('remesas.index_mes', ['mes' => $mes->mes, 'anio' => $mes->anio]) }}" method="GET" class="d-inline">
                                                        <button type="submit" class="btn btn-primary" title="Ver detalles">
                                                            <i class="bi bi-clipboard2-plus-fill"></i> Completar
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#editModal-{{$mes->mes}}-{{$mes->anio}}">
                                                        <i class="bi bi-calendar-event"></i> Editar Fecha
                                                    </button>
                                                    <div class="d-flex justify-content-end ">
                                                        <a href="{{ route('pdf.remesas.mensual', ['anio' => $mes->anio, 'mes' => $mes->mes]) }} " target="_blank"
                                                        class="btn btn-success fw-bold shadow-sm">
                                                            <i class="bi bi-file-earmark-bar-graph"></i>  Informe PDF
                                                        </a>
                                                    </div>

                                                    <!-- Actualiozar -->
                                                    <form action="{{ route('remesas.actualizar', ['mes' => $mes->mes, 'anio' => $mes->anio, 'fecha_limite' => $mes->fecha_limite]) }}" method="GET" class="d-inline">
                                                        <button type="submit" class="btn btn-primary" title="Actualiza las remesas del mes">
                                                           <i class="bi bi-arrow-clockwise"></i> Actualizar
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>

                                        </tr>

                                            <div class="modal fade" id="editModal-{{$mes->mes}}-{{$mes->anio}}" tabindex="-1" aria-labelledby="modalLabel-{{$mes->mes}}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('remesas.editar_fecha_mes') }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header bg-warning">
                                                            <h1 class="modal-title fs-5" id="modalLabel-{{$mes->mes}}">Actualizar Fecha Límite</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Vas a cambiar la fecha límite para todas las iglesias en <strong>{{ $mes->nombre_mes }} / {{ $mes->anio }}</strong>.</p>
                                                            
                                                            <input type="hidden" name="mes" value="{{ $mes->mes }}">
                                                            <input type="hidden" name="anio" value="{{ $mes->anio }}">

                                                            <div class="form-group">
                                                                <label for="fecha_limite">Nueva Fecha Límite:</label>
                                                                <input 
                                                                    type="date" 
                                                                    name="fecha_limite" 
                                                                    class="form-control"
                                                                    required
                                                                    value="{{ $mes->fecha_limite }}"
                                                                    min="{{ $mes->anio }}-{{ str_pad($mes->mes, 2, '0', STR_PAD_LEFT) }}-01"
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-warning">Guardar Cambios</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Mes</th>
                                            <th>Año</th>
                                            <th>Fecha limite</th>
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
            pageLength: 12, 
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