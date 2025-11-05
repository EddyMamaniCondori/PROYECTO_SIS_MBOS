@extends('template')


@section('title', 'Tablas')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
@php
    $meses = [
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
        12 => 'Diciembre',
    ];
@endphp

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
              <div class="col-sm-6"><h3 class="mb-0">Remesas Pendientes Mensuales </h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Pendientes anuales</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--div BUSCADOR-->
            <div class="row">
              <div class="col-8">
                <div class="row">
                  <div class="card mb-4">
                    <form class="mt-3 mb-3" action="#" method="POST" id="filtroForm">
                    @csrf  
                      <div class="row">
                        <!--SELECT PERSOMNALIZADO-->
                        <div class="col-3">
                          <label class="form-label fw-semibold">Periodo:</label>
                          <select id="periodoInicio" name="periodoInicio" class="form-select">
                            <option value="">--Selecciona--</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                          </select>
                        </div>
                        <!--SELECT PERSOMNALIZADO-->
                        <div class="col-2">
                          <button type="submit" class="btn btn-success mt-3"><i class="bi bi-funnel-fill"></i> &nbsp; Filtrar</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="card mb-4 p-2">
                  <button type="button" class="btn btn-primary w-100 mb-2"> <i class="bi bi-filetype-xlsx"></i> &nbsp;  Descargar EXCEL</button>
                  <button type="button" class="btn btn-success w-100"> <i class="bi bi-filetype-pdf"></i>  &nbsp; Descargar PDF</button>
                </div>
              </div>

            </div>
            <!--begin::TABLA-->
            <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabla de Pendientes
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Iglesia</th>
                                            <th>Enero</th>
                                            <th>Febrero</th>
                                            <th>Marzo</th>
                                            <th>Abril</th>
                                            <th>Mayo</th>
                                            <th>Junio</th>
                                            <th>Julio</th>
                                            <th>Agosto</th>
                                            <th>Septiembre</th>
                                            <th>Octubre</th>
                                            <th>Noviembre</th>
                                            <th>Diciembre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datos as $dato)
                                        <tr>
                                            <td>
                                                {{$dato->codigo}}
                                            <td>
                                                {{$dato->nombre}}
                                            </td>

                                            <td class="text-center">
                                                @if ($dato->mes_enero === 'ENTREGADO')
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @elseif ($dato->mes_enero === 'PENDIENTE')
                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                @elseif ($dato->mes_enero === 'SIN REGISTRO')
                                                    <i class="bi bi-circle text-secondary"></i>
                                                @else
                                                    <i class="bi bi-question-circle text-muted"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($dato->mes_febrero === 'ENTREGADO')
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @elseif ($dato->mes_febrero === 'PENDIENTE')
                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                @elseif ($dato->mes_febero === 'SIN REGISTRO')
                                                    <i class="bi bi-circle text-secondary"></i>
                                                @else
                                                    <i class="bi bi-question-circle text-muted"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($dato->mes_marzo === 'ENTREGADO')
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @elseif ($dato->mes_marzo === 'PENDIENTE')
                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                @elseif ($dato->mes_marzo === 'SIN REGISTRO')
                                                    <i class="bi bi-circle text-secondary"></i>
                                                @else
                                                    <i class="bi bi-question-circle text-muted"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($dato->mes_abril === 'ENTREGADO')
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @elseif ($dato->mes_abril === 'PENDIENTE')
                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                @elseif ($dato->mes_abril === 'SIN REGISTRO')
                                                    <i class="bi bi-circle text-secondary"></i>
                                                @else
                                                    <i class="bi bi-question-circle text-muted"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($dato->mes_mayo === 'ENTREGADO')
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @elseif ($dato->mes_mayo === 'PENDIENTE')
                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                @elseif ($dato->mes_mayo === 'SIN REGISTRO')
                                                    <i class="bi bi-circle text-secondary"></i>
                                                @else
                                                    <i class="bi bi-question-circle text-muted"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($dato->mes_junio === 'ENTREGADO')
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @elseif ($dato->mes_junio === 'PENDIENTE')
                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                @elseif ($dato->mes_junio === 'SIN REGISTRO')
                                                    <i class="bi bi-circle text-secondary"></i>
                                                @else
                                                    <i class="bi bi-question-circle text-muted"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($dato->mes_julio === 'ENTREGADO')
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @elseif ($dato->mes_julio === 'PENDIENTE')
                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                @elseif ($dato->mes_julio === 'SIN REGISTRO')
                                                    <i class="bi bi-circle text-secondary"></i>
                                                @else
                                                    <i class="bi bi-question-circle text-muted"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($dato->mes_agosto === 'ENTREGADO')
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @elseif ($dato->mes_agosto === 'PENDIENTE')
                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                @elseif ($dato->mes_agosto === 'SIN REGISTRO')
                                                    <i class="bi bi-circle text-secondary"></i>
                                                @else
                                                    <i class="bi bi-question-circle text-muted"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($dato->mes_septiembre === 'ENTREGADO')
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @elseif ($dato->mes_septiembre === 'PENDIENTE')
                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                @elseif ($dato->mes_septiembre === 'SIN REGISTRO')
                                                    <i class="bi bi-circle text-secondary"></i>
                                                @else
                                                    <i class="bi bi-question-circle text-muted"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($dato->mes_octubre === 'ENTREGADO')
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @elseif ($dato->mes_octubre === 'PENDIENTE')
                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                @elseif ($dato->mes_octubre === 'SIN REGISTRO')
                                                    <i class="bi bi-circle text-secondary"></i>
                                                @else
                                                    <i class="bi bi-question-circle text-muted"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($dato->mes_noviembre === 'ENTREGADO')
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @elseif ($dato->mes_noviembre === 'PENDIENTE')
                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                @elseif ($dato->mes_noviembre === 'SIN REGISTRO')
                                                    <i class="bi bi-circle text-secondary"></i>
                                                @else
                                                    <i class="bi bi-question-circle text-muted"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($dato->mes_diciembre === 'ENTREGADO')
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @elseif ($dato->mes_diciembre === 'PENDIENTE')
                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                @elseif ($dato->mes_diciembre === 'SIN REGISTRO')
                                                    <i class="bi bi-circle text-secondary"></i>
                                                @else
                                                    <i class="bi bi-question-circle text-muted"></i>
                                                @endif
                                            </td>
                                        </tr>

                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Iglesia</th>
                                            <th>Enero</th>
                                            <th>Febrero</th>
                                            <th>Marzo</th>
                                            <th>Abril</th>
                                            <th>Mayo</th>
                                            <th>Junio</th>
                                            <th>Julio</th>
                                            <th>Agosto</th>
                                            <th>Septiembre</th>
                                            <th>Octubre</th>
                                            <th>Noviembre</th>
                                            <th>Diciembre</th>
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