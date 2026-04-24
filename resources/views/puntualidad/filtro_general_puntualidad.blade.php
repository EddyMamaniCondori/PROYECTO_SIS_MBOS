@extends('template')


@section('title', 'Filtro Remesas Puntualidad')

@push('css')
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    
    <style>
        /* Variables de estilo premium */
        :root {
            --primary-gold: #b8860b;
            --dark-navy: #0d47a1;
            --soft-bg: #f8fafc;
            --border-color: #e2e8f0;
        }

        .card-filter {
            background: #ffffff;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .section-title::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--border-color);
            margin-left: 10px;
        }

        /* Estilo para los Selects */
        .form-select-custom {
            border-radius: 12px;
            border: 2px solid #f1f5f9;
            padding: 10px 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .form-select-custom:focus {
            border-color: var(--dark-navy);
            box-shadow: 0 0 0 4px rgba(13, 71, 161, 0.1);
        }

        /* Transformación de Checkboxes a "Chips" */
        .tipo-selector {
            display: none;
        }

        .tipo-label {
            display: block;
            padding: 12px 15px;
            background: #f1f5f9;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            text-align: center;
        }

        .tipo-selector:checked + .tipo-label {
            background: var(--dark-navy);
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 71, 161, 0.2);
        }

        .tipo-selector:disabled + .tipo-label {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* Radio Buttons estilo Segmented Control */
        .btn-check:checked + .btn-outline-primary {
            background-color: var(--dark-navy) !important;
            border-color: var(--dark-navy) !important;
            color: white !important;
            font-weight: 700;
        }

        .btn-outline-primary {
            border: 2px solid #f1f5f9 !important;
            color: #64748b;
            background: #f1f5f9;
            border-radius: 10px !important;
            padding: 10px;
            font-weight: 600;
        }
        .btn-outline-primary.small {
            font-size: 0.75rem !important;
            letter-spacing: 0.5px;
        }

        /* Estilo para cuando el input está deshabilitado visualmente */
        input:disabled, select:disabled {
            background-color: #f1f5f9 !important;
            cursor: not-allowed;
            opacity: 0.6;
        }


        /* Paneles dinámicos con animación */
        .main-layer, .final-panel {
            animation: fadeIn 0.4s ease-out;
        }

       

        /* Botones de acción */
        .btn-action {
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-action-primary {
            background: var(--dark-navy);
            border: none;
            box-shadow: 0 4px 12px rgba(13, 71, 161, 0.2);
        }

        .btn-action-primary:hover {
            background: #0a3d8a;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 71, 161, 0.3);
        }

        /* Estilo del Panel de Distritos */
        .container-distritos-scroll {
            max-height: 200px;
            overflow-y: auto;
            padding: 10px;
            background: #f8fafc;
            border-radius: 15px;
            border: 1px solid var(--border-color);
        }

        input[type="date"].form-select-custom {
            border-radius: 12px;
            border: 2px solid #f1f5f9;
            padding: 10px 15px;
            font-weight: 600;
            color: var(--dark-navy);
        }

        /* Animación de entrada para los paneles */
        .animate__fadeIn {
            animation-duration: 0.5s;
        }

    </style>




@endpush

@section('content')
<x-alerts/>

<div class="app-content-header p-3 pb-0">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold"><i class="fas fa-award text-warning me-2"></i>Filtros Para Puntualidad</h3>
                <p class="text-muted small">Seleccione los criterios para el reporte de desempeño</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Inicio</a></li>
                    <li class="breadcrumb-item active fw-bold text-primary">Puntualidad</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card card-filter">
            <form id="filterForm" action="{{ route('remesas.puntualidad_filtro_general.pdf')}}" method="POST" class="card-body pt-4 pb-4 px-4" target="_blank">
                @csrf
                
                <div class="section-title justify-content-center">Seleccione el Método de Evaluación</div>
                <div class="row mb-2">
                    <div class="col-md-8 col-lg-6 mx-auto">
                        <div class="btn-group w-100 shadow-sm" role="group" style="border-radius: 12px; overflow: hidden; border: 1.5px solid var(--border-color);">
                            <input type="radio" class="btn-check" name="modo_filtro" id="modo_periodo" value="periodo" checked>
                            <label class="btn btn-outline-primary py-2 fw-bold small" for="modo_periodo">
                                <i class="fas fa-calendar-alt me-1"></i> POR PERIODO
                            </label>

                            <input type="radio" class="btn-check" name="modo_filtro" id="modo_rango" value="rango">
                            <label class="btn btn-outline-primary py-2 fw-bold small" for="modo_rango">
                                <i class="fas fa-calendar-day me-1"></i> POR RANGO
                            </label>
                        </div>
                    </div>
                </div>

                <div id="panel_modo_periodo" class="row g-4 mb-2 animate__animated animate__fadeIn">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">Desde el Periodo:</label>
                        <select name="periodo_inicio" id="periodo_inicio" class="form-select form-select-custom"></select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">Hasta el Periodo:</label>
                        <select name="periodo_fin" id="periodo_fin" class="form-select form-select-custom"></select>
                    </div>
                </div>

                <div id="panel_modo_rango" class="row g-4 mb-5 d-none animate__animated animate__fadeIn">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">Fecha de Inicio:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-2 border-end-0" style="border-radius: 12px 0 0 12px; border-color: #f1f5f9;"><i class="fas fa-calendar-plus text-primary"></i></span>
                            <input type="date" name="fecha_desde" id="fecha_desde" class="form-control form-select-custom border-start-0" style="border-radius: 0 12px 12px 0;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">Fecha de Fin:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-2 border-end-0" style="border-radius: 12px 0 0 12px; border-color: #f1f5f9;"><i class="fas fa-calendar-check text-success"></i></span>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control form-select-custom border-start-0" style="border-radius: 0 12px 12px 0;">
                        </div>
                    </div>
                </div>

                <div class="section-title">Categorías a Incluir</div>
                <div class="row g-3 mb-2">
                    <div class="col-6 col-md-3">
                        <input class="tipo-selector tipo-checkbox" type="checkbox" name="tipos[]" value="Iglesia" id="t_iglesia">
                        <label class="tipo-label" for="t_iglesia">
                            <i class="fas fa-church d-block mb-2 fa-lg text-success"></i> IGLESIAS
                        </label>
                    </div>
                    <div class="col-6 col-md-3">
                        <input class="tipo-selector tipo-checkbox" type="checkbox" name="tipos[]" value="Grupo" id="t_grupo">
                        <label class="tipo-label" for="t_grupo">
                            <i class="fas fa-users d-block mb-2 fa-lg text-primary"></i> GRUPOS
                        </label>
                    </div>
                    <div class="col-6 col-md-3">
                        <input class="tipo-selector tipo-checkbox" type="checkbox" name="tipos[]" value="Filial" id="t_filial">
                        <label class="tipo-label" for="t_filial">
                            <i class="fas fa-place-of-worship d-block mb-2 fa-lg text-warning"></i> FILIALES
                        </label>
                    </div>
                    
                    <div class="col-6 col-md-3">
                        <input class="tipo-selector" type="checkbox" name="tipos[]" value="todos" id="t_todos">
                        <label class="tipo-label border-primary border-opacity-25" for="t_todos">
                            <i class="fas fa-check-double d-block mb-2 fa-lg text-info"></i> TODOS
                        </label>
                    </div>
                </div>

                <div class="section-title">Alcance del Reporte</div>
                <div class="row mb-2 align-items-end">
                    <div class="col-md-7">
                        <label class="form-label fw-bold small text-secondary mb-3">Nivel de agrupación:</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check main-trigger" name="nivel_principal" id="n_mbos" value="capa_mbos">
                            <label class="btn btn-outline-primary py-2" for="n_mbos"><i class="fas fa-globe me-2"></i>TODO EL MBOS</label>

                            <input type="radio" class="btn-check main-trigger" name="nivel_principal" id="n_encargado_root" value="capa_encargado">
                            <label class="btn btn-outline-primary py-2" for="n_encargado_root"><i class="fas fa-user-tie me-2"></i>POR ENCARGADO</label>
                        </div>
                    </div>
                    <div id="capa_encargado" class="col-md-5 main-layer d-none mt-3 mt-md-0">
                        <div class="p-3 bg-light rounded-3 border">
                            <label class="form-label fw-bold small text-primary"><i class="fas fa-search me-1"></i> Seleccionar Encargado:</label>
                            <select class="form-select" name="encargado_id" id="select_encargado">
                                <option value="">-- Buscar Responsable --</option>
                                @foreach($encargados as $e)
                                    <option value="{{ $e->id_persona }}">{{ $e->nombre }} {{ $e->ape_paterno }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="sub_opciones_container" class="d-none mb-5 animate__animated animate__fadeIn">
                    <div class="section-title" id="titulo_sub_opcion">Criterios Adicionales</div>
                    <div class="btn-group w-100" role="group" id="group_sub_opciones"></div>
                </div>

                <div id="paneles_finales" class="p-4 rounded-4 mb-5 border d-none bg-light shadow-inner">
                   

                    <div id="panel_zona" class="final-panel d-none d-flex justify-content-center gap-5 py-2">
                        @foreach(['ALTIPLANO', 'EL ALTO', 'TODOS'] as $z)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="zona" value="{{ $z }}" id="z_{{ $z }}">
                                <label class="form-check-label fw-bold text-dark" for="z_{{ $z }}">{{ $z }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-center justify-content-md-end gap-3 mt-4 border-top pt-4">
                    <button type="submit" name="action" value="listar" class="btn btn-action btn-action-primary text-white">
                        <i class="fas fa-file-pdf me-2"></i> Generar Reporte PDF
                    </button>
                    <button type="submit" name="action" value="excel" class="btn btn-action btn-success px-4">
                        <i class="fas fa-file-excel me-2"></i> Exportar Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection




        @push('js')

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
  
<script>
document.addEventListener('DOMContentLoaded', function() {


    const radioPeriodo = document.getElementById('modo_periodo');
    const radioRango = document.getElementById('modo_rango');
    const panelPeriodo = document.getElementById('panel_modo_periodo');
    const panelRango = document.getElementById('panel_modo_rango');

    const inputDesde = document.getElementById('fecha_desde');
    const inputHasta = document.getElementById('fecha_hasta');

    function alternarModos() {
        if (radioPeriodo.checked) {
            panelPeriodo.classList.remove('d-none');
            panelRango.classList.add('d-none');
            toggleInputs(panelPeriodo, true);
            toggleInputs(panelRango, false);
        } else {
            panelPeriodo.classList.add('d-none');
            panelRango.classList.remove('d-none');
            toggleInputs(panelPeriodo, false);
            toggleInputs(panelRango, true);

            // 1. VALIDACIÓN: Colocar fecha de hoy automáticamente si están vacíos
            const hoy = new Date().toISOString().split('T')[0];
            if (!inputDesde.value) inputDesde.value = hoy;
            if (!inputHasta.value) inputHasta.value = hoy;
            
            // Sincronizar el mínimo permitido
            inputHasta.min = inputDesde.value;
        }
    }

    inputDesde.addEventListener('change', function() {
        // Al cambiar la fecha inicial, actualizamos el mínimo de la final
        inputHasta.min = this.value;
        
        // Si la fecha final actual es menor a la nueva fecha inicial, la igualamos
        if (inputHasta.value < this.value) {
            inputHasta.value = this.value;
        }
    });


    // Función auxiliar para habilitar/deshabilitar campos y que no se envíen vacíos
    function toggleInputs(container, enabled) {
        const inputs = container.querySelectorAll('select, input');
        inputs.forEach(i => {
            i.disabled = !enabled;
            if(enabled) i.removeAttribute('disabled');
            else i.setAttribute('disabled', 'disabled');
        });
    }

    radioPeriodo.addEventListener('change', alternarModos);
    radioRango.addEventListener('change', alternarModos);

    // Ejecutar al cargar para asegurar el estado inicial
    alternarModos();
    //************************************************************************************ */
    const periodoInicio = document.getElementById('periodo_inicio');
    const periodoFin = document.getElementById('periodo_fin');

    // 1. Datos de periodos (01-2026 hasta 12-2026)
    const listaPeriodos = @json($periodos);

    function popularPeriodos() {
        // Limpiar selects por si acaso
        periodoInicio.innerHTML = '';
        
        listaPeriodos.forEach((p) => {
            let opt = document.createElement('option');
            // El valor será sin espacios para el POST (ej: 02-2026)
            opt.value = p.label.replace(/\s/g, ''); 
            opt.text = p.label;
            periodoInicio.add(opt);
        });
        
        // Ejecutar la sincronización inicial
        updatePeriodoFin();
    }

    // 2. Sincronización de Periodos
    function updatePeriodoFin() {
      const selectedIndex = periodoInicio.selectedIndex;
      const currentFinValue = periodoFin.value;

      // 1. Limpiar el select de Periodo Final
      periodoFin.innerHTML = '';

      // 2. Lógica de Bloqueo:
      // Si selectedIndex es 0 (el mes más reciente), no hay más opciones hacia adelante.
      if (selectedIndex === 0) {
          periodoFin.disabled = true; // Bloquear el select
          
          let opt = document.createElement('option');
          opt.value = listaPeriodos[0].label.replace(/\s/g, '');
          opt.text = listaPeriodos[0].label;
          periodoFin.add(opt);
          periodoFin.value = opt.value; // Forzar el mismo valor
      } else {
          periodoFin.disabled = false; // Desbloquear si hay opciones

          // Llenar con opciones válidas (desde el inicio hasta el más reciente)
          // En orden DESC: el índice 0 es el más nuevo, selectedIndex es el más viejo.
          // Las opciones válidas para el final son desde el 0 hasta el selectedIndex.
          for (let i = 0; i <= selectedIndex; i++) {
              let opt = document.createElement('option');
              opt.value = listaPeriodos[i].label.replace(/\s/g, '');
              opt.text = listaPeriodos[i].label;
              periodoFin.add(opt);
          }

          // Mantener selección previa si es válida, si no, igualar al inicio
          if (currentFinValue && [...periodoFin.options].some(o => o.value === currentFinValue)) {
              periodoFin.value = currentFinValue;
          } else {
              periodoFin.value = periodoInicio.value;
          }
      }
  }

    periodoInicio.addEventListener('change', updatePeriodoFin);

    // Inicializar la carga de datos
    popularPeriodos();
    
    // ... (aquí va el resto de tu lógica de paneles que ya hicimos)
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkTodos = document.getElementById('t_todos');
        const otrosChecks = document.querySelectorAll('.tipo-checkbox');

        if (checkTodos) {
            checkTodos.addEventListener('change', function() {
                otrosChecks.forEach(checkbox => {
                    if (this.checked) {
                        // Si "Todos" está marcado:
                        checkbox.checked = false;    // Desmarcar
                        checkbox.disabled = true;    // Anular/Bloquear
                        checkbox.parentElement.classList.add('opacity-50'); // Efecto visual de deshabilitado
                    } else {
                        // Si "Todos" se desmarca:
                        checkbox.disabled = false;   // Habilitar
                        checkbox.parentElement.classList.remove('opacity-50');
                    }
                });
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const mainTriggers = document.querySelectorAll('.main-trigger');
    const capaEncargado = document.getElementById('capa_encargado');
    const subContainer = document.getElementById('sub_opciones_container');
    const groupSubOpciones = document.getElementById('group_sub_opciones');
    const selectEncargado = document.getElementById('select_encargado');
    const panelesFinales = document.getElementById('paneles_finales');

    // Configuración de botones por nivel
    const config = {
        capa_mbos: [
            { id: 'opt_no', val: 'iglesia', text: 'Iglesia' },
            { id: 'opt_dist', val: 'panel_distrito', text: 'Distrito' },
            { id: 'opt_zona', val: 'panel_zona', text: 'Zona' }
        ],
        capa_encargado: [
            { id: 'opt_no', val: 'iglesia', text: 'Iglesia' },
            { id: 'opt_dist', val: 'panel_distrito', text: 'Distrito' },
            { id: 'opt_zona', val: 'panel_zona', text: 'Zona' }
        ]
    };

    // 1. Cambio Principal (MBOS vs Encargado)
    mainTriggers.forEach(t => {
        t.addEventListener('change', function() {
            resetTodo();
            if (this.value === 'capa_mbos') {
                capaEncargado.classList.add('d-none');
                renderSubOpciones('capa_mbos');
            } else {
                capaEncargado.classList.remove('d-none');
            }
        });
    });

    // 2. Si elige Encargado, esperar a que seleccione uno de la lista
    selectEncargado.addEventListener('change', function() {
        if (this.value !== "") {
            renderSubOpciones('capa_encargado');
            filtrarDistritosPorEncargado(this.value);
        } else {
            subContainer.classList.add('d-none');
        }
    });

    function renderSubOpciones(tipo) {
        subContainer.classList.remove('d-none');
        groupSubOpciones.innerHTML = '';
        
        config[tipo].forEach(opt => {
            const radio = document.createElement('input');
            radio.type = 'radio';
            radio.className = 'btn-check sub-trigger';
            radio.name = 'sub_nivel_tipo';
            radio.id = opt.id;
            radio.value = opt.val;

            if (opt.val === 'iglesia') {
                radio.checked = true;
            }

            const label = document.createElement('label');
            label.className = 'btn btn-outline-success';
            label.htmlFor = opt.id;
            label.innerText = opt.text;

            radio.addEventListener('change', function() {
                mostrarPanelFinal(this.value);
            });

            groupSubOpciones.appendChild(radio);
            groupSubOpciones.appendChild(label);
        });
    }

    function mostrarPanelFinal(panelId) {
        // Ocultar todos
        document.querySelectorAll('.final-panel').forEach(p => p.classList.add('d-none'));
        
        if (panelId === 'iglesia') {
            panelesFinales.classList.add('d-none');
        } else {
            panelesFinales.classList.remove('d-none');
            document.getElementById(panelId).classList.remove('d-none');
        }
    }

    function filtrarDistritosPorEncargado(idPersona) {
        const items = document.querySelectorAll('.item-distrito');
        items.forEach(item => {
            // Si idPersona es null (MBOS), mostrar todos. Si no, filtrar.
            if (!idPersona || item.getAttribute('data-responsable') == idPersona) {
                item.classList.remove('d-none');
            } else {
                item.classList.add('d-none');
            }
        });
    }

    function resetTodo() {
        subContainer.classList.add('d-none');
        panelesFinales.classList.add('d-none');
        selectEncargado.value = "";
        // Mostrar todos los distritos por defecto al resetear
        document.querySelectorAll('.item-distrito').forEach(i => i.classList.remove('d-none'));
    }
});
</script>
@endpush