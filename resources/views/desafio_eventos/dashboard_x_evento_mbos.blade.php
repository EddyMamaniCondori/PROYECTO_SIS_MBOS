@extends('template')

@section('title', 'Dashboard Bautizos')

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


@endpush


@section('content')
<x-alerts />
<div class="app-content-header">
    <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Seguimiento Distrital de Bautizos — Evento: {{ $evento->nombre }}</h3>
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

<div class="app-content">
    <div class="container-fluid">
        <!-- TARJETA PRINCIPAL -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-lg border-0" style="border-radius: 18px;">
                    <div class="p-3 d-flex align-items-center"
                         style="background: linear-gradient(135deg, #0069d9, #64a4ff); border-radius: 18px 18px 0 0;">
                        <i class="bi bi-droplet-fill text-white me-3" style="font-size: 40px;"></i>

                        <div>
                            <h5 class="text-white mb-1">Avance General Bautizos</h5>
                            <h2 class="text-white fw-bold">{{ $porcentajeGeneral }}%</h2>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <h5>
                            <strong>Desafío:</strong> {{ $totales['desafio'] }} |
                            <strong>Alcanzado:</strong> {{ $totales['alcanzado'] }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-lg border-0" style="border-radius: 18px;">
                    <div class="card-header">
                        <i class="bi bi-graph-up-arrow"></i>  &nbsp;
                        <strong>Grafico Resultados Generales MBOS</strong>
                    </div>
                    <div class="card-body">
                        <div id="chart-global"></div>
                    </div>
                </div>
            </div>
        </div>
         



        <!-- TABLA + GRAFICA DISTRITAL -->
        <div class="row">

            <div class="col-6">
                <div class="card">
                    <div class="card-header fw-bold">Bautizos por Distrito</div>
                    <div class="card-body table-responsive">
                        <table id="example" class="display">
                            <thead>
                                <tr>
                                    <th>Distrito</th>
                                    <th>Desafío</th>
                                    <th>Alcanzado</th>
                                    <th>Diferencia</th>
                                    <th>Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($graficos as $g)
                                <tr>
                                    <td>{{ $g['nombre'] }}</td>
                                    <td>{{ $g['desafio'] }}</td>
                                    <td>{{ $g['alcanzado'] }}</td>
                                    <td>{{ $g['diferencia'] }}</td>
                                    <td>
                                        <button class="btn btn-warning ver-grafica"
                                                data-id="{{ $g['id_distrito'] }}">
                                            Gráfica
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 id="tituloGrafica" class="text-center"></h5>

                        <h6 class="text-center text-muted">Valores Absolutos</h6>
                        <div id="grafico-valores"></div>

                        <hr>
                        <h6 class="text-center text-muted">Porcentajes (%)</h6>
                        <div id="grafico-porcentajes"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- GRAFICA TODOS LOS DISTRITAL -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0"> <i class="bi bi-graph-up-arrow"></i>  &nbsp; Gráfico Global del Evento: {{ $evento->titulo }}</h5>
                    </div>
                    <div class="card-body">
                        <div id="graficoGlobal"></div>
                    </div>
                </div>
            </div>
        </div>

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
document.addEventListener('DOMContentLoaded', function () {

    // ---------- 2) DATOS QUE VIENEN DE LARAVEL ----------
    // Asegúrate que $graficos sea un array de objetos con id_distrito, nombre, desafio, alcanzado, diferencia
    const datos = @json($graficos ?? []);

    console.log('Datos graficos (JS):', datos);

    // ---------- 3) GRAFICO GLOBAL ----------
    const totales = {
        desafio: Number({{ $totales['desafio'] ?? 0 }}),
        alcanzado: Number({{ $totales['alcanzado'] ?? 0 }}),
        diferencia: Number({{ $totales['diferencia'] ?? 0 }})
    };
    const elGlobal = document.querySelector("#chart-global");
    if (elGlobal) {
        if (window.chartGlobal && typeof window.chartGlobal.destroy === 'function') {
            window.chartGlobal.destroy();
        }
        window.chartGlobal = new ApexCharts(elGlobal, {
            chart: { 
                type: "bar", 
                height: 260,
                toolbar: { show: true }
            },
            series: [{
                name: "Totales",
                data: [
                    totales.desafio, 
                    totales.alcanzado, 
                    totales.diferencia
                ]
            }],
            xaxis: {
                categories: ['Blanco', 'Alcanzado', 'Diferencia'],
                labels: { style: { fontSize: "16px" } }
            },
            plotOptions: {
                bar: {
                    distributed: true,     // ← permite color por barra
                    borderRadius: 2,
                    columnWidth: "45%"
                }
            },
            colors: [
                "#007bff", // azul → desafío
                "#28a745", // verde → alcanzado
                "#dc3545"  // rojo → diferencia
            ],
            dataLabels: {
                enabled: true,
                style: {
                    fontWeight: "bold",
                    fontSize: "14px"
                }
            },
            tooltip: {
                y: {
                    formatter: val => `${val} bautizos`
                }
            }
        });
        window.chartGlobal.render();
    } else {
        console.warn('#chart-global no encontrado en el DOM.');
    }
    // ---------- 4) GRAFICOS DINAMICOS POR DISTRITO ----------
    let graficoValores = null;
    let graficoPorcentaje = null;

    function destroyIfExists(g) {
        if (g && typeof g.destroy === 'function') {
            try { g.destroy(); } catch (err) { console.warn('Error al destruir gráfico:', err); }
        }
    }

    // Adjuntar listener a botones .ver-grafica (pueden generarse dinámicos, por eso buscamos en el DOM)
    document.querySelectorAll('.ver-grafica').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            // busco con igualdad flexible (string/number)
            const d = datos.find(x => String(x.id_distrito) === String(id) || String(x.id) === String(id));

            if (!d) {
                console.warn('No se encontraron datos para id_distrito:', id);
                document.getElementById('tituloGrafica') && (document.getElementById('tituloGrafica').innerText = 'Sin datos');
                destroyIfExists(graficoValores);
                destroyIfExists(graficoPorcentaje);
                return;
            }
            console.log('Seleccionado distrito:', d);
            // Asegurarnos que los campos sean números
            const desafio = Number(d.desafio || d.total_desafio || 0);
            const alcanzado = Number(d.alcanzado || d.total_alcanzado || 0);
            const diferencia = Number(typeof d.diferencia !== 'undefined' ? d.diferencia : (desafio - alcanzado));

            // actualizar título
            const tituloEl = document.getElementById('tituloGrafica');
            if (tituloEl) tituloEl.innerText = d.nombre || ('Distrito ' + id);

            // destruir anteriores
            destroyIfExists(graficoValores);
            destroyIfExists(graficoPorcentaje);

            // limpiar contenedores antes de re-render
            const contValores = document.getElementById('grafico-valores') || document.getElementById('revenue-chart') || document.getElementById('revenue-chart');
            const contPorc = document.getElementById('grafico-porcentajes') || document.getElementById('percentage-chart') || document.getElementById('percentage-chart');

            if (contValores) contValores.innerHTML = '';
            if (contPorc) contPorc.innerHTML = '';

            // GRAFICO DE VALORES
            graficoValores = new ApexCharts(contValores || document.querySelector("#revenue-chart"), {
                chart: { type: 'bar', height: 320 },
                series: [
                    { name: 'Desafío', data: [desafio] },
                    { name: 'Alcanzado', data: [alcanzado] },
                    { name: 'Diferencia', data: [diferencia] }
                ],
                xaxis: { categories: ['Unidades'] },
                dataLabels: { enabled: true },
                legend: { position: 'top' },
                title: { text: 'Valores por Distrito', align: 'center' },
            });
            graficoValores.render().catch(e => console.error('Error render graficoValores:', e));

            // GRAFICO DE PORCENTAJES
            const pDesafio = desafio > 0 ? 100 : 0;
            const pAlcanzado = desafio > 0 ? Math.round((alcanzado / desafio) * 100) : 0;
            const pDiferencia = desafio > 0 
                ? Math.round(((desafio - alcanzado) / desafio) * 100)
                : 0;

            graficoPorcentaje = new ApexCharts(
                contPorc || document.querySelector("#percentage-chart"),
                {
                    chart: {
                        type: 'bar',
                        height: 320,
                        toolbar: { show: true }
                    },
                    series: [{
                        name: 'Porcentaje',
                        data: [pDesafio, pAlcanzado, pDiferencia]
                    }],
                    xaxis: {
                        categories: ['Desafío (%)', 'Alcanzado (%)', 'Diferencia (%)']
                    },
                    plotOptions: {
                        bar: {
                            distributed: true, // permite colores individuales
                            columnWidth: '45%'
                        }
                    },
                    colors: [
                        '#007bff', // azul - desafío
                        '#28a745', // verde - alcanzado
                        '#dc3545'  // rojo - diferencia
                    ],
                    dataLabels: {
                        enabled: true,
                        formatter: val => val + '%'
                    },
                    title: {
                        text: 'Porcentaje de Cumplimiento',
                        align: 'center'
                    }
                }
            );

            graficoPorcentaje.render()
                .catch(e => console.error('Error render graficoPorcentaje:', e));
        });
    });

    // FIN DOMContentLoaded
});
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const data = @json($graficos);
        const nombres = data.map(d => d.nombre);
        const desafios = data.map(d => d.desafio);
        const alcanzados = data.map(d => d.alcanzado);

        var options = {
            chart: {
                type: 'area',
                height: 380,
                toolbar: { show: true }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            dataLabels: { enabled: false },
            series: [
                {
                    name: 'Desafío',
                    data: desafios
                },
                {
                    name: 'Alcanzado',
                    data: alcanzados
                }
            ],
            xaxis: {
                categories: nombres
            },
            yaxis: {
                min: 0
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 0.4,
                    opacityFrom: 0.5,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            legend: {
                position: 'top'
            }
        };

        var chart = new ApexCharts(document.querySelector("#graficoGlobal"), options);
        chart.render();
    });
</script>

@endpush
