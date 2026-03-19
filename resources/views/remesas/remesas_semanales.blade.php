@extends('template')


@section('title', 'Tablas')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Estilo para los inputs dentro del header para que no parezcan "cajas" viejas */
    .modern-input {
        background: rgba(255, 255, 255, 0.15) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        border-radius: 8px !important;
        backdrop-filter: blur(4px);
        transition: all 0.3s ease;
    }
    .modern-input:focus {
        background: rgba(255, 255, 255, 0.25) !important;
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.1) !important;
        transform: translateY(-1px);
    }
    .modern-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        margin-bottom: 4px;
        display: block;
        color: rgba(255, 255, 255, 0.8);
    }
    .badge-result {
        border-radius: 8px;
        padding: 5px 10px;
        display: block;
    }
</style>
    @endpush

@section('content')

<x-alerts/>
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Regristro Semanal</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Remesas</li>
                </ol>
              </div>
               
<x-alerts />
            
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <div class="container-fluid py-4">
                <form action="{{ route('remesas.guardar_todo', $remesa->id_remesa) }}" method="POST" id="formRegistro">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4><i class="bi bi-calendar-check me-2"></i>Registro Semanal: {{ $remesa->nombre_mes }}</h4>
                                <span class="badge bg-primary fs-6">{{ $iglesia->nombre }}</span>
                            </div>

                            @for ($s = 1; $s <= 5; $s++)
                                <div class="card shadow-sm mb-4 border-0 semana-card" data-semana="{{ $s }}" style="border-radius: 12px; border: 1px solid #dee2e6;">
                                    <div class="card-header py-4" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); border: none; border-radius: 15px 15px 0 0; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                    <div class="row align-items-center">
                                        <div class="col-xl-2 col-lg-12 mb-3 mb-xl-0">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-white bg-opacity-10 rounded-3 me-3">
                                                    <i class="bi bi-calendar3 fs-4 text-white"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0 fw-bold text-white text-uppercase" style="letter-spacing: 0.5px;">Semana</h5>
                                                    <span class="fs-4 fw-black text-white" style="line-height: 1;">{{ $s }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-10 col-lg-12">
                                            <div class="row g-3 justify-content-end text-center">
                                                <div class="col-md-2">
                                                    <label class="modern-label">Diezmo</label>
                                                    <input type="number" name="resumen_diezmo[{{ $s }}]" class="form-control form-control-sm modern-input text-center fw-bold res-diezmo excel-input" step="0.01" value="0" min="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="modern-label">Ofrenda (Gral)</label>
                                                    <input type="number" name="resumen_ofrenda[{{ $s }}]" class="form-control form-control-sm modern-input text-center fw-bold res-ofrenda excel-input" step="0.01" value="0" min="0">
                                                </div>
                                                <div class="col-md-2 border-end border-white border-opacity-10">
                                                    <label class="modern-label">Pro-Templo</label>
                                                    <input type="number" name="resumen_pro[{{ $s }}]" class="form-control form-control-sm modern-input text-center fw-bold res-pro excel-input" step="0.01" value="0" min="0">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="modern-label" style="color: #ffd700;">Remesa MBOS</label>
                                                    <input type="text" class="form-control form-control-sm res-remesa-mbos text-center fw-bold border-0 shadow-sm" style="background-color: #fefce8; color: #854d0e; border-radius: 8px;" readonly value="0.00">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="modern-label" style="color: #6ee7b7;">Fondo Local</label>
                                                    <input type="text" class="form-control form-control-sm res-fondo-local text-center fw-bold border-0 shadow-sm" style="background-color: #f0fdf4; color: #166534; border-radius: 8px;" readonly value="0.00">
                                                </div>
                                                <div class="col-md-2">
                                                        <label class="modern-label" style="color: #fca5a5;">Total Semana</label>
                                                        <input type="text" class="form-control form-control-sm res-total text-center fw-bold border-0 shadow-sm" style="background-color: #111827; color: #10b981; border-radius: 8px;" readonly value="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover mb-0" id="tabla-sem-{{ $s }}">
                                                <thead style="background-color: #f8fafc;">
                                                    <tr class="text-center small fw-bold text-secondary">
                                                        <th class="py-2 border-0">Diezmo</th>
                                                        <th class="py-2 border-0">Ofrenda</th>
                                                        <th class="py-2 border-0">Pacto</th>
                                                        <th class="py-2 border-0">Especiales</th>
                                                        <th class="py-2 border-0">Pro-Templo</th>
                                                        <th class="py-2 border-0" width="50px"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="cuerpo-semana" data-semana="{{ $s }}"></tbody>
                                                
                                                <tfoot class="d-none footer-totales" style="background-color: #fef9c3; border-top: 2px solid #facc15;">
                                                    <tr class="fw-bold text-center" style="font-size: 0.9rem; color: #854d0e;">
                                                        <td id="total-d-{{ $s }}">0.00</td>
                                                        <td id="total-o-{{ $s }}">0.00</td>
                                                        <td id="total-p-{{ $s }}">0.00</td>
                                                        <td id="total-e-{{ $s }}">0.00</td>
                                                        <td id="total-pt-{{ $s }}">0.00</td>
                                                        <td><i class="bi bi-calculator-fill opacity-50"></i></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-white border-top-0 py-3">
                                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm btn-agregar-fila" data-semana="{{ $s }}">
                                            <i class="bi bi-list-ol me-1"></i> Desglosar recibos
                                        </button>
                                        <small class="text-muted ms-3 d-none msg-bloqueo">
                                            <span class="badge rounded-pill bg-light text-primary border px-2">
                                                <i class="bi bi-lock-fill me-1"></i> Totales calculados por detalle
                                            </span>
                                        </small>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow-lg border-0 sticky-top" style="top: 20px; border-radius: 20px; overflow: hidden; background: #ffffff;">
                                <div class="card-header py-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: none;">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 bg-primary bg-opacity-25 rounded-3 me-3">
                                            <i class="bi bi-graph-up-arrow text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold text-white small text-uppercase" style="letter-spacing: 1px;">Resumen del Mes</h5>
                                            <small class="text-white-50">Cierre de Gestión Pastoral</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body p-4">
                                    <div class="row g-3 mb-4">
                                        <div class="col-6">
                                            <label class="modern-label text-dark opacity-75">Fecha Entrega</label>
                                            <input type="date" name="fecha_entrega" class="form-control border-0 bg-light fw-bold" value="{{ date('Y-m-d') }}" style="border-radius: 10px;">
                                        </div>
                                        <div class="col-6">
                                            <label class="modern-label text-danger opacity-75">Gasto del Mes</label>
                                            <input type="number" name="gasto_mensual" class="form-control border-0 bg-danger bg-opacity-10 fw-bold text-danger gasto-input" step="0.01" value="0" style="border-radius: 10px;">
                                        </div>
                                    </div>

                                    <hr class="opacity-10">

                                    <div class="space-y-2 mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small fw-bold uppercase">Total Diezmos</span>
                                            <input type="text" class="js-mes-diezmo border-0 bg-transparent text-end fw-bold text-dark" readonly value="0.00" style="width: 120px; outline: none;">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small fw-bold uppercase">Total Ofrendas</span>
                                            <input type="text" class="js-mes-ofrenda border-0 bg-transparent text-end fw-bold text-dark" readonly value="0.00" style="width: 120px; outline: none;">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small fw-bold uppercase">Total Pro-Templo</span>
                                            <input type="text" class="js-mes-pro border-0 bg-transparent text-end fw-bold text-dark" readonly value="0.00" style="width: 120px; outline: none;">
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-4 text-center">
                                        <div class="col-6">
                                            <div class="p-3 rounded-4 shadow-sm" style="background: #fffbeb; border: 1px solid #fef3c7;">
                                                <span class="d-block small fw-bold text-warning text-uppercase mb-1" style="font-size: 0.6rem;">Remesa MBOS Total</span>
                                                <span class="fs-6 fw-bold text-dark" id="mes-res-remesa">Bs 0.00</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 rounded-4 shadow-sm" style="background: #f0fdf4; border: 1px solid #dcfce7;">
                                                <span class="d-block small fw-bold text-success text-uppercase mb-1" style="font-size: 0.6rem;">Fondo Local Total</span>
                                                <span class="fs-6 fw-bold text-dark" id="mes-res-fondo-local">Bs 0.00</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-4 rounded-4 mb-4" style="background: #f8fafc; border: 1px dashed #e2e8f0;">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="small fw-bold text-muted text-uppercase">Total sin Gastos</span>
                                            <span class="fw-bold text-dark" id="total-bruto">Bs 0.00</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center pt-3 border-top border-2">
                                            <div>
                                                <span class="d-block small fw-bold text-uppercase text-primary">Saldo Neto</span>
                                                <small class="text-muted" style="font-size: 0.6rem;">(Ingresos - Gastos)</small>
                                            </div>
                                            <span class="fs-4 fw-black" id="res-saldo" style="color: #3b82f6;">Bs 0.00</span>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-lg border-0 mb-2" style="border-radius: 12px; transition: all 0.3s ease;">
                                        <i class="bi bi-cloud-check-fill me-2"></i>GUARDAR REGISTRO MENSUAL
                                    </button>
                                    <a href="{{ route('remesas.index') }}" class="btn btn-link w-100 text-muted small text-decoration-none">
                                        <i class="bi bi-arrow-left me-1"></i> Regresar al historial
                                    </a>
                                </div>
                            </div>
                        </div>

                        <style>
                            .fw-black { font-weight: 900; }
                            .input-resumen { width: 120px; outline: none; }
                            #res-saldo.text-danger { color: #dc3545 !important; }
                        </style>
                    </div>
                </form>
            </div>

            <style>
                .input-resumen { border: none; background: transparent; text-align: right; font-weight: bold; width: 100px; outline: none; }
                .table td { padding: 0.5rem; }
                .form-control-sm { border-color: #e9ecef; }
                .form-control-sm:focus { border-color: #3b82f6; box-shadow: none; }
            </style>
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
$(document).ready(function() {

    // 1. Generador de filas (Aseguramos clases correctas)
    function crearFila(s) {
        return `
            <tr class="fila-detalle">
                <td><input type="number" name="semana[${s}][diezmo][]" class="form-control form-control-sm val-d text-center border-0 bg-light input-detalle excel-input" step="0.01" value="0" min="0"></td>
                <td><input type="number" name="semana[${s}][ofrenda][]" class="form-control form-control-sm val-o text-center border-0 bg-light input-detalle excel-input" step="0.01" value="0" min="0"></td>
                <td><input type="number" name="semana[${s}][pacto][]" class="form-control form-control-sm val-p text-center border-0 bg-light input-detalle excel-input" step="0.01" value="0" min="0"></td>
                <td><input type="number" name="semana[${s}][especiales][]" class="form-control form-control-sm val-e text-center border-0 bg-light input-detalle excel-input" step="0.01" value="0" min="0" ></td>
                <td><input type="number" name="semana[${s}][pro_templo][]" class="form-control form-control-sm val-pt text-center border-0 bg-light input-detalle excel-input" step="0.01" value="0" min="0"></td>
                <td class="text-center"><button type="button" class="btn btn-sm text-danger btn-remove"><i class="bi bi-trash-fill"></i></button></td>
            </tr>`;
    }

    // 2. Evento Botón: Desglosar Recibos
    $(document).on('click', '.btn-agregar-fila', function(e) {
        e.preventDefault();
        let s = $(this).data('semana');
        let card = $(this).closest('.semana-card');
        let cuerpo = card.find('.cuerpo-semana');

        if (cuerpo.children().length === 0) {
            // LIMPIAR Y BLOQUEAR (Propiedades de jQuery más fuertes)
            card.find('.res-diezmo, .res-ofrenda, .res-pro').val('0.00').prop('readonly', true).css('opacity', '0.6');
            
            // Mostrar elementos de detalle
            card.find('.footer-totales, .msg-bloqueo').removeClass('d-none');
            
            // Añadir 5 filas iniciales
            for (let i = 0; i < 10; i++) { cuerpo.append(crearFila(s)); }
            $(this).html('<i class="bi bi-plus-lg me-1"></i>Añadir fila');
        } else {
            cuerpo.append(crearFila(s));
        }

        recalcularSemana(card, s);
    });

    // 3. Evento unificado para CUALQUIER cambio de entrada
    $(document).on('input', '.res-diezmo, .res-ofrenda, .res-pro, .input-detalle', function() {
        let card = $(this).closest('.semana-card');
        let s = card.data('semana');
        recalcularSemana(card, s);
    });

    // 4. Función de Cálculo (Lógica Blindada)
    function recalcularSemana(card, s) {
        let dFinal = 0, oFinal = 0, ptFinal = 0;
        let filas = card.find('.cuerpo-semana tr');

        if (filas.length > 0) {
            // --- MODO DETALLE: Manda la tabla ---
            let dDet = 0, oDet = 0, pDet = 0, eDet = 0, ptDet = 0;
            
            filas.each(function() {
                dDet += parseFloat($(this).find('.val-d').val()) || 0;
                oDet += parseFloat($(this).find('.val-o').val()) || 0;
                pDet += parseFloat($(this).find('.val-p').val()) || 0;
                eDet += parseFloat($(this).find('.val-e').val()) || 0;
                ptDet += parseFloat($(this).find('.val-pt').val()) || 0;
            });

            dFinal = dDet;
            oFinal = oDet + pDet + eDet; 
            ptFinal = ptDet;

            // Inyectar valores en los 3 campos de cabecera
            card.find('.res-diezmo').val(dFinal.toFixed(2));
            card.find('.res-ofrenda').val(oFinal.toFixed(2));
            card.find('.res-pro').val(ptFinal.toFixed(2));

            // Actualizar footer amarillo de la tabla
            card.find(`#total-d-${s}`).text(dDet.toFixed(2));
            card.find(`#total-o-${s}`).text(oDet.toFixed(2));
            card.find(`#total-p-${s}`).text(pDet.toFixed(2));
            card.find(`#total-e-${s}`).text(eDet.toFixed(2));
            card.find(`#total-pt-${s}`).text(ptDet.toFixed(2));

        } else {
            // --- MODO MANUAL: Mandan los inputs de arriba ---
            dFinal = parseFloat(card.find('.res-diezmo').val()) || 0;
            oFinal = parseFloat(card.find('.res-ofrenda').val()) || 0;
            ptFinal = parseFloat(card.find('.res-pro').val()) || 0;
        }

        // --- CÁLCULOS INSTITUCIONALES (MBOS / LOCAL) ---
        let remesaMBOS = dFinal + (oFinal * 0.4);
        let fondoLocal = ptFinal + (oFinal * 0.6);
        let totalSemanal = dFinal + oFinal + ptFinal;

        // Actualizar los campos de resultado en la cabecera azul
        card.find('.res-remesa-mbos').val(remesaMBOS.toFixed(2));
        card.find('.res-fondo-local').val(fondoLocal.toFixed(2));
        card.find('.res-total').val(totalSemanal.toFixed(2));

        // Actualizar el resumen derecho (Si la función existe)
        if (typeof actualizarResumenMensualGlobal === "function") {
            actualizarResumenMensualGlobal();
        }
    }

    // 5. Eliminar fila y restaurar si queda vacío
    $(document).on('click', '.btn-remove', function() {
        let card = $(this).closest('.semana-card');
        let s = card.data('semana');
        $(this).closest('tr').remove();
        
        if(card.find('.cuerpo-semana tr').length === 0) {
            card.find('.res-diezmo, .res-ofrenda, .res-pro')
                .prop('readonly', false)
                .css('opacity', '1');
            card.find('.footer-totales, .msg-bloqueo').addClass('d-none');
            card.find('.btn-agregar-fila').html('<i class="bi bi-list-ol me-1"></i> Desglosar recibos');
        }
        recalcularSemana(card, s);
    });
    // 5. Eliminar fila y restaurar si queda vacío
    function actualizarResumenMensualGlobal() {
        let acumuladoDiezmo = 0;
        let acumuladoOfrenda = 0;
        let acumuladoProTemplo = 0;
        let acumuladoRemesaMBOS = 0;
        let acumuladoFondoLocal = 0;
        
        // Obtenemos el gasto mensual
        let gastoTotal = parseFloat($('.gasto-input').val()) || 0;

        // "Barremos" las 5 semanas usando las clases js-sem que definimos
        $('.semana-card').each(function() {
            // USANDO TUS CLASES REALES:
            acumuladoDiezmo      += parseFloat($(this).find('.res-diezmo').val()) || 0;
            acumuladoOfrenda     += parseFloat($(this).find('.res-ofrenda').val()) || 0;
            acumuladoProTemplo   += parseFloat($(this).find('.res-pro').val()) || 0;
            
            // También sumamos lo que ya se calculó de MBOS y Local por semana
            acumuladoRemesaMBOS  += parseFloat($(this).find('.res-remesa-mbos').val()) || 0;
            acumuladoFondoLocal  += parseFloat($(this).find('.res-fondo-local').val()) || 0;
        });

        // Cálculos de saldo
        let totalBrutoDelMes = acumuladoDiezmo + acumuladoOfrenda + acumuladoProTemplo;
        let saldoNetoFinal   = totalBrutoDelMes - gastoTotal;

        // --- INYECTAR DATOS EN LA INTERFAZ ---

        // 1. Inputs del desglose (Inputs de texto)
        $('.js-mes-diezmo').val(acumuladoDiezmo.toFixed(2));
        $('.js-mes-ofrenda').val(acumuladoOfrenda.toFixed(2));
        $('.js-mes-pro').val(acumuladoProTemplo.toFixed(2));

        // 2. Distribución Institucional (Cuadritos Amarillo y Verde)
        $('#mes-res-remesa').text(`Bs ${acumuladoRemesaMBOS.toFixed(2)}`);
        $('#mes-res-fondo-local').text(`Bs ${acumuladoFondoLocal.toFixed(2)}`);

        // 3. Saldo Total Bruto
        $('#total-bruto').text(`Bs ${totalBrutoDelMes.toFixed(2)}`);

        // 4. Saldo Neto (Resaltado)
        const spanSaldo = $('#res-saldo');
        spanSaldo.text(`Bs ${saldoNetoFinal.toFixed(2)}`);

        // Estética del saldo: Si es negativo se pone rojo, si es positivo azul/verde
        if (saldoNetoFinal < 0) {
            spanSaldo.css('color', '#ef4444'); // Rojo Fintech
            } else {
                spanSaldo.css('color', '#3b82f6'); // Azul Fintech
            }
    }

    // Escuchar cambios en el Gasto Mensual
    $(document).on('input', '.gasto-input', function() {
        actualizarResumenMensualGlobal();
    });
});

// CONTROL DE TECLAS

document.addEventListener('keydown', function (e) {
    // Verificamos si la tecla presionada es Enter
    if (e.key === 'Enter') {
        const activeElement = document.activeElement;

        // Solo actuamos si estamos dentro de un input de la tabla
        if (activeElement.classList.contains('excel-input')) {
            e.preventDefault(); // Evitamos que el formulario se guarde/envíe

            const currentCell = activeElement.closest('td');
            const currentRow = currentCell.parentElement;
            const table = currentRow.parentElement;
            
            const columnIndex = Array.from(currentRow.children).indexOf(currentCell);
            const rowIndex = Array.from(table.children).indexOf(currentRow);
            
            // Intentamos buscar la celda en la siguiente fila (mismo índice de columna)
            const nextRow = table.children[rowIndex + 1];
            
            if (nextRow) {
                // Hay otra fila abajo: movemos el foco ahí
                const nextInput = nextRow.children[columnIndex].querySelector('.excel-input');
                if (nextInput) nextInput.focus();
            } else {
                // No hay más filas: vamos a la primera fila de la SIGUIENTE columna
                const firstRow = table.children[0];
                const nextColumnCell = firstRow.children[columnIndex + 1];
                
                if (nextColumnCell) {
                    const nextColumnInput = nextColumnCell.querySelector('.excel-input');
                    if (nextColumnInput) nextColumnInput.focus();
                }
            }
        }
    }
});

//PARA QUE SE SELCCIONE Y NO TENGAS QUE BORAR LOS CERROS

document.addEventListener('focusin', function (e) {
    // 1. SELECCIÓN AUTOMÁTICA AL ENTRAR
    // Si el elemento es un input de nuestra tabla, seleccionamos todo su texto
    if (e.target.classList.contains('excel-input')) {
        e.target.select();
    }
});

</script>


@endpush