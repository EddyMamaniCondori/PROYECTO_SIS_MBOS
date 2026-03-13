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
                                <center><h2 class=" text-primary mt-3">{{ $iglesia->codigo }} {{ $iglesia->nombre }}</h2></center>
                            <div class="card-body">
                                <hr>
                                <p class="mb-1"><strong>Codigo:</strong> {{ $iglesia->codigo }}  </p>
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
                                            <div class="modal fade" id="confirmModal-{{ $dato->id_remesa }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg border-0">
                                                    <div class="modal-content shadow-lg">
                                                        <form action="{{ route('remesasfilial.registrar', ['id' => $dato->id_remesa]) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title">
                                                                    <i class="bi bi-pencil-square me-2"></i> Registro de Remesa: {{ $dato->nombre_mes }} - {{ $dato->anio }}
                                                                </h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body p-0"> <div class="row g-0">
                                                                    <div class="col-md-6 p-4 border-end">
                                                                        <h6 class="text-uppercase fw-bold text-primary mb-4 border-bottom pb-2">Datos de Registro</h6>
                                                                        
                                                                        <input type="hidden" name="id_iglesia" value="{{ $iglesia->id_iglesia }}">
                                                                        <input type="hidden" name="distrito" value="{{ $distrito }}">
                                                                        <input type="hidden" name="anio" value="{{ $anio }}">
                                                                        <input type="hidden" name="mes" value="{{ $mes }}">

                                                                        <div class="mb-3">
                                                                            <label class="form-label fw-bold small">Fecha de Entrega:</label>
                                                                            <input type="date" name="fecha_entrega" class="form-control" required value="{{ now()->format('Y-m-d') }}">
                                                                        </div>

                                                                        <div class="d-flex gap-3 mb-4">
                                                                            <div class="form-check form-switch">
                                                                                <input class="form-check-input" type="checkbox" role="switch" id="switchCierre-{{ $dato->id_remesa }}" name="cierre" @if($dato->cierre) checked @endif>
                                                                                <label class="form-check-label small" for="switchCierre-{{ $dato->id_remesa }}">CIE</label>
                                                                            </div>
                                                                            <div class="form-check form-switch">
                                                                                <input class="form-check-input" type="checkbox" role="switch" id="switchDep-{{ $dato->id_remesa }}" name="deposito"  @if($dato->deposito) checked @endif>
                                                                                <label class="form-check-label small" for="switchDep-{{ $dato->id_remesa }}">DEP</label>
                                                                            </div>
                                                                            <div class="form-check form-switch">
                                                                                <input class="form-check-input" type="checkbox" role="switch" id="switchDoc-{{ $dato->id_remesa }}" name="documentacion" @if($dato->documentacion) checked @endif>
                                                                                <label class="form-check-label small" for="switchDoc-{{ $dato->id_remesa }}">DOC</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row g-2">
                                                                            <div class="col-6 mb-3">
                                                                                <label class="form-label small fw-bold text-secondary">Ofrenda:</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-text bg-light">Bs</span>
                                                                                    <input type="number" name="ofrenda" class="ofrenda form-control border-primary fw-bold" step="0.01" value="{{ $dato->ofrenda }}" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6 mb-3">
                                                                                <label class="form-label small fw-bold text-secondary">Diezmo:</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-text bg-light">Bs</span>
                                                                                    <input type="number" name="diezmo" class="diezmo form-control border-primary fw-bold" step="0.01" value="{{ $dato->diezmo }}" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6 mb-3">
                                                                                <label class="form-label small fw-bold text-secondary">Pro Templo:</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-text bg-light">Bs</span>
                                                                                    <input type="number" name="pro_templo" class="pro_templo form-control border-primary fw-bold" step="0.01" value="{{ $dato->pro_templo }}" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6 mb-3">
                                                                                <label class="form-label small fw-bold text-secondary">Gasto Realizado:</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-text bg-light text-danger"><i class="bi bi-dash-circle"></i></span>
                                                                                    <input type="number" name="gasto" class="gasto form-control border-danger" step="0.01" value="{{ $dato->gasto }}" required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 p-4 bg-light">
                                                                        <h6 class="text-uppercase fw-bold text-success mb-4 border-bottom pb-2">Distribución de Fondos</h6>
                                                                        
                                                                        <div class="mb-3">
                                                                            <label class="small text-muted d-block mb-1">Fondo Local (60% Of + Pro Templo)</label>
                                                                            <input type="number" name="fondo_local" class="fondo_local form-control-plaintext fs-4 fw-bold text-dark p-0" readonly>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label class="small text-muted d-block mb-1">Remesa MBOS (40% Of + Diezmo)</label>
                                                                            <input type="number" name="monto_remesa" class="monto_remesa form-control-plaintext fs-4 fw-bold text-primary p-0" readonly>
                                                                        </div>

                                                                        <hr>

                                                                        <div class="card border-0 shadow-sm mt-4 bg-white">
                                                                            <div class="card-body p-3">
                                                                                <div class="d-flex justify-content-between mb-2">
                                                                                    <span class="text-secondary small">Total Ingresos:</span>
                                                                                    <input type="text" class="total_general border-0 fw-bold text-end w-50" readonly>
                                                                                </div>
                                                                                <div class="d-flex justify-content-between">
                                                                                    <span class="text-secondary small">Saldo Neto (Ingresos - Gasto):</span>
                                                                                    <input type="text" class="saldo_neto border-0 fw-bold text-end w-50" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="mt-4 small text-muted italic">
                                                                            <i class="bi bi-info-circle me-1"></i> Los valores se calculan automáticamente basándose en la normativa institucional.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer bg-white border-top-0 p-3">
                                                                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-success px-4 shadow-sm">
                                                                    <i class="bi bi-cloud-upload me-1"></i> Registrar Remesa
                                                                </button>
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

    // --- 1. FUNCIÓN MAESTRA DE CÁLCULOS ---
    function ejecutarCalculos(modal) {
        let ofrenda = parseFloat(modal.find('.ofrenda').val()) || 0;
        let diezmo = parseFloat(modal.find('.diezmo').val()) || 0;
        let proTemplo = parseFloat(modal.find('.pro_templo').val()) || 0;
        let gasto     = parseFloat(modal.find('.gasto').val()) || 0;

        // Cálculos
        let p60 = ofrenda * 0.6;
        let p40 = ofrenda * 0.4;
        let fondoLocal = p60 + proTemplo;
        let remesa = diezmo + p40;

        // --- NUEVOS CÁLCULOS VISUALES ---
        let totalGeneral = diezmo + ofrenda + proTemplo;
        let saldoNeto = totalGeneral - gasto;
        

        // Asignar valores al modal actual
        modal.find('.porcentaje_ofrenda').val(p60.toFixed(2));
        modal.find('.input_pro_templo').val(proTemplo.toFixed(2));
        modal.find('.porcentaje_ofrenda_rem').val(p40.toFixed(2));
        modal.find('.input_diezmo').val(diezmo.toFixed(2));
        modal.find('.fondo_local').val(fondoLocal.toFixed(2));
        modal.find('.monto_remesa').val(remesa.toFixed(2));

        // --- SETEAR CAMPOS VISUALES ---
        modal.find('.total_general').val(totalGeneral.toFixed(2));
        modal.find('.saldo_neto').val(saldoNeto.toFixed(2));
        
        // Opcional: Si el saldo es negativo, ponerlo en rojo
        if(saldoNeto < 0) {
            modal.find('.saldo_neto').addClass('text-danger').removeClass('text-success');
        } else {
            modal.find('.saldo_neto').addClass('text-success').removeClass('text-danger');
        }

    }


    // --- 2. DISPARADOR AL ESCRIBIR ---
    $(document).on('input', '.ofrenda, .diezmo, .pro_templo, .gasto', function() {
        let modal = $(this).closest('.modal');
        ejecutarCalculos(modal);
    });

    // --- 3. DISPARADOR AL ABRIR EL MODAL (La solución a tu problema) ---
    $(document).on('show.bs.modal', '.modal', function() {
        let modal = $(this);
        // Pequeño delay para asegurar que los valores del value de Blade ya estén cargados
        setTimeout(() => {
            ejecutarCalculos(modal);
        }, 100); 
    });


</script>


@endpush