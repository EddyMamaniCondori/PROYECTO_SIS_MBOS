@extends('template')


@section('title', 'Remesas')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select .dropdown-menu li {
            display: none;
        }

        /* Muestra las opciones solo cuando el buscador tiene texto */
        .bootstrap-select .bs-searchbox input:not(:placeholder-shown) ~ .dropdown-menu li,
        .bootstrap-select.show-search-results .dropdown-menu li {
            display: block;
        }

    .custom-scroll-container {
        /* Altura fija para que el scroll sea obligatorio si hay más de 3-4 alertas */
        height: 350px !important; 
        max-height: 350px !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        padding-right: 10px !important;
    }

    /* Estilo de la barra de scroll para que se vea claramente */
    .custom-scroll-container::-webkit-scrollbar {
        width: 8px;
    }

    .custom-scroll-container::-webkit-scrollbar-track {
        background: #f8f9fa;
        border-radius: 10px;
    }

    .custom-scroll-container::-webkit-scrollbar-thumb {
        background: #adb5bd; /* Color gris visible */
        border-radius: 10px;
        border: 2px solid #f8f9fa;
    }

    .custom-scroll-container::-webkit-scrollbar-thumb:hover {
        background: #6c757d;
    }

    .badge-wrap {
        white-space: normal !important; /* Permite que el texto baje */
        max-width: 110px;               /* Ajusta este valor según lo ancho que lo quieras */
        display: inline-block;
        line-height: 1.2;
        padding: 5px 8px;
    }
</style>

@endpush

