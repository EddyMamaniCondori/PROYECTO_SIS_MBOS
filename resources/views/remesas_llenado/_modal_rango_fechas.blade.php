<!-- Archivo: resources/views/remesas_llenado/_modal_rango_fechas.blade.php -->
<div class="modal fade" id="modalRangoFechas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tituloModalRango">
                    <i class="bi bi-printer me-2"></i><span id="textoTituloRango">Reporte</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Seleccione el rango de meses para generar el reporte.</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Desde:</label>
                        <select id="rep_desde" class="form-select" disabled>
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hasta:</label>
                        <select id="rep_hasta" class="form-select" disabled>
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0">
                <button type="button" class="btn btn-danger" onclick="generarReporteModal('pdf')">
                    <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                </button>
                <button type="button" class="btn btn-success" onclick="generarReporteModal('excel')">
                    <i class="bi bi-file-earmark-excel me-1"></i> Excel
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
