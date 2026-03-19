@extends('template')


@section('title', 'Filtro Remesas Pendientes')

@push('css')
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    




@endpush

@section('content')
<x-alerts/>


        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0"><strong class="text-primary">Filtrar Pendientes</strong></h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('asea.index')}}">Pendientes</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Filtro</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          

    <div class="card border-0 shadow-sm mb-4">
    <form id="filterForm" action="{{ route('remesas.pendientes_filtro_general.pdf')}}" method="POST" class="card-body" target="_blank">
        @csrf
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-bold small">Periodo Inicial</label>
                <select name="periodo_inicio" id="periodo_inicio" class="form-select">
                    </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold small">Periodo Final</label>
                <select name="periodo_fin" id="periodo_fin" class="form-select">
                    </select>
            </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small d-block">Tipo</label>
                    <div class="row g-2  mt-2">
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input tipo-checkbox" type="checkbox" name="tipos[]" value="Iglesia" id="t_iglesia">
                                <label class="form-check-label text-capitalize " for="t_iglesia"> <i class="bi bi-house-check-fill text-success me-2"></i>Iglesia</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input tipo-checkbox" type="checkbox" name="tipos[]" value="Filial" id="t_filial">
                                <label class="form-check-label text-capitalize " for="t_filial"> <i class="bi bi-house-gear-fill text-warning me-2"></i>Filial</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input tipo-checkbox" type="checkbox" name="tipos[]" value="Grupo" id="t_grupo">
                                <label class="form-check-label text-capitalize " for="t_grupo"> <i class="bi bi-house-fill text-primary me-2"></i>Grupo</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tipos[]" value="todos" id="t_todos">
                                <label class="form-check-label text-capitalize fw-bold" for="t_todos"> <i class="bi bi-clipboard2-check text-info"></i>&nbsp;Todos</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-8">
                    <label class="form-label fw-bold small">Nivel de Filtro:</label>
                    <div class="btn-group w-100 mt-3" role="group">
                        <input type="radio" class="btn-check main-trigger" name="nivel_principal" id="n_mbos" value="capa_mbos">
                        <label class="btn btn-outline-primary" for="n_mbos">MBOS</label>

                        <input type="radio" class="btn-check main-trigger" name="nivel_principal" id="n_encargado_root" value="capa_encargado">
                        <label class="btn btn-outline-primary" for="n_encargado_root">Encargado</label>
                    </div>
                </div>
                <div id="capa_encargado" class="col-4 main-layer d-none  p-3  mb-3 ">
                    <label class="form-label fw-bold small text-primary">Seleccione el Encargado:</label>
                    <select class="form-select" name="encargado_id" id="select_encargado">
                        <option value="">-- Seleccione --</option>
                        @foreach($encargados as $e)
                            <option value="{{ $e->id_persona }}">{{ $e->nombre }} {{ $e->ape_paterno }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            

            <div id="sub_opciones_container" class="d-none mb-3">
                <label class="form-label fw-bold small" id="titulo_sub_opcion">Filtros Adicionales:</label>
                <div class="btn-group w-100" role="group" id="group_sub_opciones">
                    </div>
            </div>

            <div id="paneles_finales" class="p-3 rounded mb-4 border d-none bg-white shadow-sm">
                
                <div id="panel_distrito" class="final-panel d-none">
                    <p class="small text-muted mb-2 fw-bold">Lista de Distritos:</p>
                    <div class="row" id="container_distritos">
                        @foreach($distritos as $d)
                            <div class="col-md-3 item-distrito" data-responsable="{{ $d->id_responsable_remesa }}">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="distritos[]" value="{{ $d->id_distrito }}" id="dist_{{ $d->id_distrito }}">
                                    <label class="form-check-label small" for="dist_{{ $d->id_distrito }}">{{ $d->nombre }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="panel_zona" class="final-panel d-none d-flex justify-content-center gap-4">
                    @foreach(['ALTIPLANO', 'EL ALTO', 'TODOS'] as $z)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="zona" value="{{ $z }}" id="z_{{ $z }}">
                            <label class="form-check-label" for="z_{{ $z }}">{{ $z }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" name="action" value="listar" class="btn btn-primary px-4 shadow-sm">
                    <i class="bi bi-search me-2"></i> Listar
                </button>
                <button type="submit" name="action" value="excel" class="btn btn-success px-4 shadow-sm">
                    <i class="bi bi-file-earmark-excel me-2"></i> Excel
                </button>
            </div>
        </form>
    </div>

          <!--end::Container-->
        </div>
        @endsection



@push('js')

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
  
<script>
document.addEventListener('DOMContentLoaded', function() {
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
            { id: 'opt_no', val: 'no_filtrar', text: 'No Filtrar' },
            { id: 'opt_dist', val: 'panel_distrito', text: 'Distrito' },
            { id: 'opt_zona', val: 'panel_zona', text: 'Zona' }
        ],
        capa_encargado: [
            { id: 'opt_no', val: 'no_filtrar', text: 'No Filtrar' },
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

            if (opt.val === 'no_filtrar') {
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
        
        if (panelId === 'no_filtrar') {
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