@section('content')

        <x-alerts/>
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 pb-1"><h3 class="mb-0">Remesas </h3></div>
                <div class="col-sm-3 text-end pb-1"> 
                    <a href="{{ route('remesas.index_mes', ['mes' => $mes, 'anio' => $anio]) }}" 
                        class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
                <div class="col-sm-3">
                    <select id="buscadorIglesia" class="selectpicker form-control" data-live-search="true" title="Seleccione una Iglesia...">
                        @foreach($iglesias as $ig)
                            <option value="{{ $ig->id_iglesia }}">{{ $ig->codigo }} - {{ $ig->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                

                <div class="row">
                    <div class="col-lg-4 col-md-6 ">
                        <div class="card shadow-sm border rounded-3 ">
                            <div class="card-header border-0 pt-2 pb-0">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fas fa-church fa-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-bold"><strong class="text-primary">Cod: {{ $iglesia->codigo }} </strong> - {{ $iglesia->nombre }}</h4>
                                        <span class="text-muted small">Detalles de la Iglesia</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body  p-3">
                                <hr class="text-muted opacity-25 p-0 m-0">
                                <div class="row g-1">
                                    <div class="col-sm-6">
                                        <label class="text-muted d-block small uppercase fw-bold">Tipo de Iglesia</label>
                                        <p class="mb-0">
                                            <i class="fas fa-tags text-primary me-2"></i>{{ $iglesia->tipo ?? 'No definido' }}
                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="text-muted d-block small uppercase fw-bold">Estado</label>
                                        <span class="badge {{ $iglesia->estado ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border px-3">
                                            {{ $iglesia->estado ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-muted d-block small uppercase fw-bold">Dirección</label>
                                        <p class="mb-0">
                                            <i class="bi bi-geo-alt-fill text-danger"></i>{{ $iglesia->ciudad ?? 'Sin registro' }} - {{ $iglesia->zona ?? 'Sin registro' }}
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-muted d-block small uppercase fw-bold">Lugar</label>
                                        <p class="mb-0">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i>{{ $iglesia->lugar?? 'Sin lugar' }} 
                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="text-muted d-block small uppercase fw-bold">Distrito</label>
                                        <p class="mb-0 fw-bold text-secondary">
                                            <i class="fas fa-layer-group me-2"></i>{{ $dato->nombre_distrito ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="text-muted d-block small uppercase fw-bold">Pastor Distrital</label>
                                        <p class="mb-0">
                                            <i class="fas fa-user-tie me-2 text-primary"></i>{{ $dato->nombre_pas ?? 'Sin asignar' }}
                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="text-muted d-block small uppercase fw-bold">Encargado</label>
                                        <p class="mb-0">
                                            <i class="fas fa-user-tie me-2 text-primary"></i>{{ $dato->nombre_resp ?? 'Sin asignar' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 p-0">
                        <div class="card shadow-sm border rounded-3  border-primary border-opacity-10">
                            <div class="card-header border-0">
                                <h5 class="card-title fw-bold text-primary mb-0">
                                    <i class="fas fa-wallet me-2"></i>Fondo Local
                                </h5>
                            </div>
                            <div class="card-body p-3 pt-0">
                                <div class="text-center p-1 mb-1 rounded-3" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px dashed #cbd5e1;">
                                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 1px;">Fondo Local Actual</span>
                                    <h3 id="big_saldo_actual" class="fw-black text-primary mb-0">Bs {{ number_format($iglesia->fondo_local, 2) }}</h3>
                                </div>

                                <label class="text-muted d-block small uppercase fw-bold mb-2" style="font-size: 0.65rem;">Histórico 4 Meses</label>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.75rem;">
                                        <thead class="table-light">
                                            <tr class="text-muted">
                                                <th>Mes</th>
                                                <th class="text-end"> FL Ant.</th>
                                                <th class="text-end"> Fondo Local</th>
                                                <th class="text-end text-danger">Gasto</th>
                                                <th class="text-end fw-bold">Final</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabla_historial_fondo">
                                            <tr>
                                                <td colspan="5" class="text-center text-muted small">Cargando historial...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 ">
                        <div class="card shadow-sm border rounded-3">
                            <div class="card-header border-0 pt-3">
                                <h5 class="card-title fw-bold text-primary mb-0">
                                    <i class="fas fa-bell me-2"></i>Centro de Alertas
                                </h5>
                            </div>
                            <div class="card-body pt-0 scroll-alertas" id="body-alertas" style="height: 235px; overflow-y: scroll !important;">
                                <div id="contenedor-alertas">
                                    </div>
                            </div>
                            <div id="sin-alertas" class="text-center py-5 text-muted d-none">
                                <div class="mb-3">
                                    <i class="fas fa-check-circle fa-4x text-success opacity-50"></i>
                                </div>
                                <h6 class="fw-bold text-dark">¡Todo al día!</h6>
                                <p class="small">No tienes alertas pendientes para esta iglesia.</p>
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
                            <div class="card-header d-flex justify-content-between align-items-center py-3">
                                <div class="fw-bold">
                                    <i class="fas fa-table me-2"></i>
                                    Tabla de Remesas 
                                </div>

                                <div class="dropdown ms-auto">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle shadow-sm" type="button" id="dropdownConfig" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear-fill me-1"></i> Configurar Vista
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end p-3 shadow border-0" aria-labelledby="dropdownConfig" style="min-width: 250px;">
                                        <li class="dropdown-header text-uppercase small fw-bold">Mostrar/Ocultar Columnas</li>
                                        <li><hr class="dropdown-divider"></li>
                                        
                                        
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="5" id="col5" checked>
                                                <label class="form-check-label" for="col5">Fecha entrega</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="6" id="col6" checked>
                                                <label class="form-check-label" for="col6">Fecha limite</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="7" id="col7" checked>
                                                <label class="form-check-label" for="col7">Estado de dias</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="9" id="col9" checked>
                                                <label class="form-check-label" for="col9">Observaciones</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="10" id="col10" checked>
                                                <label class="form-check-label" for="col10">Ofrenda</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="11" id="col11" checked>
                                                <label class="form-check-label" for="col11">Diezmo</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="12" id="col12" checked>
                                                <label class="form-check-label" for="col12">Pro Templo</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                                <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Periodo</th>
                                            <th>CIE</th>
                                            <th>DEP</th>
                                            <th>DOC</th>
                                            <th>SCAN</th>
                                            <th>Fecha entrega</th> <!--end::4-->
                                            <th>Fecha limite</th>
                                            <th>estado dias</th>
                                            <th>estado</th>
                                            <th>observaciones</th>
                                            <th>Ofrenda</th><!--end::9-->
                                            <th>Diezmo</th>
                                            <th>Pro Templo</th><!--end::11-->
                                            <th>Fondo Local</th>
                                            <th>Remesa</th>
                                            <th>Gasto</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>


            <div class="card-body">
          </div>
          <!--end::Container-->
        </div>
<!--MOdal de editar Alerta-->
<div class="modal fade" id="modalEditarAlerta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Alerta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id_remesa">
                <input type="hidden" id="edit_index">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Periodo</label>
                    <input type="text" id="edit_periodo" class="form-control bg-light" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Estado</label>
                    <select id="edit_estado" class="form-select">
                        <option value="IMPORTANTE">IMPORTANTE (Rojo)</option>
                        <option value="AVISO">AVISO (Amarillo)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Mensaje de la Alerta</label>
                    <textarea id="edit_mensaje" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEdicionAlerta()">
                    <i class="fas fa-save me-1"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>
<!--Modal-Crear alerta-->
<div class="modal fade" id="modalNuevaAlerta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-bell-plus me-2"></i>Nueva Alerta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="new_id_remesa">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Periodo Correspondiente</label>
                    <input type="text" id="new_periodo" class="form-control bg-light" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nivel de Importancia</label>
                    <select id="new_estado" class="form-select">
                        <option value="AVISO" selected>AVISO (Amarillo)</option>
                        <option value="IMPORTANTE">IMPORTANTE (Rojo)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Mensaje / Observación</label>
                    <textarea id="new_mensaje" class="form-control" rows="3" placeholder="Escriba el detalle de la alerta..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarNuevaAlerta()">
                    <i class="fas fa-save me-1"></i> Crear Alerta
                </button>
            </div>
        </div>
    </div>
</div>
<!--Modal-Completar una remesa-->
<div class="modal fade" id="modalFilial" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered border-0">
        <div class="modal-content shadow-lg">
            <form id="formRemesaFilial" method="POST">
                @csrf
                <input type="hidden" name="id_remesa" id="f_id_remesa">
                <input type="hidden" name="id_iglesia" value="{{ $iglesia->id_iglesia }}">
                <input type="hidden" name="distrito" value="{{ $dato->nombre_distrito ?? '' }}">
                <input type="hidden" name="anio" id="f_anio_val">
                <input type="hidden" name="mes" id="f_mes_val">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i> 
                        Registro de Remesa: <span id="f_periodo_txt"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-md-6 p-4 border-end">
                            <h6 class="text-uppercase fw-bold text-primary mb-4 border-bottom pb-2">Datos de Registro</h6>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Fecha y Hora de entrega:</label>
                                <input type="datetime-local" name="fecha_entrega" id="f_fecha_entrega" class="form-control" required>
                                <div id="f_puntualidad_feedback" class="small mt-1 fw-bold"></div>
                            </div>

                            <div class="d-flex gap-3 mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="f_cierre" name="cierre">
                                    <label class="form-check-label small" for="f_cierre">CIE</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="f_deposito" name="deposito">
                                    <label class="form-check-label small" for="f_deposito">DEP</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="f_documentacion" name="documentacion">
                                    <label class="form-check-label small" for="f_documentacion">DOC</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="f_escaneado" name="escaneado">
                                    <label class="form-check-label small" for="f_escaneado">SCAN</label>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-bold text-secondary">Ofrenda:</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">Bs</span>
                                        <input type="number" name="ofrenda" id="f_ofrenda" class="calc-input form-control border-primary fw-bold" step="0.01" value="0" required>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-bold text-secondary">Diezmo:</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">Bs</span>
                                        <input type="number" name="diezmo" id="f_diezmo" class="calc-input form-control border-primary fw-bold" step="0.01" value="0" required>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-bold text-secondary">Pro Templo:</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">Bs</span>
                                        <input type="number" name="pro_templo" id="f_pro_templo" class="calc-input form-control border-primary fw-bold" step="0.01" value="0" required>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-bold text-secondary">Gasto Realizado:</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-danger"><i class="bi bi-dash-circle"></i></span>
                                        <input type="number" name="gasto" id="f_gasto" class="calc-input form-control border-danger" step="0.01" value="0" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-bold small">Observaciones:</label>
                                <textarea name="observacion" id="f_observacion" class="form-control" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="col-md-6 p-4 bg-light">
                            <h6 class="text-uppercase fw-bold text-success mb-4 border-bottom pb-2">Distribución de Fondos</h6>
                            
                            <div class="mb-3">
                                <label class="small text-muted d-block mb-1">Fondo Local (60% Of + Pro Templo)</label>
                                <input type="number" name="fondo_local" id="f_fondo_local" class="form-control-plaintext fs-4 fw-bold text-dark p-0" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="small text-muted d-block mb-1">Remesa MBOS (40% Of + Diezmo)</label>
                                <input type="number" name="monto_remesa" id="f_monto_remesa" class="form-control-plaintext fs-4 fw-bold text-primary p-0" readonly>
                            </div>

                            <hr>

                            <div class="card border-0 shadow-sm mt-4 bg-white">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-secondary small">Total Ingresos:</span>
                                        <input type="text" id="f_total_general" class="border-0 fw-bold text-end w-50" readonly>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-secondary small">Saldo Neto:</span>
                                        <input type="text" id="f_saldo_neto" class="border-0 fw-bold text-end w-50" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-white border-top-0 p-3">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" onclick="guardarRemesaFilial()" class="btn btn-success px-4 shadow-sm">
                        <i class="bi bi-cloud-upload me-1"></i> Registrar Remesa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


        @endsection


@push('js')
    <!--JQUERY-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!--data table-->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar el select (si no se hace automáticamente)
            $('.selectpicker').selectpicker();

            // Detectar el cambio de selección
            $('#buscadorIglesia').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
                const idIglesia = $(this).val(); // Obtenemos el ID seleccionado
                
                if (idIglesia) {
                    // Traemos las variables de PHP a JS
                    const anioActual = "{{ $anio }}";
                    const mesActual = "{{ $mes }}";

                    // Construimos la URL con los 3 parámetros
                    const url = `/remesas/iglesia/${idIglesia}?anio=${anioActual}&mes=${mesActual}`;

                    // Redirigir
                    window.location.href = url;
                }
            });
            $('.bs-searchbox input').on('keyup', function() {
                let value = $(this).val();
                let $container = $(this).closest('.bootstrap-select');
                
                if (value.length > 0) {
                    $container.addClass('show-search-results');
                } else {
                    $container.removeClass('show-search-results');
                }
            });
        });
    </script>
    <script>
    $(document).ready(function() {

        cargarAlertas("{{ $iglesia->id_iglesia }}");
        actualizarHistorialFondo();

        var table = $('#example').DataTable({
            ajax: {
                url: "{{ route('remesas.iglesia.index', $iglesia->id_iglesia) }}",
                type: 'GET',
                dataSrc: 'data' // Laravel envía { "data": [...] }
            },
            columns: [
                { 
                    data: null, 
                    render: function(data, type, row) {
                        return `${row.nombre_mes} - ${row.anio}`;
                    }
                },  
                { 
                    data: 'cierre',
                    width: '10px',
                    render: function(data) {
                        return data ? '<i class="bi bi-check-square-fill text-success"></i>' : '<i class="bi bi-file-excel-fill text-danger"></i>';
                    }
                },
                { 
                    data: 'deposito',
                    width: '10px',
                    render: function(data) {
                        return data ? '<i class="bi bi-check-square-fill text-success"></i>' : '<i class="bi bi-file-excel-fill text-danger"></i>';
                    }
                },
                { 
                    data: 'documentacion',
                    width: '10px',
                    render: function(data) {
                        return data ? '<i class="bi bi-check-square-fill text-success"></i>' : '<i class="bi bi-file-excel-fill text-danger"></i>';
                    }
                },
                { 
                    data: 'escaneado',
                    width: '10px',
                    render: function(data) {
                        return data ? '<i class="bi bi-check-square-fill text-success"></i>' : '<i class="bi bi-file-excel-fill text-danger"></i>';
                    }
                },
                { data: 'fecha_entrega' },
                { data: 'fecha_limite' },
                { data: 'estado_dias', // Nombre de la columna que viene del JSON
                    render: function(data, type, row) {
                        // 1. Si el dato es nulo, vacío o exactamente "0" (no entregado)
                        if (!data || data == "0") {
                            return `<span class="badge bg-secondary badge-wrap">Sin entregar</span>`;
                        }

                        // 2. Definimos el color por defecto (Gris)
                        let colorClass = 'bg-secondary';

                        // 3. Lógica de colores basada en el texto
                        // Si contiene "adelanto" o "0 día(s) de retraso" -> Verde (success)
                        if (data.includes('adelanto') || data.includes(' 0 día(s) de retraso')) {
                            colorClass = 'bg-success';
                        } 
                        // Si contiene "retraso" (y no es el caso del 0 anterior) -> Rojo (danger)
                        else if (data.includes('retraso')) {
                            colorClass = 'bg-danger';
                        }
                        // 4. Retornamos el badge con el color decidido
                        return `<span class="badge ${colorClass} badge-wrap">${data}</span>`;
                    }
                },
                { 
                    data: 'estado',
                    render: function(data) {
                        let color = data === 'PENDIENTE' ? 'red' : (data === 'ENTREGADO' ? 'green' : 'orange');
                        return `<strong><span style="color: ${color}">${data}</span></strong>`;
                    }
                },
                { data: 'observacion' },
                { data: 'ofrenda' },
                { data: 'diezmo' },
                { data: 'pro_templo' },
                { data: 'fondo_local' },
                { data: 'monto_remesa' },
                { data: 'gasto' },
                {
                    data: null,
                    render: function(data) {
                        let urlRegistro = "{{ route('remesas.registro_semanas', ':id') }}";
                        // 2. Reemplazamos el placeholder con el valor real de JS
                        urlRegistro = urlRegistro.replace(':id', data.id_remesa);
                        let mesVal = "{{ $mes }}"; 
                        let anioVal = "{{ $anio }}";
                        let urlReporte = "{{ route('remesas.reporte_pdf', ':id') }}".replace(':id', data.id_remesa);

                        urlRegistro += `?mes=${encodeURIComponent(mesVal)}&anio=${anioVal}`;


                        const periodo = `${data.nombre_mes} - ${data.anio}`;
                        // Botones de acción
                        let botonSemanas = '';
                        let botonPDF = '';
                        let estadoDet = parseInt(data.sw_det_semana);
                        let estado = data.estado;
                        if(estado === 'ENTREGADO'){
                            botonPDF = `<button type="button" 
                                    class="btn btn-secondary btn-sm rounded-circle shadow-sm" 
                                    style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                    onclick="prepararReporte(${data.id_remesa})"
                                    data-bs-toggle="tooltip" 
                                    title="Generar Reporte PDF">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </button>`;
                        }

                        if (estadoDet === 0) {
                            // ESTADO 0: No tiene datos (Botón gris claro o bordeado para invitar a crear)
                            botonSemanas = `
                                <a href="${urlRegistro}" class="btn btn-outline-warning btn-sm rounded-circle shadow-sm" 
                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                data-bs-toggle="tooltip" title="Iniciar Registro de Semanas">
                                    <i class="bi bi-calendar-plus"></i>
                                </a>
                                <button type="button" 
                                    class="btn btn-success btn-sm rounded-circle" 
                                    onclick="editarRemesa('${data.id_remesa}')"
                                    title="Editar/Completar Remesa">
                                    <i class="bi bi-check-lg"></i>
                                </button>  
                                `;
                        } else if (estadoDet === 1) {
                            // ESTADO 1: Tiene datos y se puede EDITAR
                            botonSemanas = `
                                <a href="${urlRegistro}" class="btn btn-warning btn-sm rounded-circle shadow-sm" 
                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                data-bs-toggle="tooltip" title="Editar Semanas Registradas">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                
                                `;
                        } else {
                            // ESTADO 2: Tiene datos pero está BLOQUEADO (Solo ver)
                            botonSemanas = `
                                <a href="${urlRegistro}" class="btn btn-secondary btn-sm rounded-circle shadow-sm opacity-75" 
                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                data-bs-toggle="tooltip" title="Registro Bloqueado (Solo lectura)">
                                    <i class="bi bi-eye"></i>
                                </a>
                                `;
                        }

                        return `
                            <div class="btn-group gap-2">
                                ${botonSemanas}
                                ${botonPDF} 
                                <button type="button" 
                                    class="btn btn-primary btn-sm rounded-circle shadow-sm" 
                                    style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                    onclick="abrirModalNuevaAlerta(${data.id_remesa}, '${periodo}')"
                                    data-bs-toggle="tooltip" 
                                    title="Añadir Alerta">
                                    <i class="bi bi-bell"></i>
                                </button>

                                
                                
                            </div>
                        `;
                    }
                }
            ],
            createdRow: function(row, data) {
                if (data.estado === 'ENTREGADO') $(row).css('background-color', '#d4edda');
                else if (data.estado === 'REGISTRADO EN ACMS') $(row).css('background-color', '#ebdbc2');
                if (data.tipo_igle === 'Filial') $(row).css('color', 'red');
                $(row).attr('id', 'fila-' + data.id_remesa);
            },
            scrollX: true,
            stateSave: true,
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
        


        // *********************************************************
        //              ocultar y mostar columnas
        // **********************************************************"
        $('.toggle-vis').on('change', function (e) {
            // Obtenemos el índice de la columna desde el 'value' del check
            var columnIdx = $(this).val();
            
            // Obtenemos la columna de la instancia de DataTable
            var column = table.column(columnIdx);

            // Cambiamos la visibilidad (si estaba oculta la muestra, y viceversa)
            column.visible(!column.visible());
        });
        $('.dropdown-menu').on('click', function(e) {
            e.stopPropagation();
        });
    });

    // *********************************************************
        //             MODAL PARA PREGUNTAR CUANTOS MESES EL REPORTE
        // **********************************************************"
        function prepararReporte(id) {
            Swal.fire({
                title: 'Generar Reporte',
                text: '¿Cuántos meses desea incluir en el informe? (Hacia atrás desde este mes)',
                icon: 'question',
                input: 'select',
                inputOptions: {
                    1: 'Solo este mes (Detalle Semanal)',
                    2: 'Últimos 2 meses',
                    3: 'Últimos 3 meses (Trimestral)',
                    4: 'Últimos 4 meses',
                    5: 'Últimos 5 meses',
                    6: 'Últimos 6 meses (Semestral)',
                    7: 'Últimos 7 meses',
                    8: 'Últimos 8 meses',
                    9: 'Últimos 9 meses',
                    10: 'Últimos 10 meses',
                    11: 'Últimos 11 meses',
                    12: 'Últimos 12 meses (Anual)'
                },
                inputValue: 1,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-filetype-pdf"></i> Generar PDF',
                cancelButtonText: '<i class="bi bi-x"></i> Cancelar',
                confirmButtonColor: '#0d47a1',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Construimos la URL con el parámetro 'meses'
                    let url = "{{ route('remesas.reporte_pdf', ':id') }}".replace(':id', id);
                    window.open(`${url}?cantidad_meses=${result.value}`, '_blank');
                }
            });
        }


    function cargarAlertas(id_iglesia) {
        const bodyAlertas = $('#body-alertas');
        const contenedor = $('#contenedor-alertas');
        const vistaVacia = $('#sin-alertas');

        // Estado inicial: Limpiar y mostrar que está cargando
        contenedor.html(`
            <div id="alerta-cargando" class="text-center py-4 text-muted">
                <div class="spinner-border spinner-border-sm text-warning me-2" role="status"></div>
                <span>Actualizando...</span>
            </div>
        `);
        
        $.get(`/alertas/${id_iglesia}`, function(alertas) {
            if (!alertas || alertas.length === 0) {
                contenedor.empty();
                bodyAlertas.addClass('d-none'); // Usar solo d-none para consistencia
                vistaVacia.removeClass('d-none');
            } else {
                vistaVacia.addClass('d-none');
                bodyAlertas.removeClass('d-none');
                
                let html = '';
                alertas.forEach(alerta => {
                    let esRojo = alerta.estado === 'IMPORTANTE';
                    let bg = esRojo ? 'bg-danger-subtle' : 'bg-warning-subtle';
                    let border = esRojo ? 'border-danger' : 'border-warning';
                    let icono = esRojo ? 'fa-exclamation-circle' : 'fa-exclamation-triangle';
                    let textoIcono = esRojo ? 'text-danger' : 'text-warning';

                    html += `
                        <div class="alert ${bg} border-0 border-start border-4 ${border} shadow-sm p-2 mb-2 rounded-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center" style="min-width: 0;">
                                    <div class="${textoIcono} me-2 d-flex align-items-center justify-content-center" style="width: 30px;">
                                        <i class="fas ${icono} fa-lg"></i>
                                    </div>
                                    <div style="min-width: 0;">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold text-dark me-2" style="font-size: 0.85rem;">${alerta.periodo}</span>
                                            <span class="badge ${bg} ${textoIcono} border border-${border.split('-')[1]} py-0 px-1" style="font-size: 0.65rem;">
                                                ${alerta.estado}
                                            </span>
                                        </div>
                                        <div class="text-muted small">${alerta.mensaje}</div>
                                    </div>
                                </div>
                                <div class="ms-2 d-flex">
                                    <button class="btn btn-sm text-primary p-1" onclick="abrirModalEditarAlerta(${alerta.id_remesa}, ${alerta.index}, '${alerta.estado}', '${alerta.mensaje}', '${alerta.periodo}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm text-success p-1" onclick="solucionarAlerta(${alerta.id_remesa}, ${alerta.index})">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>`;
                });
                contenedor.html(html);
            }
        }).fail(function() {
            contenedor.html('<div class="text-danger small text-center py-3">Error al cargar alertas</div>');
        });
    }

    // 1. Abrir el modal y cargar los datos
    function abrirModalEditarAlerta(id_remesa, index, estado, mensaje, periodo) {
        $('#edit_id_remesa').val(id_remesa);
        $('#edit_index').val(index);
        $('#edit_periodo').val(periodo);
        $('#edit_estado').val(estado);
        $('#edit_mensaje').val(mensaje);
        
        $('#modalEditarAlerta').modal('show');
    }

    // 2. Enviar la edición al servidor
    function guardarEdicionAlerta() {
        const data = {
            id_remesa: $('#edit_id_remesa').val(),
            index: $('#edit_index').val(),
            estado: $('#edit_estado').val(),
            mensaje: $('#edit_mensaje').val(),
            _token: "{{ csrf_token() }}" // No olvides el token de seguridad
        };

        $.post("{{ route('alertas.update') }}", data, function(res) {
            $('#modalEditarAlerta').modal('hide');
            // Recargamos solo la sección de alertas
            cargarAlertas("{{ $iglesia->id_iglesia }}");
            toastr.success('Alerta actualizada correctamente');
        });
    }

    // 3. "Solucionar" (Eliminar del JSON)
    function solucionarAlerta(id_remesa, index) {
        Swal.fire({
            title: '¿Marcar como solucionada?',
            text: "Esta alerta se eliminará permanentemente de la lista.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754', // Color verde (success)
            cancelButtonColor: '#6c757d', // Color gris (secondary)
            confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Sí, solucionar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true // Pone el botón de cancelar a la izquierda
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostramos un estado de carga mientras se procesa el AJAX
                Swal.fire({
                    title: 'Procesando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.post("{{ route('alertas.delete') }}", {
                    id_remesa: id_remesa,
                    index: index,
                    _token: "{{ csrf_token() }}"
                })
                .done(function(response) {
                    // Cerramos el cargando y mostramos éxito
                    Swal.fire({
                        title: '¡Solucionado!',
                        text: 'La alerta ha sido archivada correctamente.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Recargamos la lista de alertas
                    cargarAlertas("{{ $iglesia->id_iglesia }}");
                })
                .fail(function(jqXHR) {
                    console.error("Error:", jqXHR.responseText);
                    Swal.fire(
                        'Error',
                        'No se pudo eliminar la alerta. Intente de nuevo.',
                        'error'
                    );
                });
            }
        });
    }
    function abrirModalNuevaAlerta(id_remesa, periodo) {
        // Limpiar campos previos
        $('#new_id_remesa').val(id_remesa);
        $('#new_periodo').val(periodo);
        $('#new_mensaje').val('');
        $('#new_estado').val('AVISO');
        
        $('#modalNuevaAlerta').modal('show');
    }

    function guardarNuevaAlerta() {
        const mensaje = $('#new_mensaje').val().trim();
        
        if (mensaje === "") {
            Swal.fire('Atención', 'Por favor escriba un mensaje para la alerta', 'warning');
            return;
        }

        // Bloqueamos la pantalla y mostramos cargando
        Swal.fire({
            title: 'Guardando alerta...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });


        const data = {
            id_remesa: $('#new_id_remesa').val(),
            periodo: $('#new_periodo').val(),
            estado: $('#new_estado').val(),
            mensaje: mensaje,
            _token: "{{ csrf_token() }}"
        };

        $.post("{{ route('alertas.store') }}", data, function(res) {
            $('#modalNuevaAlerta').modal('hide');
            
            Swal.fire({
                icon: 'success',
                title: 'Alerta Creada',
                text: 'La alerta se ha registrado correctamente.',
                timer: 1500,
                showConfirmButton: false
            });

            // RECARGAMOS las alertas del panel superior
            cargarAlertas("{{ $iglesia->id_iglesia }}");
        }).fail(function() {
            Swal.fire('Error', 'No se pudo guardar la alerta', 'error');
        });
    }

    let fechaLimiteActual = null;
    let lugarIglesiaActual = "{{ strtoupper($iglesia->lugar ?? 'SIN LUGAR') }}";

    // Función para abrir el modal y cargar datos
    function editarRemesa(idRemesa) {
        const table = $('#example').DataTable();
        const data = table.rows().data().toArray().find(r => r.id_remesa == idRemesa);

        if (!data) return;

        $('#f_puntualidad_feedback').html('');
        fechaLimiteActual = data.fecha_limite;

        // Cargar datos en el modal
        $('#f_id_remesa').val(data.id_remesa);
        $('#f_periodo_txt').text(`${data.nombre_mes} - ${data.anio}`);
        $('#f_anio_val').val(data.anio);
        $('#f_mes_val').val(data.mes);
        //$('#f_fecha_entrega').val(data.fecha_entrega || "{{ date('Y-m-d') }}");

        let fechaParaInput = "";

        if (data.fecha_entrega) {
            // Si ya existe fecha, reemplazamos el espacio por "T" que es lo que pide el navegador
            fechaParaInput = data.fecha_entrega;
        } else {
            // Si es la primera vez, generamos la fecha y hora actual en formato ISO
            let ahora = new Date();
            // Ajuste de zona horaria local
            ahora.setMinutes(ahora.getMinutes() - ahora.getTimezoneOffset());
            fechaParaInput = ahora.toISOString().slice(0, 16);
        }

        $('#f_fecha_entrega').val(fechaParaInput);


        $('#f_observacion').val(data.observacion || '');
        
        // Switches
        $('#f_cierre').prop('checked', !!data.cierre);
        $('#f_deposito').prop('checked', !!data.deposito);
        $('#f_documentacion').prop('checked', !!data.documentacion);
        $('#f_escaneado').prop('checked', !!data.escaneado);

        // Valores numéricos
        $('#f_ofrenda').val(data.ofrenda || 0);
        $('#f_diezmo').val(data.diezmo || 0);
        $('#f_pro_templo').val(data.pro_templo || 0);
        $('#f_gasto').val(data.gasto || 0);

        // Ejecutar cálculos iniciales
        calcularFondosFilial();
        
        // Guardar fecha limite para el feedback de puntualidad (usamos la variable global que ya tienes)
        setTimeout(() => {
            calcularPuntualidadRealTime();
        }, 100);
        
        $('#modalFilial').modal('show');
    }
    $(document).on('input', '.calc-input', function() {
        calcularFondosFilial();
    });

    function calcularFondosFilial() {
        const ofrenda = parseFloat($('#f_ofrenda').val()) || 0;
        const diezmo = parseFloat($('#f_diezmo').val()) || 0;
        const proTemplo = parseFloat($('#f_pro_templo').val()) || 0;
        const gasto = parseFloat($('#f_gasto').val()) || 0;

        // Fórmulas
        const fondoLocal = (ofrenda * 0.60) + proTemplo;
        const remesaMBOS = (ofrenda * 0.40) + diezmo;
        const totalIngresos = ofrenda + diezmo + proTemplo;
        const saldoNeto = totalIngresos - gasto;

        // Asignar a campos
        $('#f_fondo_local').val(fondoLocal.toFixed(2));
        $('#f_monto_remesa').val(remesaMBOS.toFixed(2));
        $('#f_total_general').val('Bs ' + totalIngresos.toLocaleString('es-BO', {minimumFractionDigits: 2}));
        $('#f_saldo_neto').val('Bs ' + saldoNeto.toLocaleString('es-BO', {minimumFractionDigits: 2}));
        
        // Color del saldo neto
        $('#f_saldo_neto').removeClass('text-danger text-success').addClass(saldoNeto >= 0 ? 'text-success' : 'text-danger');
    }

    // Función para enviar por AJAX
    function guardarRemesaFilial() {
        const id = $('#f_id_remesa').val();
        const url = "{{ route('remesasfilial.registrar', ':id') }}".replace(':id', id);
        const formData = $('#formRemesaFilial').serialize();

        Swal.fire({
            title: 'Procesando Remesa...',
            text: 'Calculando saldos y actualizando historial...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(res) {
                $('#modalFilial').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: '¡Registro Exitoso!',
                    text: 'La remesa y los fondos locales arrastrados se actualizaron correctamente.',
                    timer: 2000,
                    showConfirmButton: false
                });
                actualizarHistorialFondo();
                $('#example').DataTable().ajax.reload(null, false);
                cargarAlertas("{{ $iglesia->id_iglesia }}");
                //actualizarCabeceraFondoLocal();
            },
            error: function(xhr) {
                let mensaje = "No se pudo procesar el registro.";
                if(xhr.responseJSON && xhr.responseJSON.message) mensaje = xhr.responseJSON.message;
                
                Swal.fire('Error de Sistema', mensaje, 'error');
            }
        });
    }


    $(document).on('change input', '#f_fecha_entrega', function() {
        calcularPuntualidadRealTime();
    });
    function calcularPuntualidadRealTime() {
        const fechaEntregaVal = $('#f_fecha_entrega').val();
        const feedback = $('#f_puntualidad_feedback');

        if (!fechaEntregaVal || !fechaLimiteActual) {
            feedback.html('');
            return;
        }

        // NORMALIZACIÓN: Extraemos solo la parte de la fecha (YYYY-MM-DD) 
        // para que la hora no afecte el cálculo de días de puntualidad
        const soloFechaEntrega = fechaEntregaVal.split('T')[0];
        const soloFechaLimite = fechaLimiteActual.split(' ')[0];

        const entrega = new Date(soloFechaEntrega + 'T12:00:00'); // Usamos mediodía para evitar errores de zona horaria
        const limite = new Date(soloFechaLimite + 'T12:00:00');

        // Diferencia en milisegundos a días
        const diffTime = limite - entrega;
        const diferencia = Math.round(diffTime / (1000 * 60 * 60 * 24));

        let mensaje = "";
        let icono = "";
        let claseCSS = "";

        // Lógica por Lugar
        if (lugarIglesiaActual === 'EL ALTO') {
            if (diferencia >= 0) {
                mensaje = "PUNTUALIDAD: 2 estrellas (Puntual)";
                claseCSS = "text-success";
                icono = "bi-star-fill text-warning";
            } else if (Math.abs(diferencia) <= 2) {
                mensaje = "PUNTUALIDAD: 1 estrella (Retraso leve)";
                claseCSS = "text-warning";
                icono = "bi-star-half text-warning";
            } else {
                mensaje = "Sin estrellas (Fuera de plazo)";
                claseCSS = "text-danger";
                icono = "bi-star text-muted";
            }
        } 
        else if (lugarIglesiaActual === 'ALTIPLANO') {
            // Altiplano tiene 5 días de gracia
            if (diferencia >= -5) {
                mensaje = "PUNTUALIDAD: 2 estrellas (A tiempo)";
                claseCSS = "text-success";
                icono = "bi-star-fill text-warning";
            } else {
                mensaje = "Sin estrellas (Fuera de plazo)";
                claseCSS = "text-danger";
                icono = "bi-star text-muted";
            }
        }

        feedback.removeClass('text-success text-warning text-danger text-muted')
                .addClass(claseCSS)
                .html(`<i class="bi ${icono}"></i> ${mensaje} <small class="d-block text-muted">Días de diferencia: ${diferencia}</small>`);
    }
    //BOTON DE RESET

    

    function actualizarHistorialFondo() {
        const idIglesia = "{{ $iglesia->id_iglesia }}";
        const $cuerpoTabla = $('#tabla_historial_fondo');

        $.get(`/iglesia/historial-fondo/${idIglesia}`, function(data) {

            const h3Saldo = $('#big_saldo_actual');
            h3Saldo.text(`Bs ${data.saldo_actual}`);

            h3Saldo.addClass('animate__animated animate__pulse');
            setTimeout(() => h3Saldo.removeClass('animate__animated animate__pulse'), 1000);

            if (data.raw_saldo < 0) {
                h3Saldo.removeClass('text-primary').addClass('text-danger');
            } else {
                h3Saldo.removeClass('text-danger').addClass('text-primary');
            }


            $cuerpoTabla.empty(); // Limpiar filas estáticas

            if (data.historial.length === 0) {
                $cuerpoTabla.append('<tr><td colspan="5" class="text-center text-muted py-3">Sin registros previos</td></tr>');
                return;
            }

            data.historial.forEach(item => {
                let fila = `
                    <tr>
                        <td class="fw-bold ps-2">${item.mes}</td>
                        <td class="text-end text-muted">${item.anterior}</td>
                        <td class="text-end text-success">+${item.ingreso}</td>
                        <td class="text-end text-danger">-${item.gasto}</td>
                        <td class="text-end fw-bold text-primary">${item.final}</td>
                    </tr>
                `;
                $cuerpoTabla.append(fila);
            });
        });
    }
</script>




@endpush