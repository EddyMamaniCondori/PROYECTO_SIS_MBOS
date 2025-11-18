@extends('template')


@section('title', 'Bautisos')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     <!-- *********************apexcharts GRAFICOS-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <style>
    .action-toolbar {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .action-btn {
        border-radius: 30px !important;
        padding: 8px 18px !important;
        font-size: 0.9rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
        border: none;
    }

    .action-btn i {
        font-size: 1.1rem;
    }

    /* Estilos minimalistas con colores suaves */
    .btn-students { background: #007bff15; color: #0d6efd; }
    .btn-students:hover { background: #0d6efd; color: white; }

    .btn-instructors { background: #17a2b815; color: #17a2b8; }
    .btn-instructors:hover { background: #17a2b8; color: white; }

    .btn-detail { background: #19875415; color: #198754; }
    .btn-detail:hover { background: #198754; color: white; }

    .btn-chart { background: #ffc10725; color: #b68b00; }
    .btn-chart:hover { background: #ffc107; color: black; }
</style>


@endpush

@section('content')

<x-alerts />
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Seguimiento Distrital de Instructores y Estudiantes Distritales <br>Gestion: {{$anioActual}}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Seguiento Distrital</li>
                    </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!-- BLOQUE COMPLETO UNIFICADO -->
            <div class="row ">

                <!-- ESTUDIANTES -->
                <div class="col-md-6 mb-3">
                    <div class="card shadow-lg border-0" style="border-radius: 18px;">
                        
                        <!-- HEADER TARJETA -->
                        <div class="p-2 px-5 d-flex align-items-center" 
                            style="background: linear-gradient(135deg, #4c8bf5, #87b5ff); border-radius: 18px 18px 0 0;">
                            <div class="me-3">
                                <i class="bi bi-mortarboard-fill text-white" style="font-size: 30px;"></i>
                            </div>
                            <div>
                                <h5 class="text-white mb-0">Avance Estudiantes MBOS</h5>
                                <h3 class="text-white fw-bold mb-0">{{ $porcentajeEstudiantes }}%</h3>
                            </div>
                        </div>
                        <!-- CUERPO Y GRÁFICA -->
                        <div class="card-body text-center">
                            
                                <strong>Desafío:</strong> {{ $dataEstudiantes['desafio'] }} |
                                <strong>Alcanzado:</strong> {{ $dataEstudiantes['alcanzado'] }}
                            
                            <div id="chart-estudiantes" ></div>
                        </div>
                    </div>
                </div>

                <!-- INSTRUCTORES -->
                <div class="col-md-6 mb-3">
                    <div class="card shadow-lg border-0" style="border-radius: 18px;">

                        <!-- HEADER TARJETA -->
                        <div class="p-2 px-5 d-flex align-items-center"
                            style="background: linear-gradient(135deg, #28a745, #6ddf8c); border-radius: 18px 18px 0 0;">
                            <div class="me-3">
                                <i class="bi bi-people-fill text-white" style="font-size: 30px;"></i>
                            </div>
                            <div>
                                <h5 class="text-white mb-0">Avance Instructores MBOS</h5>
                                <h3 class="text-white fw-bold mb-0">{{ $porcentajeInstructores }}%</h3>
                            </div>
                        </div>

                        <!-- CUERPO Y GRÁFICA -->
                        <div class="card-body text-center">
                                <strong>Desafío:</strong> {{ $dataInstructores['desafio'] }} |
                                <strong>Alcanzado:</strong> {{ $dataInstructores['alcanzado'] }}
                            <div id="chart-instructores"></div>
                        </div>

                    </div>
                </div>

            </div>

            <!--GRAFICOS X DISTRITO-->
            <div class="row mt-5">
                <div class="col-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Tabla de Desafios Distritales
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">

                                            <thead>
                                                <!-- PRIMERA FILA: Encabezados de Grupo (Colspans) -->
                                                <tr>
                                                    <th rowspan="2">Distrito</th> <!-- COLUMNA 1 -->
                                                    <th colspan="2" class="text-center">Estudiantes</th> <!-- COLUMNA 2 (Ocupa 2 espacios) -->
                                                    <th colspan="2" class="text-center">Instructores</th> <!-- COLUMNA 3 (Ocupa 2 espacios) -->
                                                    <th rowspan="2">Acciones</th> <!-- COLUMNA 4 -->
                                                </tr>

                                                <!-- SEGUNDA FILA: Encabezados de Detalle -->
                                                <tr>
                                                    {{-- Detalle Estudiantes --}}
                                                    <th class="text-center">B</th>
                                                    <th class="text-center">A</th>

                                                    {{-- Detalle Instructores --}}
                                                    <th class="text-center">B</th>
                                                    <th class="text-center">A</th>
                                                </tr>
                                            </thead>
                                    <tbody>
                                        @foreach ($resultados as $desafio)
                                        <tr>
                                            <td>
                                                {{ $desafio->id_desafio }} /{{ $desafio->id_distrito }}   {{ $desafio->nombre}}  
                                            </td>
                                            <td>
                                                {{ $desafio->total_desafios_estudiantes }}
                                            </td>
                                            <td>
                                                {{ $desafio->total_estudiantes_alcanzados}}
                                            </td> 
                                            <td>
                                                {{ $desafio->total_desafio_instructores }}
                                            </td>
                                            <td>
                                                {{ $desafio->total_instructores_alcanzados}}
                                            </td>       
                                            <td> 
                                                <!-- Mejorado: El contenedor de grupo de botones (btn-group) es correcto. -->
                                                <div class="action-toolbar">
                                                    @can('ver instructores de distritos-desafios anual Est Inst')
                                                    <a href="{{ route('instructores.x.distrital', ['id' => $desafio->id_distrito, 'anio' => $anioActual]) }}"
                                                    class="action-btn btn-instructors">
                                                        <i class="bi bi-person-badge"></i> Instructores
                                                    </a>
                                                    @endcan

                                                    @can('ver estudiantes de distritos-desafios anual Est Inst')
                                                    <a href="{{ route('estudiantes.x.distrital', ['id' => $desafio->id_distrito, 'anio' => $anioActual]) }}"
                                                    class="action-btn btn-students">
                                                        <i class="bi bi-mortarboard-fill"></i> Estudiantes
                                                    </a>
                                                    @endcan

                                                    @can('ver detalle de iglesias de distritos-desafios anual Est Inst')
                                                    <a href="{{ route('mbos.x.distrital', ['id' => $desafio->id_distrito, 'anio' => $anioActual]) }}"
                                                    class="action-btn btn-detail">
                                                        <i class="bi bi-list-check"></i> Detalle
                                                    </a>
                                                    @endcan

                                                    <button type="button"
                                                            class="action-btn btn-chart ver-grafica"
                                                            data-id="{{ $desafio->id_distrito }}">
                                                        <i class="bi bi-bar-chart-line"></i> Gráfica
                                                    </button>

                                                </div>

                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    
                    <div class="card ">
                        <div class="card-body">
                            <h5 id="tituloGrafica" class="card-title text-center"></h5>
                            
                            <!-- PRIMER GRÁFICO: Valores Absolutos (Ya existía) -->
                            <h6 class="text-center text-muted mt-2">Valores Absolutos</h6>
                            <div id="revenue-chart" style="min-height: 320px;"></div>

                            <hr class="my-4">

                            <!-- SEGUNDO GRÁFICO: Porcentajes (NUEVO) -->
                            <h6 class="text-center text-muted">Porcentajes de Cumplimiento</h6>
                            <div id="percentage-chart" style="min-height: 320px;"></div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!--begin::TABLA-->
            
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


<script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous" ></script>
      <!-- ChartJS -->

<script>
    // Los datos se obtienen correctamente de Laravel
    const datosGraficos = @json($graficos);

    // Variables globales para los gráficos
    window.graficoAbsoluto = null;
    window.graficoPorcentaje = null;

    document.querySelectorAll('.ver-grafica').forEach(btn => {
        btn.addEventListener('click', function() {
            const IdDistrito = this.dataset.id;
            const data = datosGraficos.find(d => d.id_distrito == IdDistrito);

            if (!data) {
                // Reemplazamos alert() por una función de notificación si tienes una
                console.error('No hay datos para esta iglesia.');
                return; 
            }

            // --- PREPARACIÓN DE DATOS ---
            const categorias = ['Estudiantes', 'Instructores'];
            
            // Función para calcular porcentaje, evitando división por cero
            const calcularPorcentaje = (alcanzado, desafio) => {
                if (desafio === 0) return alcanzado > 0 ? 100 : 0;
                return Math.min(Math.round((alcanzado / desafio) * 100), 100); 
            };

            const porcEstudiantes = calcularPorcentaje(data.estudiantes.alcanzado, data.estudiantes.desafio);
            const porcInstructores = calcularPorcentaje(data.instructores.alcanzado, data.instructores.desafio);
            
            // --- SERIES DE DATOS PORCENTUALES (3 BARRAS) ---
            const seriePorcDesafio = [100, 100]; // Siempre 100%
            const seriePorcAlcanzado = [porcEstudiantes, porcInstructores];
            const seriePorcDiferencia = [100 - porcEstudiantes, 100 - porcInstructores];


            // Actualizar título de la tarjeta
            document.getElementById('tituloGrafica').innerText = 'Datos de Desafío: ' + data.nombre;

            // --- 1. CONFIGURACIÓN DEL GRÁFICO ABSOLUTO (Se mantiene) ---
            const optionsAbsoluto = {
                series: [
                    { name: 'Desafío', data: [data.estudiantes.desafio, data.instructores.desafio] },
                    { name: 'Alcanzado', data: [data.estudiantes.alcanzado, data.instructores.alcanzado] },
                    { name: 'Diferencia', data: [data.estudiantes.diferencia, data.instructores.diferencia] },
                ],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: true }
                },
                plotOptions: {
                    bar: { horizontal: false, columnWidth: '50%', endingShape: 'rounded' }
                },
                colors: ['#0d6efd', '#20c997', '#dc3545'], // azul, verde, rojo
                dataLabels: { enabled: true },
                xaxis: { categories: categorias },
                legend: { position: 'top' },
                title: { text: 'Comparativa de Desafíos y Alcanzados (Unidades)', align: 'center' }
            };

            // --- 2. CONFIGURACIÓN DEL GRÁFICO PORCENTUAL (3 BARRAS Agrupadas) ---
            const optionsPorcentaje = {
                series: [
                    { name: 'Desafío (Meta)', data: seriePorcDesafio },
                    { name: 'Alcanzado (%)', data: seriePorcAlcanzado },
                    { name: 'Faltante (%)', data: seriePorcDiferencia },
                ],
                chart: {
                    type: 'bar',
                    height: 350,
                    // Ya no usamos stacked: true
                    toolbar: { show: true }
                },
                plotOptions: {
                    bar: { horizontal: false, columnWidth: '50%', endingShape: 'rounded' }
                },
                colors: ['#0d6efd', '#20c997', '#dc3545'], // azul (Desafío), verde (Alcanzado), rojo (Faltante)
                dataLabels: {
                    enabled: true,
                    // Formatea las etiquetas para mostrar el símbolo %
                    formatter: function(val) {
                        return val.toFixed(0) + '%'; 
                    }
                },
                xaxis: { categories: categorias },
                yaxis: { 
                    max: 100, // Forzamos el eje Y a 100%
                    labels: {
                         formatter: function(val) {
                            return val + '%';
                        }
                    }
                },
                legend: { position: 'top' },
                tooltip: { y: { formatter: (val) => val.toFixed(0) + '%' } },
                title: { text: 'Cumplimiento de Metas (%)', align: 'center' }
            };

            // --- 3. RENDERIZACIÓN ---

            // Destruir gráficos anteriores si existen
            if (window.graficoAbsoluto) window.graficoAbsoluto.destroy();
            if (window.graficoPorcentaje) window.graficoPorcentaje.destroy();

            // Renderizar Gráfico Absoluto
            window.graficoAbsoluto = new ApexCharts(
                document.querySelector("#revenue-chart"),
                optionsAbsoluto
            );
            window.graficoAbsoluto.render();

            // Renderizar Gráfico Porcentaje (NUEVO - 3 Barras Agrupadas)
            window.graficoPorcentaje = new ApexCharts(
                document.querySelector("#percentage-chart"),
                optionsPorcentaje
            );
            window.graficoPorcentaje.render();
        });
    });
</script>


<script>
// ====================
//  GRAFICA ESTUDIANTES
// ====================
var chartEstudiantes = new ApexCharts(document.querySelector("#chart-estudiantes"), {
    chart: { type: 'bar',height: 190,  },
    series: [{
        name: 'Cantidad',
        data: [
            {{ $dataEstudiantes['desafio'] }},
            {{ $dataEstudiantes['alcanzado'] }},
            {{ $dataEstudiantes['diferencia'] }}
        ]
    }],
    xaxis: {
        categories: ['Desafío', 'Alcanzado', 'Diferencia']
    },
    colors: ['#2980b9']
});

chartEstudiantes.render();


// =====================
//  GRAFICA INSTRUCTORES
// =====================
var chartInstructores = new ApexCharts(document.querySelector("#chart-instructores"), {
    chart: { type: 'bar', height: 190,  },
    series: [{
        name: 'Cantidad',
        data: [
            {{ $dataInstructores['desafio'] }},
            {{ $dataInstructores['alcanzado'] }},
            {{ $dataInstructores['diferencia'] }}
        ]
    }],
    xaxis: {
        categories: ['Desafío', 'Alcanzado', 'Diferencia']
    },
    colors: ['#27ae60']
});

chartInstructores.render();
</script>
@endpush