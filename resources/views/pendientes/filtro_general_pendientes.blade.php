@extends('template')


@section('title', 'Crear Unidad Educativa')

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
              <div class="col-sm-6"><h3 class="mb-0">Filtrar Pendientes</h3></div>
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
    <form id="filterForm" action="{{ route('remesas.pendientes_filtro_general.pdf')}}" method="POST" class="card-body">
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
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach(['iglesia', 'filial', 'grupo', 'todos'] as $tipo)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tipos[]" value="{{ $tipo }}" id="t_{{ $tipo }}">
                            <label class="form-check-label text-capitalize small" for="t_{{ $tipo }}">{{ $tipo }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label class="form-label fw-bold small">Filtrar por Nivel Institucional</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check filter-trigger" name="nivel_tipo" id="n_iglesia" value="panel_iglesia">
                        <label class="btn btn-outline-primary" for="n_iglesia">Iglesia</label>

                        <input type="radio" class="btn-check filter-trigger" name="nivel_tipo" id="n_distrito" value="panel_distrito">
                        <label class="btn btn-outline-primary" for="n_distrito">Distrito</label>

                        <input type="radio" class="btn-check filter-trigger" name="nivel_tipo" id="n_regiones" value="panel_regiones">
                        <label class="btn btn-outline-primary" for="n_regiones">Regiones</label>

                        <input type="radio" class="btn-check filter-trigger" name="nivel_tipo" id="n_encargado" value="panel_encargado">
                        <label class="btn btn-outline-primary" for="n_encargado">Encargado</label>

                        <input type="radio" class="btn-check filter-trigger" name="nivel_tipo" id="n_zona" value="panel_zona">
                        <label class="btn btn-outline-primary" for="n_zona">Zona</label>
                    </div>
                </div>
            </div>

            <div id="dynamicPanels" class="bg-light p-3 rounded mb-4 d-none">
                
                <div id="panel_iglesia" class="filter-panel d-none row">
                    <p class="small text-muted mb-2">Seleccione Iglesias:</p>
                    @foreach($iglesias->chunk(ceil($iglesias->count() / 4)) as $chunk)
                    <div class="col-md-3">
                        @foreach($chunk as $i)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="iglesias[]" value="{{ $i->id_iglesia }}">
                            <label class="form-check-label small">{{ $i->nombre }}</label>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>

                <div id="panel_distrito" class="filter-panel d-none row">
                    <p class="small text-muted mb-2">Seleccione Distritos:</p>
                    @foreach($distritos->chunk(ceil($distritos->count() / 4)) as $chunk)
                    <div class="col-md-3">
                        @foreach($chunk as $d)
                        <div class="form-check text-capitalize">
                            <input class="form-check-input" type="checkbox" name="distritos[]" value="{{ $d->id_distrito }}">
                            <label class="form-check-label small">{{ $d->nombre }}</label>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>

                <div id="panel_regiones" class="filter-panel d-none row text-center">
                    @for($i = 1; $i <= 10; $i++)
                    <div class="col-md-2 col-4 mb-2">
                        <input type="checkbox" class="btn-check" name="regiones[]" id="reg_{{ $i }}" value="{{ $i }}">
                        <label class="btn btn-sm btn-outline-secondary w-100" for="reg_{{ $i }}">R-{{ $i }}</label>
                    </div>
                    @endfor
                </div>

                <div id="panel_encargado" class="filter-panel d-none">
                    <select class="form-select" name="encargado_id">
                        <option value="">-- Seleccione Encargado --</option>
                        @foreach($encargados as $e)
                        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="panel_zona" class="filter-panel d-none d-flex justify-content-center gap-4">
                    @foreach(['Altiplano', 'El Alto', 'Todos'] as $z)
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
    const filterTriggers = document.querySelectorAll('.filter-trigger');
    const panels = document.querySelectorAll('.filter-panel');
    const dynamicWrapper = document.getElementById('dynamicPanels');

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

@endpush