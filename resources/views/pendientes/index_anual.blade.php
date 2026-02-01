@extends('template')


@section('title', 'Tablas')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
@php
    $meses = [
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
        12 => 'Diciembre',
    ];
@endphp

@if (session('success'))
    <script>
        const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
        });
        Toast.fire({
        icon: "success",
        title: "{{ session('success') }}"
        });
    </script>
@endif

        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Remesas Pendientes Mensuales de la gestion 2025 </h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Pendientes anuales</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--div BUSCADOR-->
            <div class="row">
              <div class="col-8">
                <div class="row">
                  <div class="card mb-4">
                    <form class="mt-3 mb-3" action="{{ route('remesas.pendiente.anual.filtro') }}" method="POST" id="filtroForm">
                    @csrf  
                      <div class="row">
                        <!--SELECT PERSOMNALIZADO-->
                        <div class="col-3">
                          <label class="form-label fw-semibold">Periodo inicial:</label>
                          <select id="periodoInicio" name="periodoInicio" class="form-select" required>
                            <option value="">--Selecciona--</option>
                            <option value="1-2025">01 - 2025</option>
                            <option value="2-2025">02 - 2025</option>
                            <option value="3-2025">03 - 2025</option>
                            <option value="4-2025">04 - 2025</option>
                            <option value="5-2025">05 - 2025</option>
                            <option value="6-2025">06 - 2025</option>
                            <option value="7-2025">07 - 2025</option>
                            <option value="8-2025">08 - 2025</option>
                            <option value="9-2025">09 - 2025</option>
                            <option value="10-2025">10 - 2025</option>
                            <option value="11-2025">11 - 2025</option>
                            <option value="12-2025">12 - 2025</option>
                          </select>
                        </div>
                        <!--SELECT PERSOMNALIZADO-->
                        <div class="col-3">
                          <label class="form-label fw-semibold">Periodo final:</label>
                          <select id="periodoFinal" name="periodoFinal" class="form-select" required>
                            <option value="">--Selecciona--</option>
                            <option value="1-2025">01 - 2025</option>
                            <option value="2-2025">02 - 2025</option>
                            <option value="3-2025">03 - 2025</option>
                            <option value="4-2025">04 - 2025</option>
                            <option value="5-2025">05 - 2025</option>
                            <option value="6-2025">06 - 2025</option>
                            <option value="7-2025">07 - 2025</option>
                            <option value="8-2025">08 - 2025</option>
                            <option value="9-2025">09 - 2025</option>
                            <option value="10-2025">10 - 2025</option>
                            <option value="11-2025">11 - 2025</option>
                            <option value="12-2025">12 - 2025</option>
                          </select>
                        </div>

                        <!--SELECT PERSOMNALIZADO-->
                        <div class="col-4">
                          <label class="form-label fw-semibold">Tipo:</label>
                          <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                              --Escoge el tipo--
                            </button>
                            <ul class="dropdown-menu p-2" style="width:100%;">
                              <li>
                                <label class="form-check d-flex align-items-center">
                                  <input class="form-check-input me-2" type="checkbox" name="tipo[]" value="iglesia">
                                  <i class="bi bi-house-check-fill text-success me-2"></i> Iglesias
                                </label>
                              </li>
                              <li>
                                <label class="form-check d-flex align-items-center">
                                  <input class="form-check-input me-2" type="checkbox" name="tipo[]" value="grupo">
                                  <i class="bi bi-house-fill text-primary me-2"></i> Grupos
                                </label>
                              </li>
                              <li>
                                <label class="form-check d-flex align-items-center">
                                  <input class="form-check-input me-2" type="checkbox" name="tipo[]" value="filial">
                                  <i class="bi bi-house-gear-fill text-warning me-2"></i> Filiales
                                </label>
                              </li>
                            </ul>
                          </div>
                        </div>
                        <!--SELECT PERSOMNALIZADO-->
                        <div class="col-2">
                          <button type="submit" class="btn btn-success mt-3"><i class="bi bi-funnel-fill"></i> &nbsp; Filtrar</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="card mb-4 p-2">
                  <button type="button" class="btn btn-primary w-100 mb-2"> <i class="bi bi-filetype-xlsx"></i> &nbsp;  Descargar EXCEL</button>
                  <button type="button" class="btn btn-success w-100"> <i class="bi bi-filetype-pdf"></i>  &nbsp; Descargar PDF</button>
                </div>
              </div>

            </div>

            <!--div de los cards-->
            <div class="row">
              
              <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 1-->
                <div class="small-box text-bg-primary">
                  <div class="inner">
                    <h3><span>{{ $total }}</span></h3>
                    <p>Total de Pendientes</p>
                  </div>
                  <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="currentColor" class="bi bi-houses-fill small-box-icon" viewBox="0 0 24 24">
                    <path clip-rule="evenodd"
                      fill-rule="evenodd" d="M7.207 1a1 1 0 0 0-1.414 0L.146 6.646a.5.5 0 0 0 .708.708L1 7.207V12.5A1.5 1.5 0 0 0 2.5 14h.55a2.5 2.5 0 0 1-.05-.5V9.415a1.5 1.5 0 0 1-.56-2.475l5.353-5.354z"/>
                    <path  clip-rule="evenodd"
                      fill-rule="evenodd" d="M8.793 2a1 1 0 0 1 1.414 0L12 3.793V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3.293l1.854 1.853a.5.5 0 0 1-.708.708L15 8.207V13.5a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 4 13.5V8.207l-.146.147a.5.5 0 1 1-.708-.708z"/>
                  </svg>
                 
                  <a
                    href="#"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Mas Informacion <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 1-->
              </div>
              <!--end::Col-->
              <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 2-->
                <div class="small-box text-bg-success">
                  <div class="inner">
                    <h3>{{ $c_iglesias }}<sup class="fs-5"></sup></h3>
                    <p>Pendientes de Iglesias</p>
                  </div>

                  <svg xmlns="http://www.w3.org/2000/svg"  aria-hidden="true"  fill="currentColor" class="bi bi-house-heart-fill small-box-icon" viewBox="0 0 24 24">
                  <path clip-rule="evenodd"
                      fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0L11 3.793V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3.293l2.354 2.353a.5.5 0 0 1-.708.707L8 2.207 1.354 8.853a.5.5 0 1 1-.708-.707z"/>
                  <path clip-rule="evenodd"
                      fill-rule="evenodd" d="m14 9.293-6-6-6 6V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5zm-6-.811c1.664-1.673 5.825 1.254 0 5.018-5.825-3.764-1.664-6.691 0-5.018"/>
                </svg>

                  <a
                    href="#"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    Mas informacion <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 2-->
              </div>
              <!--end::Col-->
              <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 3-->
                <div class="small-box text-bg-warning">
                  <div class="inner">
                    <h3>{{ $c_grupo }}</h3>
                    <p>Pendientes de Grupos</p>
                  </div>
                  <svg xmlns="http://www.w3.org/2000/svg"  aria-hidden="true" fill="currentColor" class="bi bi-house-gear-fill small-box-icon" viewBox="0 0 24 24">
                    <path clip-rule="evenodd"
                      fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0L11 3.793V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3.293l2.354 2.353a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708z"/>
                    <path clip-rule="evenodd"
                      fill-rule="evenodd"  d="M11.07 9.047a1.5 1.5 0 0 0-1.742.26l-.02.021a1.5 1.5 0 0 0-.261 1.742 1.5 1.5 0 0 0 0 2.86 1.5 1.5 0 0 0-.12 1.07H3.5A1.5 1.5 0 0 1 2 13.5V9.293l6-6 4.724 4.724a1.5 1.5 0 0 0-1.654 1.03"/>
                    <path clip-rule="evenodd"
                      fill-rule="evenodd" d="m13.158 9.608-.043-.148c-.181-.613-1.049-.613-1.23 0l-.043.148a.64.64 0 0 1-.921.382l-.136-.074c-.561-.306-1.175.308-.87.869l.075.136a.64.64 0 0 1-.382.92l-.148.045c-.613.18-.613 1.048 0 1.229l.148.043a.64.64 0 0 1 .382.921l-.074.136c-.306.561.308 1.175.869.87l.136-.075a.64.64 0 0 1 .92.382l.045.149c.18.612 1.048.612 1.229 0l.043-.15a.64.64 0 0 1 .921-.38l.136.074c.561.305 1.175-.309.87-.87l-.075-.136a.64.64 0 0 1 .382-.92l.149-.044c.612-.181.612-1.049 0-1.23l-.15-.043a.64.64 0 0 1-.38-.921l.074-.136c.305-.561-.309-1.175-.87-.87l-.136.075a.64.64 0 0 1-.92-.382ZM12.5 14a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"/>
                    </svg>
                    
                    
                  <a
                    href="#"
                    class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    Mas Informacion <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 3-->
              </div>
              <!--end::Col-->
              <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 4-->
                <div class="small-box text-bg-danger">
                  <div class="inner">
                    <h3>{{ $c_filiales }}</h3>
                    <p>Pendientes Filiales</p>
                  </div>
                  <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="currentColor" class="bi bi-house small-box-icon" viewBox="0 0 24 24" >
                    <path  clip-rule="evenodd"
                      fill-rule="evenodd" d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
                </svg>
                  
                  
                  <a
                    href="#"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    Mas Informacion <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 4-->
              </div>
              <!--end::Col-->
            </div>


            <!--begin::TABLA-->
            <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabla de Pendientes
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Distrito</th>
                                            <th>Pastor</th>
                                            <th>codigo</th>
                                            <th>Iglesia</th>
                                            <th>Tipo</th>
                                            <th>Lugar</th>
                                            <th>Mes - Año</th>
                                            <th>estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datos as $dato)
                                        <tr>
                                            <td>
                                                {{$dato->nombre_distrito}}
                                            </td>
                                            <td>
                                                {{$dato->nombre_p}}
                                            </td>
                                            <td>
                                                {{$dato->codigo}}
                                            </td>
                                            <td>
                                                {{$dato->nombre}}
                                            </td>
                                            <td>
                                                {{$dato->tipo}}
                                            </td>
                                            <td>
                                                {{$dato->lugar}}
                                            </td>
                                            <td>
                                                 {{ $meses[$dato->mes] ?? 'Desconocido' }} -{{$dato->anio}}
                                            <td>
                                                {{$dato->estado}}
                                            </td>
                                        </tr>

                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Distrito</th>
                                            <th>Pastor</th>
                                            <th>codigo</th>
                                            <th>Iglesia</th>
                                            <th>Tipo</th>
                                            <th>Lugar</th>
                                            <th>Mes - Año</th>
                                            <th>estado</th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>


            <div class="card-body">
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


