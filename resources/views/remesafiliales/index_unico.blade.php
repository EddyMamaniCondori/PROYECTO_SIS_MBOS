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
              <div class="col-sm-6"><h3 class="mb-0">Registro Remesa de Filial </h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="#">Remesas</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Remesas Filiales</li>
                </ol>
              </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 rounded-3 ">
                                <center><h2 class=" text-primary mt-3">{{ $iglesia->id_iglesia }} {{ $iglesia->nombre }}</h2></center>
                            <div class="card-body">
                                <hr>
                                <p class="mb-1"><strong>Codigo:</strong> {{ $iglesia->codigo }}</p>
                                <p class="mb-1"><strong>Tipo:</strong> {{ $iglesia->tipo }}</p>
                                <p class="mb-1"><strong>Dirección:</strong> {{ $iglesia->direccion ?? 'Sin registrar' }}</p>
                                <p class="mb-1"><strong>Distrito:</strong> {{$distrito }}</p>
                                <p class="mb-0"></p>
                                    <strong>Estado:</strong> 
                                    <span class="badge {{ $iglesia->estado ? 'bg-success' : 'bg-danger' }}">
                                        {{ $iglesia->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('remesas.index_mes', ['mes' => $mes, 'anio' => $anio]) }}" 
                            class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
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
                                            <th>MES - AÑO</th>
                                            <th>CIE</th>
                                            <th>DEP</th>
                                            <th>DOC</th>
                                            <th>Fecha entrega</th>
                                            <th>Fecha limite</th>
                                            <th>estado dias</th>
                                            <th>P / E</th>
                                            <th>observaciones</th>
                                            <th>diezmo</th>
                                            <th>ofrenda</th>
                                            <th>pro templo</th>
                                            <th>fondo local</th>
                                            <th>remesa</th>
                                            <th>gasto</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($resultados as $dato)
                                        <tr>
                                            <td>
                                                {{$dato->nombre_mes}} - {{$dato->anio}}
                                            </td>
                                            <td>
                                                @if ($dato->cierre)
                                                    <i class="bi bi-check-square-fill" style="color: #28a745;"></i>
                                                @else
                                                    <i class="bi bi-file-excel-fill" style="color: red;"></i>
                                                @endif
                                                            
                                            </td>
                                            <td>
                                                @if ($dato->deposito)
                                                    <i class="bi bi-check-square-fill" style="color: #28a745;"></i>
                                                @else
                                                    <i class="bi bi-file-excel-fill" style="color: red;"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($dato->documentacion)
                                                    <i class="bi bi-check-square-fill" style="color: #28a745;"></i>
                                                @else
                                                    <i class="bi bi-file-excel-fill" style="color: red;"></i>
                                                @endif
                                            </td>
                                            <td>
                                                {{$dato->fecha_entrega}}
                                            </td>
                                            <td>
                                                {{$dato->fecha_limite}}
                                            </td>
                                            <td>
                                                {{$dato->estado_dias}}
                                            </td>
                                            <td>
                                                @if ($dato->estado == 'PENDIENTE')
                                                    <label style="color: red;" >PENDIENTE</label>
                                                @else
                                                    <label style="color: green;" >COMPLETADO</label>
                                                @endif
                                            </td>
                                            <td>
                                                {{$dato->observacion}}
                                            </td>
                                            <td>
                                                {{$dato->ofrenda}}
                                            </td>
                                            <td>
                                                {{$dato->diezmo}}
                                            </td>
                                            <td>
                                                {{$dato->pro_templo}}
                                            </td>
                                            <td>
                                                {{$dato->fondo_local}}
                                            </td>
                                            <td>
                                                {{$dato->monto_remesa}}
                                            </td>
                                            <td>
                                                {{$dato->gasto}}
                                            </td>
                                            <td>
                                                <div class="btn-group  justify-content-center" role="group" >
                                                    <!-- Cerrar Mes -->
                                                    <button type="button" 
                                                            class="btn btn-success"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#confirmModal-{{ $dato->id_remesa}}">
                                                        <i class="bi bi-x-circle"></i> Completar
                                                    </button>

                                                </div>
                                            </td>

                                        </tr>

                                             <!-- Modal -->
                                            <div class="modal fade" id="confirmModal-{{ $dato->id_remesa }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <form action="{{ route('remesasfilial.registrar', ['id' => $dato->id_remesa]) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Registro de remesas</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <center><strong style="color: green;"> {{$dato->nombre_mes}} - {{$dato->anio}}</strong></center>
                                                    <input type="hidden" name="id_iglesia" id="id_iglesia" class="form-control" value="{{ $iglesia->id_iglesia }}">
                                                    <input type="hidden" name="distrito" id="distrito" class="distrito" value="{{ $distrito}}">
                                                    <input type="hidden" name="anio" id="anio" value="{{ $anio }}">
                                                    <input type="hidden" name="mes" id="mes" value="{{ $mes }}">
                                                    <hr>
                                                    <label for="fecha_entrega" class="form-label">Fecha de entrega:</label>
                                                    <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control" required value="{{ now()->format('Y-m-d') }}" >
                                                    
                                                    
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="cierre" value="false">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="switchCierre" name="cierre" value="true" 
                                                            @if($dato->cierre) checked @endif>
                                                        <label class="form-check-label" for="switchCierre">Cierre:</label>
                                                    </div>

                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="deposito" value="false">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="switchDeposito" name="deposito" value="true"
                                                            @if($dato->deposito) checked @endif>
                                                        <label class="form-check-label" for="switchDeposito">Depósito:</label>
                                                    </div>

                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="documentacion" value="false">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="switchDocumentacion" name="documentacion" value="true"
                                                            @if($dato->documentacion) checked @endif>
                                                        <label class="form-check-label" for="switchDocumentacion">Documentación:</label>
                                                    </div>


                                                    <div class="mt-3">
                                                        <div class="row">
                                                            <div class="col-3">
                                                                <label for="ofrenda" class="form-label">Ofrenda:</label>
                                                            </div>
                                                            <div class="col-8">
                                                                <input type="number" name="ofrenda" class="ofrenda" class="form-control" step="0.001" min="0" value="{{$dato->ofrenda}}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3">
                                                        <div class="row">
                                                            <div class="col-3">
                                                                <label for="diezmo" class="form-label">Diezmo:</label>
                                                            </div>
                                                            <div class="col-8">
                                                                <input type="number" name="diezmo" class="diezmo" class="form-control" step="0.001" min="0" value="{{$dato->diezmo}}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3">
                                                        <div class="row">
                                                            <div class="col-3">
                                                                <label for="pro_templo" class="form-label">Pro Templo:</label>
                                                            </div>
                                                            <div class="col-8">
                                                                <input type="number" name="pro_templo" class="pro_templo" class="form-control" step="0.001" min="0" value="{{$dato->pro_templo}}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3">
                                                        <div class="row">
                                                            <div class="col-3">
                                                                <label for="pro_templo" class="form-label">Gasto:</label>
                                                            </div>
                                                            <div class="col-8">
                                                                <input type="number" name="gasto" class="gasto" class="form-control" step="0.001" min="0" value="{{$dato->gasto}}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <div class="mt-3">
                                                        <strong><label for="fondo_local" class="form-label" style="color: #1872E7;">Fondo Local (60% Ofrenda + Pro Templo):</label></strong>
                                                        <div class="row">
                                                            <div class="col-3">
                                                             60% Ofrenda = 
                                                            </div>
                                                            <div class="col-8">
                                                                <input type="number" class="porcentaje_ofrenda" class="form-control" min="0" value="0" step="0.001" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-3">
                                                                Pro Templo =
                                                            </div>
                                                            <div class="col-8">
                                                                <input type="number" class="input_pro_templo" class="form-control" min="0" value="0" step="0.001" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-3">
                                                            </div>
                                                            <div class="col-8">
                                                                <hr>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-3">
                                                                TOTAL FONDO LOCAL=
                                                            </div>
                                                            <div class="col-8">
                                                                <input type="number" name="fondo_local" class="fondo_local" class="form-control" min="0" value="0" step="0.01" readonly>
                                                            </div>
                                                        </div> 
                                                    </div>

                                                    <div class="mt-3">
                                                        <strong><label for="fondo_local" class="form-label " style="color: #1872E7;">Remesa MBOS (Diezmo + 40% Ofrenda):</label></strong>
                                                        <div class="row">
                                                            <div class="col-3">
                                                             40% Ofrenda = 
                                                            </div>
                                                            <div class="col-8">
                                                                <input type="number" class="porcentaje_ofrenda_rem" class="form-control" min="0" value="0" step="0.001" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-3">
                                                                Diezmos =
                                                            </div>
                                                            <div class="col-8">
                                                                <input type="number" class="input_diezmo" class="form-control" min="0" value="0" step="0.001" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-3">
                                                            </div>
                                                            <div class="col-8">
                                                                <hr>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-3">
                                                                TOTAL REMESA =
                                                            </div>
                                                            <div class="col-8">
                                                                <input type="number" name="monto_remesa" class="monto_remesa" class="form-control" step="0.01" min="0" value="0" readonly>
                                                            </div>
                                                        </div> 
                                                        
                                                    
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-success">Registrar</button>
                                                </div>
                                                </form>
                                                </div>
                                            </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>MES - AÑO</th>
                                            <th>CIE</th>
                                            <th>DEP</th>
                                            <th>DOC</th>
                                            <th>Fecha entrega</th>
                                            <th>Fecha limite</th>
                                            <th>estado dias</th>
                                            <th>P / E</th>
                                            <th>observaciones</th>
                                            <th>diezmo</th>
                                            <th>ofrenda</th>
                                            <th>pro templo</th>
                                            <th>fondo local</th>
                                            <th>remesa</th>
                                            <th>gasto</th>
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

<script>
$(document).on('input', '.ofrenda, .diezmo, .pro_templo', function() {

    let modal = $(this).closest('.modal'); // ← Modal actual

    let ofrenda = parseFloat(modal.find('.ofrenda').val()) || 0;
    let diezmo = parseFloat(modal.find('.diezmo').val()) || 0;
    let proTemplo = parseFloat(modal.find('.pro_templo').val()) || 0;

    // Evitar negativos
    if (ofrenda < 0) ofrenda = 0;
    if (diezmo < 0) diezmo = 0;
    if (proTemplo < 0) proTemplo = 0;

    // Calculos
    let p60 = ofrenda * 0.6;
    let p40 = ofrenda * 0.4;

    let fondoLocal = p60 + proTemplo;
    let remesa = diezmo + p40;

    // Setear valores al modal actual
    modal.find('.porcentaje_ofrenda').val(p60.toFixed(2));
    modal.find('.input_pro_templo').val(proTemplo.toFixed(2));
    modal.find('.porcentaje_ofrenda_rem').val(p40.toFixed(2));
    modal.find('.input_diezmo').val(diezmo.toFixed(2));
    modal.find('.fondo_local').val(fondoLocal.toFixed(2));
    modal.find('.monto_remesa').val(remesa.toFixed(2));

});
</script>


@endpush