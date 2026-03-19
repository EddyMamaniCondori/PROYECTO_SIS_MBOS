@extends('template')


@section('title', 'Tablas')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Contenedor de la barra (invisible por defecto) */
        #progress-container {
            position: fixed; /* Se queda pegada aunque bajes con el scroll */
            top: 0;
            left: 0;
            width: 100%;
            height: 4px; /* Un poquito más gruesa para que se vea bien */
            background-color: transparent;
            z-index: 9999; /* Asegura que esté por encima de todo el sistema */
            overflow: hidden;
            display: none;
        }

        /* La línea de color */
        #progress-bar {
            width: 0%;
            height: 100%;
            background-color: #156bc0; /* Azul Marino Profundo */
            box-shadow: 0 0 15px rgba(0, 51, 102, 0.7); /* Brillo sutil azul */
            transition: width 0.4s ease;
        }

        /* Animación de pulso opcional */
        .progress-active {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.4; }
            100% { opacity: 1; }
        }
    </style>
@endpush

@section('content')
                @php
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
                @endphp

    <x-alerts/>
        <!-- CONTENIDO DEL Header-->
        <div id="progress-container">
            <div id="progress-bar"></div>
        </div>
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><strong><h3 class="mb-0 text-primary">Registro de Remesas {{ $meses_array[$mes]}} - {{$anio}} </h3></strong></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Remesas</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="row px-4">
            <div class="col-12">
                <div class="card shadow-sm mb-4 border-1">
                    <div class="card-body  rounded">
                        <form id="filtroForm">
                            @csrf  
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-primary"><i class="bi bi-person-check-fill"></i> Personal Responsable</label>
                                    <select id="id_personal" name="id_personal" class="form-select shadow-sm border-primary">
                                        <option value="-1">-- Todos los Tesoreros --</option>
                                        @foreach($personal as $per)
                                            <option value="{{$per->id_persona}}" 
                                                {{ (auth()->user()->id_persona ?? auth()->id()) == $per->id_persona ? 'selected' : '' }}>
                                                {{$per->nombre}} {{$per->ape_paterno}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-secondary"><i class="bi bi-collection-fill"></i> Tipo</label>
                                    <div class="d-flex flex-wrap gap-2 p-2 border rounded bg-white shadow-sm h-75 align-items-center">
                                        <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="form-check custom-check">
                                                        <input class="form-check-input check-todos" type="checkbox" name="tipo[]" value="TODOS" id="tipoTodos" checked>
                                                        <label class="form-check-label" for="tipoTodos"><i class="bi bi-asterisk text-secondary"></i> Todos</label>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-check custom-check">
                                                        <input class="form-check-input check-item sub-tipo" type="checkbox" name="tipo[]" value="Iglesia" id="tipoIgle">
                                                        <label class="form-check-label" for="tipoIgle"><i class="bi bi-house-check-fill text-success"></i> Iglesia</label>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-check custom-check">
                                                        <input class="form-check-input check-item sub-tipo" type="checkbox" name="tipo[]" value="Grupo" id="tipoGrupo">
                                                        <label class="form-check-label" for="tipoGrupo"><i class="bi bi-house-fill text-primary"></i> Grupo</label>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-check custom-check">
                                                        <input class="form-check-input check-item sub-tipo" type="checkbox" name="tipo[]" value="Filial" id="tipoFilial">
                                                        <label class="form-check-label" for="tipoFilial"><i class="bi bi-house-gear-fill text-warning"></i> Filial</label>
                                                    </div>
                                                </div>
                                            </div>
                                       
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-secondary"><i class="bi bi-geo-alt-fill"></i> Lugar</label>
                                    <div class="d-flex flex-wrap gap-2 p-2 border rounded bg-white shadow-sm h-75 align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input check-todos" type="checkbox" name="lugar[]" value="TODOS" id="lugarTodos" checked>
                                            <label class="form-check-label fw-bold" for="lugarTodos">TODOS</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input check-item sub-lugar" type="checkbox" name="lugar[]" value="EL ALTO" id="lugarAlto">
                                            <label class="form-check-label" for="lugarAlto">El Alto</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input check-item sub-lugar" type="checkbox" name="lugar[]" value="ALTIPLANO" id="lugarAlti">
                                            <label class="form-check-label" for="lugarAlti">Altiplano</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-secondary"><i class="bi bi-info-circle-fill"></i> Estado</label>
                                    <div class="d-flex flex-wrap gap-2 p-2 border rounded bg-white shadow-sm h-75 align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input check-todos" type="checkbox" name="estado[]" value="TODOS" id="estadoTodos" checked>
                                            <label class="form-check-label fw-bold" for="estadoTodos">TODOS</label>
                                        </div>
                                        <div class="form-check text-danger">
                                            <input class="form-check-input check-item sub-estado" type="checkbox" name="estado[]" value="PENDIENTE" id="estPen">
                                            <label class="form-check-label" for="estPen">Pend.</label>
                                        </div>
                                        <div class="form-check text-success">
                                            <input class="form-check-input check-item sub-estado" type="checkbox" name="estado[]" value="ENTREGADO" id="estEnt">
                                            <label class="form-check-label" for="estEnt">Entr.</label>
                                        </div>
                                        <div class="form-check text-warning">
                                            <input class="form-check-input check-item sub-estado" type="checkbox" name="estado[]" value="REGISTRADO EN ACMS" id="estAcms">
                                            <label class="form-check-label" for="estAcms">ACMS</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary px-5 shadow">
                                        <i class="bi bi-search"></i> Aplicar Filtros
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                                <div class="fw-bold text-secondary">
                                    <i class="fas fa-table me-2 text-primary"></i>
                                    TABLA DE REMESAS
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
                                                <input class="form-check-input toggle-vis" type="checkbox" value="1" id="col1" checked>
                                                <label class="form-check-label" for="col1">Pastor</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="4" id="col4" checked>
                                                <label class="form-check-label" for="col4">Tipo</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="5" id="col5" checked>
                                                <label class="form-check-label" for="col5">Encargado</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="6" id="col6" checked>
                                                <label class="form-check-label" for="col6">Lugar</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="10" id="col10" checked>
                                                <label class="form-check-label" for="col10">Fecha entrega</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="11" id="col11" checked>
                                                <label class="form-check-label" for="col11">Fecha limite</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input toggle-vis" type="checkbox" value="14" id="col14" checked>
                                                <label class="form-check-label" for="col14">Observaciones</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example" class="display" >
                                    <thead>
                                        <tr>
                                            <th>Distrito</th>
                                            <th>Pastor</th>
                                            <th>Cod</th>
                                            <th>Iglesia</th>
                                            <th>tipo</th>
                                            <th>Encargado</th>
                                            <th>lugar</th>
                                            <th>CIE</th>
                                            <th>DEP</th>
                                            <th>DOC</th>
                                            <th>Fecha entrega</th>
                                            <th>Fecha limite</th>
                                            <th>estado dias</th>
                                            <th>estado</th>
                                            <th>observaciones</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        </tbody>
                                </table>

                            </div>
                        </div>


            <div class="card-body">
          </div>
          <!--end::Container-->
        </div>


<!-- Modal único reutilizable -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formRemesa" method="POST">
        @csrf
        <input type="hidden" name="id_remesa" id="modal_id_remesa">
        <input type="hidden" name="mes" id="mes" value="{{$mes}}">
        <input type="hidden" name="anio" id="value" value="{{$anio}}">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="confirmModalLabel">Complete los datos</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <center><strong style="color: green;">Verifique los datos</strong></center><br>
            <p><strong>id_iglesia:</strong> <span id="modal_id_iglesia"></span><br>
          <strong>Distrito:</strong> <span id="modal_distrito"></span><br>
          <strong>Iglesia:</strong> <span id="modal_iglesia"></span><br>
          <strong>Pastor:</strong> <span id="modal_pastor"></span></p>
          <hr>

          <label for="fecha_entrega" class="form-label">Fecha de entrega:</label>
          <input type="date" name="fecha_entrega" id="modal_fecha_entrega" class="form-control" value="{{ date('Y-m-d') }}" required>

          <br>

          <div class="form-check form-switch">
            <input type="hidden" name="cierre" value="false">
            <input class="form-check-input" type="checkbox" role="switch" id="modal_switchCierre" name="cierre" value="true">
            <label class="form-check-label" for="modal_switchCierre">Cierre:</label>
          </div>

          <div class="form-check form-switch">
            <input type="hidden" name="deposito" value="false">
            <input class="form-check-input" type="checkbox" role="switch" id="modal_switchDeposito" name="deposito" value="true">
            <label class="form-check-label" for="modal_switchDeposito">Depósito:</label>
          </div>

          <div class="form-check form-switch">
            <input type="hidden" name="documentacion" value="false">
            <input class="form-check-input" type="checkbox" role="switch" id="modal_switchDocumentacion" name="documentacion" value="true">
            <label class="form-check-label" for="modal_switchDocumentacion">Documentación:</label>
          </div>

          <div class="mt-3">
            <label for="observacion" class="form-label">Observación:</label>
            <input type="text" name="observacion" id="modal_observacion" class="form-control">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success">Confirmar</button>
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
    <script>
    $(document).ready(function() {
        var mesActual = "{{ $mes }}";
        var anioActual = "{{ $anio }}";

        var table = $('#example').DataTable({
            ajax: {
                url: '/remesas/get-data-json/' + mesActual + '/' + anioActual,
                dataSrc: 'data'
            },
            stateSave: true,
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
            },
            columns: [
                { data: 'nombre_distrito' },
                { 
                    data: null, 
                    render: function(data) {
                        return `${data.nombre_pas} ${data.ape_paterno} ${data.ape_materno}`;
                    }
                },
                { data: 'codigo' },
                { data: 'nombre_igle' },
                { data: 'tipo_igle' },
                { 
                    data: null,
                    render: function(data) {
                        return data.nombre_per ? `${data.nombre_per} ${data.ape_paterno_per}` : '-';
                    }
                },
                { data: 'lugar_igle' },
                // Columnas de Iconos (CIE, DEP, DOC)
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
                { data: 'fecha_entrega' },
                { data: 'fecha_limite' },
                { data: 'estado_dias' },
                { 
                    data: 'estado',
                    render: function(data) {
                        let color = data === 'PENDIENTE' ? 'red' : (data === 'ENTREGADO' ? 'green' : 'orange');
                        return `<strong><span style="color: ${color}">${data}</span></strong>`;
                    }
                },
                { data: 'observacion', defaultContent: '-' },
                // COLUMNA DE ACCIONES (Botón dinámico)
                {
                    data: null,
                    render: function(data) {
                        let urlRegistro = "{{ route('remesas.registro_semanas', ':id') }}";
                        // 2. Reemplazamos el placeholder con el valor real de JS
                        urlRegistro = urlRegistro.replace(':id', data.id_remesa);

                        if (data.tipo_igle === 'Filial') {
                            return ` 
                            <div class="btn-group">
                            <a href="${urlRegistro}" class="btn btn-warning shadow-sm btn-sm">
                                <i class="bi bi-pencil-square"></i> Registrar Semanas
                            </a>
                            <form action="{{ route('remesas.filial') }}" method="POST">@csrf

                                                          <input type="hidden" name="id_iglesia" id="id_iglesia" value="${data.id_iglesia }">
                                                          <input type="hidden" name="anio" id="anio" value="${anioActual }">
                                                          <input type="hidden" name="mes" id="mes" value="${ mesActual }">
                                                          <input type="hidden" name="distrito" id="distrito" value="${data.nombre_distrito}">
                                                          <button type="submit" class="btn btn-success btn-sm">
                                                              <i class="bi bi-file-earmark-bar-graph-fill"></i> Registrar
                                                          </button>
                                                      </form></div>`;
                        }
                        return `
                                <div class="btn-group">
                                <a href="${urlRegistro}" class="btn btn-warning shadow-sm btn-sm">
                                    <i class="bi bi-pencil-square"></i> Registrar Semanas
                                </a>    
                                <button type="button" class="btn btn-primary btn-sm btn-abrir-modal" 
                                    data-id_remesa="${data.id_remesa}" 
                                    data-distrito="${data.nombre_distrito}"
                                    data-iglesia="${data.nombre_igle}"
                                    data-id_iglesia="${data.id_iglesia}"
                                    data-pastor="${data.nombre_pas} ${data.ape_paterno}"
                                    data-tipo="${data.tipo_igle}"
                                    data-fecha_entrega="${data.fecha_entrega}"
                                    data-cierre="${data.cierre}"
                                    data-deposito="${data.deposito}"
                                    data-documentacion="${data.documentacion}"
                                    data-observacion="${data.observacion}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#confirmModal">
                                    <i class="bi bi-pencil-square"></i> Registrar
                                </button></div>`;
                    }
                }
            ],
            
            createdRow: function(row, data) {
                if (data.estado === 'ENTREGADO') $(row).css('background-color', '#d4edda');
                else if (data.estado === 'REGISTRADO EN ACMS') $(row).css('background-color', '#ebdbc2');
                if (data.tipo_igle === 'Filial') $(row).css('color', 'red');
                $(row).attr('id', 'fila-' + data.id_remesa);
            }
        });
        // *********************************************************
        //              LINEA DE CARGANDO
        // **********************************************************"

        // Función para MOSTRAR la barra
        function startLoading() {
            $('#progress-container').show();
            $('#progress-bar').css('width', '0%'); // Empezar de cero
            
            // Saltos rápidos para dar sensación de respuesta inmediata
            setTimeout(() => $('#progress-bar').css('width', '15%'), 50);
            setTimeout(() => $('#progress-bar').css('width', '45%'), 200);
            setTimeout(() => $('#progress-bar').css('width', '85%'), 500);
        }

        // Función para FINALIZAR la barra
        function stopLoading() {
            $('#progress-bar').css('width', '100%');
            setTimeout(() => {
                $('#progress-container').fadeOut(300, function() {
                    $('#progress-bar').css('width', '0%');
                });
            }, 200);
        }

        // EVENTOS DE DATATABLES
        table.on('preXhr', function() {
            startLoading(); // Se activa cuando se refresca la tabla (polling o manual)
        });

        table.on('xhr', function() {
            stopLoading(); // Se oculta cuando los datos ya llegaron
        });

        startLoading();

        

        // *********************************************************
        //              LLENADO DEL MODAL
        // **********************************************************"
         $('#confirmModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);

            // Leer atributos data-* del botón
            var id_iglesia = button.data('id_iglesia');
            var id_remesa = button.data('id_remesa');
            var distrito = button.data('distrito');
            var iglesia = button.data('iglesia');
            var pastor = button.data('pastor');
            var fecha_entrega = button.data('fecha_entrega');
            var cierre = button.data('cierre');
            var deposito = button.data('deposito');
            var documentacion = button.data('documentacion');
            var observacion = button.data('observacion');
            var modal = $(this);

            // ✅ Actualizar acción del formulario con la ruta dinámica
            var actionUrl = "{{ route('remesasiglesia.registrar', ':id_remesa') }}";
            actionUrl = actionUrl.replace(':id_remesa', id_remesa);
            modal.find('#formRemesa').attr('action', actionUrl);

            // ✅ Rellenar campos visibles
            modal.find('#modal_id_iglesia').text(id_iglesia);
            modal.find('#modal_id_remesa').val(id_remesa);
            modal.find('#modal_distrito').text(distrito);
            modal.find('#modal_iglesia').text(iglesia);
            modal.find('#modal_pastor').text(pastor);
            modal.find('#modal_observacion').val(observacion);
            // ✅ Fecha (por defecto hoy si está vacía)
            modal.find('#modal_fecha_entrega').val(fecha_entrega || new Date().toISOString().split('T')[0]);

            // ✅ Checkboxes
            modal.find('#modal_switchCierre').prop('checked', cierre == 1 || cierre === true);
            modal.find('#modal_switchDeposito').prop('checked', deposito == 1 || deposito === true);
            modal.find('#modal_switchDocumentacion').prop('checked', documentacion == 1 || documentacion === true);
      
        });

        // *********************************************************
        //              enviado de datos
        // **********************************************************"


        $('#formRemesa').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            //console.log("Enviando estos datos a la MBOS:", form.serializeArray());
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                beforeSend: function() {
                    startLoading();
                    form.find('button[type="submit"]').prop('disabled', true).text('Guardando...');
                },
                success: function(response) {
                    if(response.success) {
                        // CERRAR MODAL
                        $('#confirmModal').modal('hide');
                        
                        // RECARGAR SOLO LOS DATOS DE LA TABLA
                        table.ajax.reload(null, false); 

                        // MENSAJE ELEGANTE
                        Swal.fire({
                            icon: 'success',
                            title: '¡Registrado!',
                            text: 'La remesa de la MBOS se actualizó correctamente.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                    form.find('button[type="submit"]').prop('disabled', false).text('Confirmar');
                    stopLoading();
                },
                error: function() {
                    stopLoading();
                    Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                    form.find('button[type="submit"]').prop('disabled', false).text('Confirmar');
                }
            });
        });

        // --- 1. LÓGICA DE LOS CHECKBOXES "TIPO" ---
        function configurarChecks(idTodos, claseItems) {
        // Si marco "TODOS", desmarco los específicos
            $(idTodos).on('change', function() {
                if ($(this).is(':checked')) {
                    $(claseItems).prop('checked', false);
                }
            });

            // Si marco uno específico, desmarco "TODOS"
            $(claseItems).on('change', function() {
                if ($(this).is(':checked')) {
                    $(idTodos).prop('checked', false);
                }
                // Si al final no hay nada marcado, marco "TODOS" automáticamente
                if ($(claseItems + ':checked').length === 0) {
                    $(idTodos).prop('checked', true);
                }
            });
        }

        // Aplicamos la lógica a los 3 grupos que tienes
        configurarChecks('#tipoTodos', '.sub-tipo');
        configurarChecks('#lugarTodos', '.sub-lugar');
        configurarChecks('#estadoTodos', '.sub-estado');

        // --- 2. ACCIÓN DE FILTRADO POR AJAX ---
        $('#filtroForm').on('submit', function(e) {
            e.preventDefault();
            
            startLoading(); // Tu barra azul de progreso
            
            // Serializamos los datos (esto enviará arreglos de tipo[], lugar[], etc.)
            let datos = $(this).serialize();
            
            // Construimos la nueva URL de la tabla
            let urlBase = `/remesas/get-data-json/${mesActual}/${anioActual}`;
            let urlConFiltros = urlBase + '?' + datos;
            
            // Le ordenamos a DataTable que cambie su fuente y recargue
            table.ajax.url(urlConFiltros).load(function() {
                stopLoading(); // Detenemos la barra azul
                
                // Un pequeño aviso de éxito tipo "Toast"
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Filtros aplicados',
                    showConfirmButton: false,
                    timer: 1500
                });
            });
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
</script>

@endpush