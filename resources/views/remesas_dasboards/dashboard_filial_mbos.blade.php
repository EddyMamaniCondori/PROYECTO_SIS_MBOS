
@extends('template') 

@section('title', 'Dashboard filiales')

@push('css')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.4/dist/css/adminlte.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Efecto Border to Solid en botones */
        .btn-custom-outline {
            border: 2px solid #0d47a1;
            color: #0d47a1;
            background: transparent;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 8px 15px;
        }

        .btn-custom-outline:hover, .btn-custom-outline.active {
            background-color: #0d47a1 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(13, 71, 161, 0.3);
        }

        /* Ocultar columnas dinámicamente */
        .hidden { display: none !important; }
        
        /* Mejoras en la tabla */
        #tablaFiliales thead th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        /* para los kpis/ */
    
        .kpi-container-fluid {
            margin-bottom: 25px;
        }

        .kpi-card-v2 {
            background: #ffffff;
            border-radius: 12px;
            padding: 15px;
            border: 1px solid #eef2f7;
            position: relative;
            overflow: hidden; /* Fundamental para el icono de fondo */
            transition: all 0.3s ease;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .kpi-card-v2:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.06);
        }

        /* Icono Grande de Fondo */
        .kpi-card-v2 i.bg-icon {
            position: absolute;
            right: -10px;
            bottom: -15px;
            font-size: 4.5rem; /* Icono muy grande */
            opacity: 0.08; /* Muy sutil */
            transform: rotate(-10deg);
            transition: all 0.3s ease;
        }

        .kpi-card-v2:hover i.bg-icon {
            opacity: 0.15;
            transform: rotate(0deg) scale(1.1);
        }

        /* Línea de color lateral (Vida) */
        .kpi-card-v2::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 5px;
            border-radius: 4px 0 0 4px;
        }

        /* Colores por categoría */
        .kpi-bruto::before, .text-bruto { color: #6c757d; background-color: #6c757d; }
        .kpi-remesa::before, .text-remesa { color: #0d47a1; background-color: #0d47a1; }
        .kpi-fondo::before, .text-fondo { color: #28a745; background-color: #28a745; }
        .kpi-impacto::before, .text-impacto { color: #343a40; background-color: #343a40; }
        .kpi-promedio::before, .text-promedio { color: #17a2b8; background-color: #17a2b8; }
        .kpi-check::before, .text-check { color: #ffc107; background-color: #ffc107; }

        .kpi-tag-v2 {
            font-size: 0.7rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
            z-index: 1;
        }

        .kpi-main-v2 {
            font-size: 1.45rem;
            font-weight: 800;
            color: #1e293b;
            z-index: 1;
            line-height: 1.1;
        }

        .kpi-sub-v2 {
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 4px;
            z-index: 1;
        }

        /* Contenedor del Loader */
        .chart-loader-overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.7);
            display: none; /* Oculto por defecto */
            z-index: 10;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .dark-mode .chart-loader-overlay {
            background: rgba(52, 58, 64, 0.7);
        }

        /* Animación del Spinner */
        .spinner-custom {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #0d47a1;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

@endpush

@section('content')
<x-alerts/>
<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard Distrital <small class="text-muted">Remesas {{ $anio }}</small></h1>
        </div>
      </div>
    <div>
  </div>
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
    
        <div class="container-fluid kpi-container-fluid">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-3">
                
                <div class="col">
                    <div class="kpi-card-v2 kpi-bruto">
                        <i class="fas fa-wallet bg-icon"></i>
                        <div class="kpi-tag-v2">Ingresos Brutos</div>
                        <div class="kpi-main-v2">Bs {{ number_format($kpis['total_dinero'], 0) }}</div>
                    </div>
                </div>

                <div class="col">
                    <div class="kpi-card-v2 kpi-remesa">
                        <i class="fas fa-university bg-icon"></i>
                        <div class="kpi-tag-v2">Remesas MBOS</div>
                        <div class="kpi-main-v2 text-remesa" style="background: none;">Bs {{ number_format($kpis['total_remesas'], 0) }}</div>
                    </div>
                </div>

                <div class="col">
                    <div class="kpi-card-v2 kpi-fondo">
                        <i class="fas fa-vault bg-icon"></i>
                        <div class="kpi-tag-v2">Fondo Local</div>
                        <div class="kpi-main-v2 text-fondo" style="background: none;">Bs {{ number_format($kpis['total_fondo_local'], 0) }}</div>
                    </div>
                </div>

                <div class="col">
                    <div class="kpi-card-v2 kpi-impacto">
                        <i class="fas fa-globe-americas bg-icon"></i>
                        <div class="kpi-tag-v2">Impacto Mis.</div>
                        <div class="kpi-main-v2">{{ number_format($kpisExtra['tasa_mision'], 1) }}%</div>
                    </div>
                </div>

                <div class="col">
                    <div class="kpi-card-v2 kpi-promedio">
                        <i class="fas fa-chart-line bg-icon"></i>
                        <div class="kpi-tag-v2">Promedio Mes</div>
                        <div class="kpi-main-v2">Bs {{ number_format($kpisExtra['promedio_mensual'], 0) }}</div>
                    </div>
                </div>

                <div class="col">
                    <div class="kpi-card-v2 kpi-check">
                        <i class="fas fa-calendar-check bg-icon"></i>
                        <div class="kpi-tag-v2">% Cumplimiento</div>
                        <div class="kpi-main-v2">{{ number_format($kpis['porcentaje_cumplimiento'], 0) }}%</div>
                        <div class="kpi-sub-v2 {{ $kpisExtra['filiales_inactivas'] > 0 ? 'text-danger' : 'text-success' }}">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $kpisExtra['filiales_inactivas'] }} Inactivas
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="row">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="fw-bold"><i class="fas fa-list me-2"></i>Detalle de Filiales</h5>
                            <div class="btn-group shadow-sm">
                                <button class="btn btn-custom-outline active" onclick="filterCol('remesa')"><i class="fas fa-money-bill-wave me-1"></i> Remesa</button>
                                <button class="btn btn-custom-outline" onclick="filterCol('fondo')"><i class="fas fa-landmark me-1"></i> F. Local</button>
                                <button class="btn btn-custom-outline" onclick="filterCol('ambos')"><i class="fas fa-eye me-1"></i> Ambos</button>
                            </div>
                        </div>
                        
                        <table id="tablaFiliales" class="table table-hover w-100">
                            <thead>
                                <tr>
                                    <th>Filial</th>
                                    <th class="col-remesa">Diezmo</th>
                                    <th class="col-remesa">Ofrenda</th>
                                    <th class="col-fondo">Pro-Templo</th>
                                    <th class="col-remesa">Total Remesa</th>
                                    <th class="col-fondo">Saldo Local</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($datosFiliales as $f)
                                <tr>
                                    <td><strong>{{ $f->filial }}</strong><br><small>{{ $f->distrito }}</small></td>
                                    <td class="col-remesa">{{ number_format($f->total_diezmo, 2) }}</td>
                                    <td class="col-remesa">{{ number_format($f->total_ofrenda, 2) }}</td>
                                    <td class="col-fondo">{{ number_format($f->total_pro_templo, 2) }}</td>
                                    <td class="col-remesa fw-bold text-primary">{{ number_format($f->total_remesa, 2) }}</td>
                                    <td class="col-fondo fw-bold text-success">{{ number_format($f->saldo_actual, 2) }}</td>
                                    <td>
                                        <button onclick="cargarHistorial({{ $f->id_iglesia }}, '{{ $f->filial }}')" 
                                                class="btn btn-sm btn-custom-outline" title="Ver Historial">
                                            <i class="fas fa-chart-area"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Distribución Global {{ $anio }}</h6>
                        <div id="chartGlobal"></div>
                    </div>
                </div>
                
                <div class="card shadow-sm border-0">
                    <div class="card-body position-relative">
                        <div id="loaderHistorial" class="chart-loader-overlay">
                            <div class="spinner-custom"></div>
                        </div>

                        <h6 class="fw-bold mb-1" id="tituloHistorial">Historial Mensual</h6>
                        <p class="small text-muted mb-3">Selecciona una filial en la tabla</p>
                        <div id="chartHistorialArea"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold"><i class="fas fa-trophy text-warning me-2"></i>Ranking Top 20 Filiales</h5>
                            <div class="btn-group">
                                <button class="btn btn-xs btn-custom-outline active" onclick="updateRanking('remesa')">Remesa</button>
                                <button class="btn btn-xs btn-custom-outline" onclick="updateRanking('fondo')">Saldo Local</button>
                            </div>
                        </div>
                        <div id="chartRankingBar"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold"><i class="fas fa-chart-line text-info me-2"></i>Desempeño Mensual Global</h5>
                            <div class="btn-group shadow-sm">
                                <button class="btn btn-xs btn-custom-outline active" onclick="updateSeriesGlobal('all')">Todo</button>
                                <button class="btn btn-xs btn-custom-outline" onclick="updateSeriesGlobal('remesa')">Remesa</button>
                                <button class="btn btn-xs btn-custom-outline" onclick="updateSeriesGlobal('ofrenda')">Ofrenda</button>
                                <button class="btn btn-xs btn-custom-outline" onclick="updateSeriesGlobal('pro')">Pro-Templo</button>
                            </div>
                        </div>
                        <div id="chartGlobalMensual"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>


@endsection

@push('js')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script>

<script>
        $(document).ready(function() {
            // 1. Inicialización de DataTable Profesional
            const table = $('#tablaFiliales').DataTable({
                scrollX: true,
                pageLength: 10,
                order: [[4, 'desc']],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
            });
            // 2. GRAFICO GENERA ************
            // *****************************
            var optionsGlobal = {
                series: [
                    {{ (float)$totalesGlobales['diezmo'] }}, 
                    {{ (float)$totalesGlobales['ofrenda'] }}, 
                    {{ (float)$totalesGlobales['pro_templo'] }}
                ],
                chart: { type: 'donut', height: 300 },
                labels: ['Diezmos', 'Ofrendas', 'Pro-Templo'],
                colors: ['#0d47a1', '#ffc107', '#28a745'],
                legend: { position: 'bottom' },
                dataLabels: { enabled: true, formatter: val => val.toFixed(1) + "%" },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Bs',
                                    formatter: () => '{{ number_format(array_sum($totalesGlobales), 0) }}'
                                }
                            }
                        }
                    }
                }
            };
            var chartGlobal = new ApexCharts(document.querySelector("#chartGlobal"), optionsGlobal);
            chartGlobal.render();
        
            // 2. Gráfico de Área (Historial Dinámico) - Inicialización vacío
            var areaOptions = {
                series: [],
                chart: { type: 'area', height: 280, zoom: { enabled: false }, toolbar: { show: false } },
                colors: ['#0d47a1', '#28a745'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                xaxis: { categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'] },
                noData: { text: 'Haz clic en el botón de la tabla para ver datos' }
            };
            var chartArea = new ApexCharts(document.querySelector("#chartHistorialArea"), areaOptions);
            chartArea.render();

            // 3. Función AJAX para cargar historial de filial
            window.cargarHistorial = function(id, nombre) {
                $('#loaderHistorial').css('display', 'flex');
                $('#tituloHistorial').text('Historial: ' + nombre);
                
                fetch(`/api/historial-filial/${id}?anio={{ $anio }}`)
                    .then(response => response.json())
                    .then(data => {
                        // Mapear datos a los 12 meses
                        const remesas = new Array(12).fill(0);
                        const fondos = new Array(12).fill(0);
                        
                        data.forEach(item => {
                            remesas[item.mes - 1] = parseFloat(item.monto_remesa);
                            fondos[item.mes - 1] = parseFloat(item.fondo_local);
                        });

                        chartArea.updateSeries([
                            { name: 'Remesa MBOS', data: remesas },
                            { name: 'Fondo Local', data: fondos }
                        ]);
                    })
                    .catch(error => console.error('Error:', error))
                    .finally(() => {
                        // Ocultar Loader siempre al finalizar (éxito o error)
                        setTimeout(() => { 
                            $('#loaderHistorial').hide(); 
                        }, 500); // Pequeño delay para que no "parpadee" muy rápido
                    });

            };

            // 4. Lógica de Ranking Dinámico (Bar Chart)
            const rawData = @json($datosFiliales->take(20));
            var rankingOptions = {
                series: [{ name: 'Monto Bs', data: rawData.map(i => i.total_remesa) }],
                chart: { type: 'bar', height: 350, toolbar: { show: false } },
                plotOptions: { bar: { horizontal: true, borderRadius: 5, barHeight: '70%' } },
                colors: ['#0d47a1'],
                xaxis: { categories: rawData.map(i => i.filial) },
                title: { text: 'Top 20 por Remesa Acumulada', style: { color: '#888' } }
            };
            var chartRanking = new ApexCharts(document.querySelector("#chartRankingBar"), rankingOptions);
            chartRanking.render();

            window.updateRanking = function(type) {
                let field, label, color, title;
                if(type === 'remesa') { field = 'total_remesa'; label = 'Remesa'; color = '#0d47a1'; title = 'Top 20 por Remesa'; }
                else if(type === 'fondo') { field = 'saldo_actual'; label = 'Saldo Local'; color = '#28a745'; title = 'Top 20 por Saldo Local'; }
                else { field = 'total_ofrenda'; label = 'Ofrendas'; color = '#ffc107'; title = 'Top 20 por Ofrendas Acumuladas'; }

                // Ordenar datos según el nuevo campo seleccionado
                const sorted = [...rawData].sort((a, b) => b[field] - a[field]);

                chartRanking.updateOptions({
                    xaxis: { categories: sorted.map(i => i.filial) },
                    colors: [color],
                    title: { text: title }
                });
                chartRanking.updateSeries([{ name: label, data: sorted.map(i => i[field]) }]);
            };
        });

    // Función para toggle de columnas en la tabla
        function filterCol(option) {
            document.querySelectorAll('.btn-custom-outline').forEach(b => b.classList.remove('active'));
            event.currentTarget.classList.add('active');
            
            if(option === 'remesa') { $('.col-remesa').show(); $('.col-fondo').hide(); }
            else if(option === 'fondo') { $('.col-remesa').hide(); $('.col-fondo').show(); }
            else { $('.col-remesa').show(); $('.col-fondo').show(); }
    }

    // Datos pasados desde el controlador
    const dataMensual = @json($reporteMensualGlobal);
    const mesesLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

    // Inicializar arrays de 12 meses con ceros
    let remesaSerie = new Array(12).fill(0);
    let ofrendaSerie = new Array(12).fill(0);
    let proSerie = new Array(12).fill(0);

    dataMensual.forEach(item => {
        remesaSerie[item.mes - 1] = parseFloat(item.remesa);
        ofrendaSerie[item.mes - 1] = parseFloat(item.ofrenda);
        proSerie[item.mes - 1] = parseFloat(item.pro_templo);
    });

    var optionsGlobalMensual = {
        series: [
            { name: 'Remesa', data: remesaSerie },
            { name: 'Ofrenda', data: ofrendaSerie },
            { name: 'Pro-Templo', data: proSerie }
        ],
        chart: { height: 350, type: 'line', toolbar: { show: false }, zoom: { enabled: false } },
        colors: ['#0d47a1', '#ffc107', '#28a745'],
        dataLabels: { enabled: false },
        stroke: { width: [4, 3, 3], curve: 'smooth', dashArray: [0, 5, 5] },
        legend: { show: false },
        xaxis: { categories: mesesLabels },
        yaxis: { title: { text: 'Monto en Bs' } },
        tooltip: { y: { formatter: val => "Bs " + val.toLocaleString() } }
    };

    var chartGlobalMensual = new ApexCharts(document.querySelector("#chartGlobalMensual"), optionsGlobalMensual);
    chartGlobalMensual.render();

    // Función para filtrar series en la gráfica global
    window.updateSeriesGlobal = function(type) {
        document.querySelectorAll('.btn-custom-outline').forEach(b => {
            if(b.innerText.toLowerCase().includes(type) || (type === 'all' && b.innerText.includes('Todo'))) {
                // No hacemos nada, solo para lógica visual si quieres manejar estados de botones aquí
            }
        });

        if (type === 'remesa') {
            chartGlobalMensual.updateSeries([{ name: 'Remesa', data: remesaSerie }]);
        } else if (type === 'ofrenda') {
            chartGlobalMensual.updateSeries([{ name: 'Ofrenda', data: ofrendaSerie }]);
        } else if (type === 'pro') {
            chartGlobalMensual.updateSeries([{ name: 'Pro-Templo', data: proSerie }]);
        } else {
            chartGlobalMensual.updateSeries([
                { name: 'Remesa', data: remesaSerie },
                { name: 'Ofrenda', data: ofrendaSerie },
                { name: 'Pro-Templo', data: proSerie }
            ]);
        }
        
        // Actualizar botones activos (opcional: puedes refinar la selección de botones específica)
        event.currentTarget.parentElement.querySelectorAll('.btn').forEach(b => b.classList.remove('active'));
        event.currentTarget.classList.add('active');
    };
</script>
@endpush
