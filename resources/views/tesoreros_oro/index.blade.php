@extends('template')

@section('title', 'Reporte Tesoreros de Oro')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

    <style>
        :root {
            --primary-gold: #b8860b;
            --dark-navy: #0d47a1;
            --soft-bg: #f8fafc;
            --border-color: #e2e8f0;
        }

        .card-filter {
            background: #ffffff;
            border-radius: 20px;
            border: 2px solid var(--border-color);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            height: 100%;
        }

        .section-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--dark-navy);
            font-weight: 700;
            margin-bottom: 1rem;
        }

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

        /* Checkboxes Tipo Iglesia (Chips) */
        .tipo-selector { display: none; }
        .tipo-label {
            display: block;
            padding: 10px 5px;
            background: #f1f5f9;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.3s ease;
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

        /* Checkboxes Cuadrados Tradicionales Custom */
        .custom-checkbox .form-check-input {
            width: 1.5em;
            height: 1.5em;
            margin-right: 0.5em;
            border: 2px solid var(--dark-navy);
        }
        .custom-checkbox .form-check-input:checked {
            background-color: var(--dark-navy);
            border-color: var(--dark-navy);
        }
        .custom-checkbox .form-check-label {
            font-weight: 600;
            padding-top: 0.3em;
        }

        /* Tabs Alcance (Segmented Control) */
        .btn-check:checked + .btn-outline-primary {
            background-color: var(--dark-navy) !important;
            border-color: var(--dark-navy) !important;
            color: white !important;
            font-weight: 700;
        }
        .btn-outline-primary {
            border: 2px solid var(--border-color) !important;
            color: #475569;
            background: white;
            border-radius: 10px !important;
            padding: 8px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* Panel de Alcance Dinámico */
        .alcance-panel {
            border: 2px solid var(--dark-navy);
            border-radius: 0 0 12px 12px;
            border-top: none;
            padding: 15px;
            min-height: 120px;
            background: var(--soft-bg);
            margin-top: -5px; /* Para fusionar con los botones */
        }

        /* Toggle Switch Custom */
        .switch-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-switch .form-check-input {
            width: 3.5em;
            height: 1.8em;
            cursor: pointer;
        }
        .form-switch .form-check-input:checked {
            background-color: var(--dark-navy);
            border-color: var(--dark-navy);
        }
        .switch-text {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--dark-navy);
            min-width: 30px;
        }

        /* Ordenamiento Direction Buttons */
        .btn-dir {
            font-size: 1.5rem;
            color: #94a3b8;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
            padding: 5px 15px;
            border-radius: 10px;
        }
        .dir-selector:checked + .btn-dir {
            color: var(--dark-navy);
            border-color: var(--dark-navy);
            background: rgba(13, 71, 161, 0.05);
        }

        /* Botones de acción */
        .btn-action {
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 700;
            letter-spacing: 1px;
            border: 2px solid black;
            background: white;
            color: black;
            transition: all 0.3s;
        }
        .btn-action:hover {
            background: #f1f5f9;
        }


        /* Tarjetas interactivas para el Ordenamiento */
        .sort-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            padding: 4px 5px;
        }
        .sort-card:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        .sort-card .icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            transition: all 0.2s;
        }
        /* Estado Activo (Seleccionado) */
        .btn-check:checked + .sort-card {
            border-color: var(--dark-navy);
            background: rgba(13, 71, 161, 0.03);
            box-shadow: 0 4px 15px rgba(13, 71, 161, 0.1);
        }
        .btn-check:checked + .sort-card .icon-box {
            background: var(--dark-navy);
            color: white !important;
        }
        .btn-check:checked + .sort-card .sort-title {
            color: var(--dark-navy);
        }

        /* Estilos específicos para Botones Exportar */
        .btn-export {
            border-radius: 12px;
            padding: 12px 35px;
            font-weight: 700;
            letter-spacing: 1px;
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .btn-pdf {
            background: #dc2626; /* Rojo vibrante */
        }
        .btn-pdf:hover {
            background: #b91c1c;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(220, 38, 38, 0.3);
        }
        .btn-excel {
            background: #16a34a; /* Verde esmeralda */
        }
        .btn-excel:hover {
            background: #15803d;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(22, 163, 74, 0.3);
        }

    </style>
@endpush

@section('content')
<div class="app-content-header p-3 pb-0">
    <div class="container-fluid">
        <h3 class="mb-0 fw-bold">REPORTE TESOREROS DE ORO</h3>
    </div>
</div>

<div class="app-content mt-3">
    <div class="container-fluid">
        <form id="filterForm" action="{{ route('remesas.tesoreros_oro_pdf') }}" method="POST" target="_blank">
            @csrf
            <div class="row g-4">

                <!-- COLUMNA IZQUIERDA -->
                <div class="col-md-6">
                    <div class="card card-filter p-4">

                        <!-- 1. Periodo -->
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Periodo</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="small text-muted">Inicio</label>
                                    <select name="periodo_inicio" id="periodo_inicio" class="form-select form-select-custom"></select>
                                </div>
                                <div class="col-6">
                                    <label class="small text-muted">Final</label>
                                    <select name="periodo_fin" id="periodo_fin" class="form-select form-select-custom"></select>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Tipo Tesorero -->
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Tipo</label>
                            <div class="d-flex flex-wrap gap-4">
                                <div class="form-check custom-checkbox">
                                    <input class="form-check-input tesorero-check" type="checkbox" name="tipo_tesorero[]" value="oro" id="teso_oro">
                                    <label class="form-check-label" for="teso_oro">Tesoreros de Oro</label>
                                </div>
                                <div class="form-check custom-checkbox">
                                    <input class="form-check-input tesorero-check" type="checkbox" name="tipo_tesorero[]" value="plata" id="teso_plata">
                                    <label class="form-check-label" for="teso_plata">Tesoreros de Plata</label>
                                </div>
                                <div class="form-check custom-checkbox">
                                    <input class="form-check-input" type="checkbox" name="tipo_tesorero[]" value="ambos" id="teso_ambos">
                                    <label class="form-check-label" for="teso_ambos">Ambos</label>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Alcance -->
                        <div>
                            <label class="fw-bold mb-2">Alcance</label>
                            <div class="btn-group w-100" role="group" style="z-index: 2; position: relative;">
                                <input type="radio" class="btn-check alcance-trigger" name="alcance" id="alc_mbos" value="mbos" checked>
                                <label class="btn btn-outline-primary" style="border-bottom-left-radius: 0!important; border-bottom-right-radius: 0!important;" for="alc_mbos">MBOS</label>

                                <input type="radio" class="btn-check alcance-trigger" name="alcance" id="alc_distrito" value="distrito">
                                <label class="btn btn-outline-primary" style="border-bottom-left-radius: 0!important; border-bottom-right-radius: 0!important;" for="alc_distrito">Distrito</label>

                                <input type="radio" class="btn-check alcance-trigger" name="alcance" id="alc_encargado" value="encargado">
                                <label class="btn btn-outline-primary" style="border-bottom-left-radius: 0!important; border-bottom-right-radius: 0!important;" for="alc_encargado">Encargado</label>

                                <input type="radio" class="btn-check alcance-trigger" name="alcance" id="alc_region" value="region">
                                <label class="btn btn-outline-primary" style="border-bottom-left-radius: 0!important; border-bottom-right-radius: 0!important;" for="alc_region">Región</label>
                            </div>

                            <!-- Paneles Dinámicos de Alcance -->
                            <div class="alcance-panel">
                                <!-- MBOS (Vacío) -->
                                <div id="panel_mbos" class="alcance-content">
                                    <p class="text-muted text-center mt-3">El Reporte tomara en cuenta a todas las iglesias de la Mision Boliviana Occidentar Sur.</p>
                                </div>

                                <!-- Distrito -->
                                <div id="panel_distrito" class="alcance-content d-none">
                                    <div class="row g-2 mt-2"> <!-- Se quitó el max-height y el overflow -->
                                        @foreach($distritos as $distrito)
                                            <div class="col-4">
                                                <div class="form-check custom-checkbox">
                                                    <input class="form-check-input" type="checkbox" name="distritos[]" value="{{ $distrito->id_distrito }}" id="dist_{{ $distrito->id_distrito }}">
                                                    <label class="form-check-label small" for="dist_{{ $distrito->id_distrito }}">
                                                        {{ $distrito->nombre }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Encargado -->
                                <div id="panel_encargado" class="alcance-content d-none">
                                    <select class="form-select form-select-custom w-100" name="encargado_id">
                                        <option value="">-- Buscar Responsable --</option>
                                        @foreach($encargados as $encargado)
                                            <option value="{{ $encargado->id_persona }}">
                                                {{ $encargado->nombre }} {{ $encargado->ape_paterno }} {{ $encargado->ape_materno }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Región -->
                                <div id="panel_region" class="alcance-content d-none d-flex justify-content-center gap-4 mt-3">
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="radio" name="region_zona" value="El Alto" id="reg_elalto" checked>
                                        <label class="form-check-label" for="reg_elalto">El Alto</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="radio" name="region_zona" value="Altiplano" id="reg_altiplano">
                                        <label class="form-check-label" for="reg_altiplano">Altiplano</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- COLUMNA DERECHA -->
                <div class="col-md-6">
                    <div class="card card-filter p-4">
                        <div class="section-title">Filtros para el reporte</div>

                        <!-- Tipo de iglesia -->
                        <div class="mb-5 mt-3">
                            <label class="fw-bold mb-1">Tipo de Iglesia</label>
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <input class="tipo-selector tipo-iglesia-check" type="checkbox" name="tipos_iglesia[]" value="Iglesia" id="ti_iglesia">
                                    <label class="tipo-label" for="ti_iglesia">
                                        <i class="fas fa-church d-block mb-2 fa-lg text-success"></i> IGLESIA
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input class="tipo-selector tipo-iglesia-check" type="checkbox" name="tipos_iglesia[]" value="Grupo" id="ti_grupo">
                                    <label class="tipo-label" for="ti_grupo">
                                        <i class="fas fa-users d-block mb-2 fa-lg text-primary"></i> GRUPO
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input class="tipo-selector tipo-iglesia-check" type="checkbox" name="tipos_iglesia[]" value="Filial" id="ti_filial">
                                    <label class="tipo-label" for="ti_filial">
                                        <i class="fas fa-place-of-worship d-block mb-2 fa-lg text-warning"></i> FILIAL
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input class="tipo-selector" type="checkbox" name="tipos_iglesia[]" value="Todos" id="ti_todos">
                                    <label class="tipo-label border-primary border-opacity-25" for="ti_todos">
                                        <i class="fas fa-check-double d-block mb-2 fa-lg text-info"></i> TODOS
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Formato de Reporte -->
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Formato del Reporte</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="tipo_reporte" id="rep_detalles" value="detalles" checked>
                                <label class="btn btn-outline-primary" for="rep_detalles">Con Detalles</label>

                                <input type="radio" class="btn-check" name="tipo_reporte" id="rep_lista" value="lista">
                                <label class="btn btn-outline-primary" for="rep_lista">Solo Lista</label>

                                <input type="radio" class="btn-check" name="tipo_reporte" id="rep_todas" value="todas">
                                <label class="btn btn-outline-primary" for="rep_todas">Todas las Iglesias</label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="fw-bold mb-1 d-block text-uppercase text-secondary" style="font-size: 0.8rem; letter-spacing: 1px;">
                                Ordenamiento
                            </label>

                            <div class="p-3 bg-light rounded-4 border shadow-sm">
                                <div class="row g-3">

                                    <!-- 1. Criterio Principal -->
                                    <div class="col-md-7">
                                        <div class="d-flex flex-column gap-2">
                                            <input type="radio" class="btn-check" name="ordenar_por" id="ord_punt" value="puntuacion" checked>
                                            <label class="sort-card m-0" for="ord_punt">
                                                <!-- Estrella Dorada -->
                                                <div class="icon-box" style="color: var(--primary-gold);">
                                                    <i class="fas fa-star fa-lg"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold sort-title">Puntuación Total</div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">Ordena según la calificación obtenida</div>
                                                </div>
                                            </label>

                                            <input type="radio" class="btn-check" name="ordenar_por" id="ord_igl" value="nombre_iglesia">
                                            <label class="sort-card m-0" for="ord_igl">
                                                <div class="icon-box text-success">
                                                    <i class="fas fa-church fa-lg"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold sort-title">Nombre de Iglesia</div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">Orden alfabético según el nombre de iglesias</div>
                                                </div>
                                            </label>

                                            <!-- Tarjeta Distrito (Agrupación de Iglesias en Pirámide) -->
                                            <input type="radio" class="btn-check" name="ordenar_por" id="ord_dist" value="nombre_distrito">
                                            <label class="sort-card m-0" for="ord_dist">
                                                <div class="icon-box text-info d-flex flex-column align-items-center justify-content-center pt-1" style="gap: 1px;">
                                                    <!-- 1 Iglesia Arriba -->
                                                    <i class="fas fa-church" style="font-size: 0.6rem;"></i>
                                                    <!-- 2 Iglesias Abajo -->
                                                    <div class="d-flex" style="gap: 4px;">
                                                        <i class="fas fa-church" style="font-size: 0.6rem;"></i>
                                                        <i class="fas fa-church" style="font-size: 0.6rem;"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold sort-title">Nombre de Distrito</div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">Orden Alfabetico según el nombre del distrito</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- 2. Dirección (Asc/Desc) -->
                                    <div class="col-md-5 d-flex flex-column gap-2 justify-content-center">

                                        <input type="radio" class="btn-check" name="orden_dir" id="dir_asc" value="asc" checked>
                                        <label class="sort-card m-0 p-2 d-flex flex-row align-items-center" for="dir_asc">
                                            <div class="text-secondary mx-3">
                                                <i class="fas fa-sort-amount-up fa-2x"></i>
                                            </div>
                                            <div class="text-start">
                                                <div class="fw-bold sort-title" style="font-size: 0.85rem;">Ascendente</div>
                                                <div class="text-muted mt-1" style="font-size: 0.65rem;">(A-Z / Menor a Mayor)</div>
                                                <div class="text-muted mt-1" style="font-size: 0.65rem;">(0-9 / Menor a Mayor)</div>
                                            </div>
                                        </label>

                                        <input type="radio" class="btn-check" name="orden_dir" id="dir_desc" value="desc">
                                        <label class="sort-card m-0 p-2 d-flex flex-row align-items-center" for="dir_desc">
                                            <div class="text-secondary mx-3">
                                                <i class="fas fa-sort-amount-down fa-2x"></i>
                                            </div>
                                            <div class="text-start">
                                                <div class="fw-bold sort-title" style="font-size: 0.85rem;">Descendente</div>
                                                <div class="text-muted mt-1" style="font-size: 0.65rem;">(Z-A / Mayor a Menor)</div>
                                                <div class="text-muted mt-1" style="font-size: 0.65rem;">(9-0 / Mayor a Menor)</div>
                                            </div>
                                        </label>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Botones Exportar -->
            <div class="row mt-4 mb-5">
                <div class="col-12 d-flex justify-content-center gap-4">
                    <button type="submit" name="action" value="pdf" class="btn btn-export btn-pdf d-flex align-items-center gap-2">
                        <i class="fas fa-file-pdf fa-xl"></i> PDF
                    </button>
                    <button type="submit" name="action" value="excel" class="btn btn-export btn-excel d-flex align-items-center gap-2">
                        <i class="fas fa-file-excel fa-xl"></i> EXCEL
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');

    // ==========================================
    // A. VALIDACIÓN Y LIMPIEZA ANTES DE ENVIAR
    // ==========================================
    form.addEventListener('submit', function(e) {
        // 1. VALIDACIÓN: Tipo de Tesorero
        const tesorerosCheck = document.querySelectorAll('input[name="tipo_tesorero[]"]:checked');
        if (tesorerosCheck.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Faltan datos',
                text: 'Debe seleccionar al menos un tipo de tesorero (Oro, Plata o Ambos).'
            });
            return false;
        }

        // 1.5 VALIDACIÓN: Tipo de Iglesia (NUEVO)
        const iglesiasCheck = document.querySelectorAll('input[name="tipos_iglesia[]"]:checked');
        if (iglesiasCheck.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Faltan datos',
                text: 'Debe seleccionar al menos un tipo de iglesia (Iglesia, Grupo, Filial o Todos).'
            });
            return false;
        }

        const alcanceSeleccionado = document.querySelector('input[name="alcance"]:checked').value;

        // 2. VALIDACIÓN: Distritos y Encargados
        if (alcanceSeleccionado === 'distrito') {
            const distritosCheck = document.querySelectorAll('input[name="distritos[]"]:checked');
            if (distritosCheck.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Faltan datos',
                    text: 'Debe seleccionar por lo menos un distrito.'
                });
                return false;
            }
        } else if (alcanceSeleccionado === 'encargado') {
            const encargadoVal = document.querySelector('select[name="encargado_id"]').value;
            if (encargadoVal === "") {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Faltan datos',
                    text: 'Debe seleccionar un responsable de la lista.'
                });
                return false;
            }
        }

        // 3. LIMPIEZA DEL REQUEST
        const paneles = ['mbos', 'distrito', 'encargado', 'region'];
        paneles.forEach(panel => {
            const elementos = document.querySelectorAll(`#panel_${panel} input, #panel_${panel} select`);
            if (panel !== alcanceSeleccionado) {
                elementos.forEach(el => el.disabled = true);
            } else {
                elementos.forEach(el => el.disabled = false);
            }
        });

        // 4. RESTAURAR CAMPOS
        setTimeout(() => {
            paneles.forEach(panel => {
                const elementos = document.querySelectorAll(`#panel_${panel} input, #panel_${panel} select`);
                elementos.forEach(el => el.disabled = false);
            });
        }, 500);
    }); // <--- AQUÍ FALTABA ESTE CIERRE IMPORTANTE

    // ==========================================
    // B. FUNCIONES DE LA INTERFAZ (TABS, MESES, ETC)
    // ==========================================

    // --- 1. LÓGICA DE PERIODOS (Ene 2026 - Dic 2026) ---
    const meses = [
        { id: 1, text: 'Enero 2026' }, { id: 2, text: 'Febrero 2026' },
        { id: 3, text: 'Marzo 2026' }, { id: 4, text: 'Abril 2026' },
        { id: 5, text: 'Mayo 2026' }, { id: 6, text: 'Junio 2026' },
        { id: 7, text: 'Julio 2026' }, { id: 8, text: 'Agosto 2026' },
        { id: 9, text: 'Septiembre 2026' }, { id: 10, text: 'Octubre 2026' },
        { id: 11, text: 'Noviembre 2026' }, { id: 12, text: 'Diciembre 2026' }
    ];

    const selInicio = document.getElementById('periodo_inicio');
    const selFin = document.getElementById('periodo_fin');

    meses.forEach(m => selInicio.add(new Option(m.text, m.id)));
    selInicio.value = 1;

    function actualizarPeriodoFin() {
        const idInicio = parseInt(selInicio.value);
        const idFinActual = parseInt(selFin.value);
        selFin.innerHTML = '';
        meses.forEach(m => {
            if (m.id >= idInicio) selFin.add(new Option(m.text, m.id));
        });
        if (idFinActual && idFinActual >= idInicio) {
            selFin.value = idFinActual;
        } else {
            selFin.value = 12;
        }
    }
    selInicio.addEventListener('change', actualizarPeriodoFin);
    actualizarPeriodoFin();

    // --- 2. LÓGICA TIPO DE TESORERO ---
    const checkOro = document.getElementById('teso_oro');
    const checkPlata = document.getElementById('teso_plata');
    const checkAmbos = document.getElementById('teso_ambos');

    function evaluarChecksIndividuales() {
        if (checkOro.checked && checkPlata.checked) {
            checkAmbos.checked = true;
            manejarAmbos();
        }
    }

    function manejarAmbos() {
        if (checkAmbos.checked) {
            checkOro.checked = true;
            checkPlata.checked = true;
            checkOro.disabled = true;
            checkPlata.disabled = true;
            checkOro.parentElement.classList.add('opacity-50');
            checkPlata.parentElement.classList.add('opacity-50');
        } else {
            checkOro.disabled = false;
            checkPlata.disabled = false;
            checkOro.parentElement.classList.remove('opacity-50');
            checkPlata.parentElement.classList.remove('opacity-50');
            checkOro.checked = false;
            checkPlata.checked = false;
        }
    }
    checkOro.addEventListener('change', evaluarChecksIndividuales);
    checkPlata.addEventListener('change', evaluarChecksIndividuales);
    checkAmbos.addEventListener('change', manejarAmbos);

    // --- 3. LÓGICA ALCANCE (TABS) ---
    const alcanceTriggers = document.querySelectorAll('.alcance-trigger');
    const panelesAlcance = document.querySelectorAll('.alcance-content');

    alcanceTriggers.forEach(trigger => {
        trigger.addEventListener('change', function() {
            panelesAlcance.forEach(p => p.classList.add('d-none'));
            document.getElementById('panel_' + this.value).classList.remove('d-none');

            if(this.value === 'distrito') {
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });

    // --- 4. LÓGICA TIPO DE IGLESIA ---
    const checkTodosIgl = document.getElementById('ti_todos');
    const otrosChecksIgl = document.querySelectorAll('.tipo-iglesia-check');

    checkTodosIgl.addEventListener('change', function() {
        otrosChecksIgl.forEach(checkbox => {
            if (this.checked) {
                checkbox.checked = false;
                checkbox.disabled = true;
            } else {
                checkbox.disabled = false;
            }
        });
    });

    // --- 5. LÓGICA SWITCH DETALLES ---
    const switchDetalles = document.getElementById('switchDetalles');
    const lblDetalles = document.getElementById('lblDetalles');

    switchDetalles.addEventListener('change', function() {
        if (this.checked) {
            lblDetalles.textContent = 'Si';
            lblDetalles.classList.add('text-success');
        } else {
            lblDetalles.textContent = 'No';
            lblDetalles.classList.remove('text-success');
        }
    });
});
</script>
@endpush
