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
              <div class="col-sm-6"><h3 class="mb-0">Puntualidad de las Iglesias - GESTION 2026</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Puntualidades</li>
                </ol>
              </div>
            </div>
            <div class="row">
                <div class="d-flex gap-2 mb-3">

                    <a href="{{ url('/puntualidad/export-excel') }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                    </a>
                    <a href="{{ route('puntualidad.exportPdf') }}" class="btn btn-danger" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
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
                                Tabla de Iglesias
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>    
                                            <th>Distrito</th>
                                            <th>Cod </th>
                                            <th>Nombre </th>
                                            <th>Tipo</th>
                                            <th>Lugar</th>
                                            <th>anio</th>
                                            <th>Ene</th>
                                            <th>Feb</th>
                                            <th>Mar</th>
                                            <th>Abr</th>
                                            <th>May</th>
                                            <th>Jun</th>
                                            <th>Jul</th>
                                            <th>Ago</th>
                                            <th>Sep</th>
                                            <th>Oct</th>
                                            <th>Nov</th>
                                            <th>Dic</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($iglesias as $iglesia)
                                        <tr>
                                            <td>
                                                {{$iglesia->nombre_distrito}}
                                            </td>
                                            <td>
                                                {{$iglesia->codigo}}
                                            </td>
                                            <td>
                                             {{$iglesia->nombre}} 
                                            </td>
                                            <td>
                                                {{$iglesia->tipo}}
                                            </td>
                                            <td>
                                                {{$iglesia->lugar}}
                                            </td>
                                            <td>
                                                {{$iglesia->anio}}
                                            </td>
                                            <td> 

                                                 @php
                                                    $valor = $iglesia->puntualidad_enero;
                                                @endphp
    @if ($valor === '0')
        <i class="bi bi-star" style="color: gold;"></i>
    @elseif ($valor === '1')
        <i class="bi bi-star-half" style="color: gold;"></i>
    @elseif ($valor === '2')
        <i class="bi bi-star-fill" style="color: gold;"></i>
    @else
        -
    @endif
                                            </td>
                                            <td>
                                                 @php
                                                    $valor = $iglesia->puntualidad_febrero;
                                                @endphp
                                                
    @if ($valor === '0')
        <i class="bi bi-star" style="color: gold;"></i>
    @elseif ($valor === '1')
        <i class="bi bi-star-half" style="color: gold;"></i>
    @elseif ($valor === '2')
        <i class="bi bi-star-fill" style="color: gold;"></i>
    @else
        -
    @endif
                                            </td>
                                            <td>
                                                @php
                                                    $valor = $iglesia->puntualidad_marzo;
                                                @endphp
    @if ($valor === '0')
        <i class="bi bi-star" style="color: gold;"></i>
    @elseif ($valor === '1')
        <i class="bi bi-star-half" style="color: gold;"></i>
    @elseif ($valor === '2')
        <i class="bi bi-star-fill" style="color: gold;"></i>
    @else
        -
    @endif
                                            </td>
                                            <td>
                                                @php
                                                   $valor = $iglesia->puntualidad_abril;
                                                @endphp
    @if ($valor === '0')
        <i class="bi bi-star" style="color: gold;"></i>
    @elseif ($valor === '1')
        <i class="bi bi-star-half" style="color: gold;"></i>
    @elseif ($valor === '2')
        <i class="bi bi-star-fill" style="color: gold;"></i>
    @else
        -
    @endif
                                            </td>  
                                            <td>
                                                @php
                                                    $valor =$iglesia->puntualidad_mayo;
                                                @endphp
    @if ($valor === '0')
        <i class="bi bi-star" style="color: gold;"></i>
    @elseif ($valor === '1')
        <i class="bi bi-star-half" style="color: gold;"></i>
    @elseif ($valor === '2')
        <i class="bi bi-star-fill" style="color: gold;"></i>
    @else
        -
    @endif
                                            </td>  
                                            <td>
                                                @php
                                                    $valor = $iglesia->puntualidad_junio;
                                                @endphp
    @if ($valor === '0')
        <i class="bi bi-star" style="color: gold;"></i>
    @elseif ($valor === '1')
        <i class="bi bi-star-half" style="color: gold;"></i>
    @elseif ($valor === '2')
        <i class="bi bi-star-fill" style="color: gold;"></i>
    @else
        -
    @endif
                                            </td>  
                                            <td>
                                                @php
                                                    $valor = $iglesia->puntualidad_julio;
                                                @endphp
    @if ($valor === '0')
        <i class="bi bi-star" style="color: gold;"></i>
    @elseif ($valor === '1')
        <i class="bi bi-star-half" style="color: gold;"></i>
    @elseif ($valor === '2')
        <i class="bi bi-star-fill" style="color: gold;"></i>
    @else
        -
    @endif
                                            </td>  
                                            <td>
                                                @php
                                                    $valor = $iglesia->puntualidad_agosto;
                                                @endphp
    @if ($valor === '0')
        <i class="bi bi-star" style="color: gold;"></i>
    @elseif ($valor === '1')
        <i class="bi bi-star-half" style="color: gold;"></i>
    @elseif ($valor === '2')
        <i class="bi bi-star-fill" style="color: gold;"></i>
    @else
        -
    @endif
                                            </td>  
                                            <td>
                                                @php
                                                    $valor = $iglesia->puntualidad_septiembre;
                                                @endphp
    @if ($valor === '0')
        <i class="bi bi-star" style="color: gold;"></i>
    @elseif ($valor === '1')
        <i class="bi bi-star-half" style="color: gold;"></i>
    @elseif ($valor === '2')
        <i class="bi bi-star-fill" style="color: gold;"></i>
    @else
        -
    @endif
                                            </td>  
                                            <td>
                                                @php
                                                    $valor = $iglesia->puntualidad_octubre;
                                                @endphp
    @if ($valor === '0')
        <i class="bi bi-star" style="color: gold;"></i>
    @elseif ($valor === '1')
        <i class="bi bi-star-half" style="color: gold;"></i>
    @elseif ($valor === '2')
        <i class="bi bi-star-fill" style="color: gold;"></i>
    @else
        -
    @endif
                                            </td>  
                                            <td>
                                                @php
                                                    $valor = $iglesia->puntualidad_noviembre;
                                                @endphp
    @if ($valor === '0')
        <i class="bi bi-star" style="color: gold;"></i>
    @elseif ($valor === '1')
        <i class="bi bi-star-half" style="color: gold;"></i>
    @elseif ($valor === '2')
        <i class="bi bi-star-fill" style="color: gold;"></i>
    @else
        -
    @endif
                                            </td>  
                                            <td>
                                                @php
                                                    $valor = $iglesia->puntualidad_diciembre;
                                                @endphp
    @if ($valor === '0')
        <i class="bi bi-star" style="color: gold;"></i>
    @elseif ($valor === '1')
        <i class="bi bi-star-half" style="color: gold;"></i>
    @elseif ($valor === '2')
        <i class="bi bi-star-fill" style="color: gold;"></i>
    @else
        -
    @endif
                                            </td>     
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Distrito</th>
                                            <th>Cod </th>
                                            <th>Nombre </th>
                                            <th>Tipo</th>
                                            <th>Lugar</th>
                                            <th>anio</th>
                                            <th>Ene</th>
                                            <th>Feb</th>
                                            <th>Mar</th>
                                            <th>Abr</th>
                                            <th>May</th>
                                            <th>Jun</th>
                                            <th>Jul</th>
                                            <th>Ago</th>
                                            <th>Sep</th>
                                            <th>Oct</th>
                                            <th>Nov</th>
                                            <th>Dic</th>
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