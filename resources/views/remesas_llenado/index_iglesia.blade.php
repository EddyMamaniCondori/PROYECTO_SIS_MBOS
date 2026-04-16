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
                    <div class="col-md-6">
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
                    <div class="col-md-6">
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
                                                <label class="form-check-label" for="col5">Remesa</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="6" id="col6" checked>
                                                <label class="form-check-label" for="col6">Fecha entrega</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="7" id="col7" checked>
                                                <label class="form-check-label" for="col7">Fecha limite</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="8" id="col8" checked>
                                                <label class="form-check-label" for="col8">Estado de dias</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="10" id="col10" checked>
                                                <label class="form-check-label" for="col10">Observaciones</label>
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
                                            <th>Remesa</th>
                                            <th>Fecha entrega</th>
                                            <th>Fecha limite</th>
                                            <th>estado dias</th>
                                            <th>estado</th>
                                            <th>observaciones</th>
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
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formRemesa">
            @csrf
            <input type="hidden" name="id_remesa" id="modal_id_remesa">
            
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Registrar Remesa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row g-2 mb-3 bg-light p-2 rounded-3 border mx-0">
                        <div class="col-4">
                            <label class="small fw-bold text-muted d-block">Mes</label>
                            <span id="txt_mes" class="fw-bold"></span>
                            <input type="hidden" name="mes" id="modal_mes">
                        </div>
                        <div class="col-4">
                            <label class="small fw-bold text-muted d-block">Año</label>
                            <span id="txt_anio" class="fw-bold"></span>
                            <input type="hidden" name="anio" id="modal_anio">
                        </div>
                        <div class="col-4">
                            <label class="small fw-bold text-muted d-block">Monto (Bs)</label>
                            <span id="txt_monto" class="fw-bold text-success"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Fecha de entrega:</label>
                        <input type="date" name="fecha_entrega" id="modal_fecha_entrega" class="form-control" required>
                        <div id="puntualidad_feedback" class="small mt-1 fw-bold"></div>
                    </div>


                    <div class="d-flex gap-3 mb-3 justify-content-center rounded p-2  ">
                        <div class="form-check form-switch">
                            <input type="hidden" name="cierre" value="false">
                            <input class="form-check-input" type="checkbox" role="switch" id="modal_check_cierre" name="cierre" value="true">
                            <label class="form-check-label small fw-bold" for="modal_check_cierre">CIE</label>
                        </div>

                        <div class="form-check form-switch">
                            <input type="hidden" name="deposito" value="false">
                            <input class="form-check-input" type="checkbox" role="switch" id="modal_check_deposito" name="deposito" value="true">
                            <label class="form-check-label small fw-bold" for="modal_check_deposito">DEP</label>
                        </div>

                        <div class="form-check form-switch">
                            <input type="hidden" name="documentacion" value="false">
                            <input class="form-check-input" type="checkbox" role="switch" id="modal_check_documentacion" name="documentacion" value="true">
                            <label class="form-check-label small fw-bold" for="modal_check_documentacion">DOC</label>
                        </div>

                        <div class="form-check form-switch">
                            <input type="hidden" name="escaneado" value="false">
                            <input class="form-check-input" type="checkbox" role="switch" id="modal_check_escaneado" name="escaneado" value="true">
                            <label class="form-check-label small fw-bold " for="modal_check_escaneado">SCAN</label>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Observaciones:</label>
                        <textarea name="observacion" id="modal_observacion" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success px-4" onclick="enviarFormularioRemesa()"> <i class="bi bi-cloud-upload"></i> Guardar Cambios</button>
                </div>
            </div>
        </form>
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
                    render: function(data) {
                        return data ? '<i class="bi bi-check-square-fill text-success"></i>' : '<i class="bi bi-file-excel-fill text-danger"></i>';
                    }
                },
                { 
                    data: 'deposito',
                    render: function(data) {
                        return data ? '<i class="bi bi-check-square-fill text-success"></i>' : '<i class="bi bi-file-excel-fill text-danger"></i>';
                    }
                },
                { 
                    data: 'documentacion',
                    render: function(data) {
                        return data ? '<i class="bi bi-check-square-fill text-success"></i>' : '<i class="bi bi-file-excel-fill text-danger"></i>';
                    }
                },
                { 
                    data: 'escaneado',
                    render: function(data) {
                        return data ? '<i class="bi bi-check-square-fill text-success"></i>' : '<i class="bi bi-file-excel-fill text-danger"></i>';
                    }
                },
                { data: 'monto' },
                { data: 'fecha_entrega' },
                { data: 'fecha_limite' },
                { data: 'estado_dias', // Nombre de la columna que viene del JSON
                    render: function(data, type, row) {
                        // 1. Si el dato es nulo, vacío o exactamente "0" (no entregado)
                        if (!data || data == "0") {
                            return `<span class="badge bg-secondary">Sin entregar</span>`;
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
                        return `<span class="badge ${colorClass}">${data}</span>`;
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
                        let estadoDet = parseInt(data.sw_det_semana);

                        if (estadoDet === 0) {
                            // ESTADO 0: No tiene datos (Botón gris claro o bordeado para invitar a crear)
                            botonSemanas = `
                                <a href="${urlRegistro}" class="btn btn-outline-warning btn-sm rounded-circle shadow-sm" 
                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                data-bs-toggle="tooltip" title="Iniciar Registro de Semanas">
                                    <i class="bi bi-calendar-plus"></i>
                                </a>`;
                        } else if (estadoDet === 1) {
                            // ESTADO 1: Tiene datos y se puede EDITAR
                            botonSemanas = `
                                <a href="${urlRegistro}" class="btn btn-warning btn-sm rounded-circle shadow-sm" 
                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                data-bs-toggle="tooltip" title="Editar Semanas Registradas">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button" 
                                    class="btn btn-secondary btn-sm rounded-circle shadow-sm" 
                                    style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                    onclick="prepararReporte(${data.id_remesa})"
                                    data-bs-toggle="tooltip" 
                                    title="Generar Reporte PDF">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </button>
                                `;
                        } else {
                            // ESTADO 2: Tiene datos pero está BLOQUEADO (Solo ver)
                            botonSemanas = `
                                <a href="${urlRegistro}" class="btn btn-secondary btn-sm rounded-circle shadow-sm opacity-75" 
                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                data-bs-toggle="tooltip" title="Registro Bloqueado (Solo lectura)">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" 
                                    class="btn btn-secondary btn-sm rounded-circle shadow-sm" 
                                    style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                    onclick="prepararReporte(${data.id_remesa})"
                                    data-bs-toggle="tooltip" 
                                    title="Generar Reporte PDF">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </button>
                                `;
                        }

                        return `
                            <div class="btn-group gap-2">
                                ${botonSemanas}

                                <button type="button" 
                                    class="btn btn-primary btn-sm rounded-circle shadow-sm" 
                                    style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                    onclick="abrirModalNuevaAlerta(${data.id_remesa}, '${periodo}')"
                                    data-bs-toggle="tooltip" 
                                    title="Añadir Alerta">
                                    <i class="bi bi-bell"></i>
                                </button>

                                <button type="button" 
                                    class="btn btn-success btn-sm rounded-circle" 
                                    onclick="editarRemesa('${data.id_remesa}')"
                                    title="Editar/Completar Remesa">
                                    <i class="bi bi-check-lg"></i>
                                </button>  
                                <button type="button" class="btn btn-danger btn-sm rounded-circle shadow-sm" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;" onclick="revertirRegistroRemesa(${data.id_remesa})" data-bs-toggle="tooltip" title="Revertir/Resetear Registro">
                                    <i class="bi bi-arrow-counterclockwise"></i>
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

        contenedor.html(`
        <div id="alerta-cargando" class="text-center py-4 text-muted">
            <div class="spinner-border spinner-border-sm text-warning me-2" role="status"></div>
            <span>Cargando alertas...</span>
        </div>
        `);
        vistaVacia.addClass('d-none'); // Ocultamos el mensaje de "sin alertas" por si estaba visible
        

        // 1. Llamada al backend
        $.get(`/alertas/${id_iglesia}`, function(alertas) {
            let html = '';
            const contenedor = $('#contenedor-alertas');
            const vistaVacia = $('#sin-alertas');

            if (!alertas || alertas.length === 0) {
                contenedor.html(''); // Limpiamos el "Cargando..."
                $('#body-alertas').addClass('d-none');
                vistaVacia.removeClass('d-none');
                bodyAlertas.hide();
                return;
            }else {
                vistaVacia.addClass('d-none');
                $('#body-alertas').removeClass('d-none');
            }

            

            alertas.forEach(alerta => {
                // Decidimos el color según el estado
                let esRojo = alerta.estado === 'IMPORTANTE';
                let bg = esRojo ? 'bg-danger-subtle' : 'bg-warning-subtle';
                let border = esRojo ? 'border-danger' : 'border-warning';
                let icono = esRojo ? 'fa-exclamation-circle' : 'fa-exclamation-triangle';
                let textoIcono = esRojo ? 'text-danger' : 'text-warning';

                // 4. Construimos el diseño de la alerta
                html += `
                    <div class="alert ${bg} border-0 border-start border-4 ${border} shadow-sm p-2 mb-2 rounded-3 animate__animated animate__fadeIn">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center" style="min-width: 0;">
                                <div class="${textoIcono} me-2 d-flex align-items-center justify-content-center" style="width: 30px;">
                                    <i class="fas ${icono} fa-lg"></i>
                                </div>
                                
                                <div style="min-width: 0; overflow: hidden;">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold text-dark me-2" style="font-size: 0.85rem;">${alerta.periodo}</span>
                                        <span class="badge ${bg} ${textoIcono} border border-${border.split('-')[1]} py-0 px-1" style="font-size: 0.65rem; opacity: 0.8;">
                                            ${alerta.estado}
                                        </span>
                                    </div>
                                    <div class="text-muted text-truncate-2" style="font-size: 0.8rem; line-height: 1.2;">
                                        ${alerta.mensaje}
                                    </div>
                                </div>
                            </div>

                            <div class="ms-2 d-flex">
                                <button title="Editar" class="btn btn-sm btn-link text-primary p-1 me-1 btn-hover-light" 
                                    onclick="abrirModalEditarAlerta(${alerta.id_remesa}, ${alerta.index}, '${alerta.estado}', '${alerta.mensaje}', '${alerta.periodo}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button title="Solucionado" class="btn btn-sm btn-link text-success p-1 btn-hover-light" 
                                    onclick="solucionarAlerta(${alerta.id_remesa}, ${alerta.index})">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });



            // 5. Inyectamos todo el HTML de un golpe
            contenedor.html(html);
        }).fail(function(jqXHR) {
            // 5. ESTADO ERROR: Si el servidor responde 500 o falla la conexión
            console.error("Error en servidor:", jqXHR.responseText);

            contenedor.html(`
            <div class="alert alert-light border border-danger text-center py-4">
                <i class="fas fa-wifi-slash text-danger fa-2x mb-2"></i>
                <div class="text-danger fw-bold">Fallo: No se pudo conectar al servidor</div>
                <button class="btn btn-sm btn-outline-danger mt-2" onclick="cargarAlertas(${id_iglesia})">
                    <i class="fas fa-sync-alt me-1"></i> Reintentar
                </button>
            </div>
            `);

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
    function editarRemesa(idRemesa) {
        // 1. Buscamos los datos actuales en el DataTable usando el ID
        const table = $('#example').DataTable();
        const data = table.rows().data().toArray().find(r => r.id_remesa == idRemesa);

        if (!data) return;

        fechaLimiteActual = data.fecha_limite;

        // 2. Llenamos los campos de Solo Lectura (Texto y Hidden)
        $('#modal_id_remesa').val(data.id_remesa);
        $('#modal_mes').val(data.mes);
        $('#modal_anio').val(data.anio);
        
        $('#txt_mes').text(data.nombre_mes);
        $('#txt_anio').text(data.anio);
        $('#txt_monto').text(data.monto || '0.00');

        // 3. Llenamos los campos Editables
        $('#modal_fecha_entrega').val(data.fecha_entrega || "{{ date('Y-m-d') }}");

        calcularPuntualidadRealTime();

        $('#modal_observacion').val(data.observacion || '');

        // 4. Activamos los Switches (Booleanos)
        // Asumimos que data.cierre, data.deposito, etc., vienen como true/false o 1/0
        $('#modal_check_cierre').prop('checked', !!data.cierre);
        $('#modal_check_deposito').prop('checked', !!data.deposito);
        $('#modal_check_documentacion').prop('checked', !!data.documentacion);
        $('#modal_check_escaneado').prop('checked', !!data.escaneado);

        // 5. Mostramos el modal
        $('#confirmModal').modal('show');
    }
    function enviarFormularioRemesa() {
        // 1. Recolectamos los datos del formulario
        const formData = $('#formRemesa').serialize();

        // 2. Mostramos el modal de "Guardando..."
        Swal.fire({
            title: 'Guardando cambios',
            text: 'Por favor, espere un momento...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // 3. Enviamos por AJAX
        $.ajax({
            url: "{{ route('remesas.update_estado') }}",
            type: 'POST',
            data: formData,
            success: function(response) {
                // Cerramos el modal de Bootstrap
                $('#confirmModal').modal('hide');

                // Mostramos éxito con SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: '¡Actualizado!',
                    text: 'La remesa se guardó correctamente.',
                    timer: 1500,
                    showConfirmButton: false
                });

                // 4. RECARGAMOS LA TABLA (Sin recargar la página)
                // Asumiendo que tu DataTable se llama 'table' o '#example'
                $('#example').DataTable().ajax.reload(null, false); 
            },
            error: function(jqXHR) {
                // Si hay error (ej: validación de Laravel 422 o error 500)
                let mensajeError = 'No se pudo procesar la solicitud.';
                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    mensajeError = jqXHR.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error al guardar',
                    text: mensajeError
                });
            }
        });
        cargarAlertas("{{ $iglesia->id_iglesia }}");
    }
    $('#modal_fecha_entrega').on('change input', function() {
        calcularPuntualidadRealTime();
    });
    function calcularPuntualidadRealTime() {
        const fechaEntregaVal = $('#modal_fecha_entrega').val();
        const feedback = $('#puntualidad_feedback');

        if (!fechaEntregaVal || !fechaLimiteActual) {
            feedback.html('');
            return;
        }

        // Parse de fechas
        const entrega = new Date(fechaEntregaVal + 'T00:00:00');
        const limite = new Date(fechaLimiteActual + 'T00:00:00');

        // Diferencia en días
        const diffTime = limite - entrega;
        const diferencia = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        let mensaje = "";
        let icono = "";
        let claseCSS = "";

        // Lógica por lugar
        if (lugarIglesiaActual === 'EL ALTO') {
            if (diferencia >= 0) {
                mensaje = "PUNTUALIDAD: Obtendrá 1 estrella (Puntual)";
                claseCSS = "text-success";
                icono = "bi-star-fill";
            } else if (Math.abs(diferencia) <= 2) {
                mensaje = "PUNTUALIDAD: Obtendrá 1/2 estrella (Retraso leve)";
                claseCSS = "text-warning";
                icono = "bi-star-half";
            } else {
                mensaje = " No obtendrá estrellas (Fuera de plazo)";
                claseCSS = "text-danger";
                icono = "bi-star";
            }
        } 
        else if (lugarIglesiaActual === 'ALTIPLANO') {
            // Altiplano: Puntual o hasta 5 días de retraso
            if (diferencia >= 0 || Math.abs(diferencia) <= 5) {
                mensaje = "PUNTUALIDAD: Obtendrá 1 estrella (Puntual)";
                claseCSS = "text-success";
                icono = "bi-star-fill";
            } else {
                mensaje = "No obtendrá estrellas (Fuera de plazo)";
                claseCSS = "text-danger";
                icono = "bi-star";
            }
        } else {
            mensaje = "Lugar no identificado para cálculo.";
            claseCSS = "text-muted";
        }

        // Inyectar en el HTML
        feedback.removeClass('text-success text-warning text-danger text-muted pt-1').addClass(claseCSS);
        feedback.html(`<i class="bi ${icono}"></i> ${mensaje}`);
    }
    //BOTON DE RESET

    function revertirRegistroRemesa(idRemesa) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se eliminará la fecha de entrega, los checks y las observaciones. Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, revertir todo',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar "Procesando"
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Restableciendo valores de la remesa',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                $.ajax({
                    url: "{{ route('remesas.revertir') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id_remesa: idRemesa
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('¡Revertido!', response.message, 'success');
                            $('#example').DataTable().ajax.reload(null, false);
                            // Recargar alertas si la función existe
                            if (typeof cargarAlertas === "function") {
                                const idIglesia = "{{ $iglesia->id_iglesia ?? '' }}";
                                if(idIglesia) cargarAlertas(idIglesia);
                            }
                        } else {
                            Swal.fire('Atención', response.message, 'info');
                        }
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error interno del servidor';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });

        cargarAlertas("{{ $iglesia->id_iglesia }}");
    }

</script>




@endpush