<script>
  // Evita que el menú se cierre al hacer clic dentro del dropdown
  document.querySelectorAll('.dropdown-menu').forEach(menu => {
    menu.addEventListener('click', e => {
      e.stopPropagation();
    });
  });
</script>
  <!-- fecha inicio y fecha final -->
<script>
  const inicio = document.getElementById('periodoInicio');
  const fin = document.getElementById('periodoFinal');

  const meses = [
    '1-2025', '2-2025', '3-2025', '4-2025', '5-2025', '6-2025',
    '7-2025', '8-2025', '9-2025', '10-2025', '11-2025', '12-2025'
  ];

  function actualizarOpciones(select, desde, haciaAdelante = true, valorPrevio = "") {
    select.innerHTML = '<option value="">--Selecciona--</option>';
    const index = meses.indexOf(desde);
    if (index === -1) return;

    const rango = haciaAdelante ? meses.slice(index) : meses.slice(0, index + 1);
    rango.forEach(m => {
      const opt = document.createElement('option');
      opt.value = m;
      opt.textContent = m;
      select.appendChild(opt);
    });

    // Restaurar valor previo si sigue dentro del rango
    if (valorPrevio && rango.includes(valorPrevio)) {
      select.value = valorPrevio;
    }
  }

  inicio.addEventListener('change', () => {
    const valor = inicio.value;
    const valorPrevio = fin.value;
    if (valor) {
      actualizarOpciones(fin, valor, true, valorPrevio);
    } else {
      fin.innerHTML = meses.map(m => `<option value="${m}">${m}</option>`).join('');
    }
  });

  fin.addEventListener('change', () => {
    const valor = fin.value;
    const valorPrevio = inicio.value;
    if (valor) {
      actualizarOpciones(inicio, valor, false, valorPrevio);
    } else {
      inicio.innerHTML = meses.map(m => `<option value="${m}">${m}</option>`).join('');
    }
  });
</script>

@endpush