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
              <div class="col-sm-6"><h3 class="mb-0">Remesas Pendientes Mensuales </h3></div>
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
                    <form class="mt-3 mb-3" action="#" method="POST" id="filtroForm">
                    @csrf  
                      <div class="row">
                        <!--SELECT PERSOMNALIZADO-->
                        <div class="col-3">
                          <label class="form-label fw-semibold">Periodo inicial:</label>
                          <select id="periodoInicio" name="periodoInicio" class="form-select">
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
                          <select id="periodoFinal" name="periodoFinal" class="form-select">
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
                                            <th>Mes</th>
                                            <th>anio</th>
                                            <th>cantidad iglesias</th>
                                            <th>cantidad grupos</th>
                                            <th>cantidad filiales</th>
                                            <th>total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datos as $dato)
                                        <tr>
                                            <td>
                                                 {{ $meses[$dato->mes] ?? 'Desconocido' }}
                                            <td>
                                                {{$dato->anio}}
                                            </td>
                                            <td>
                                                {{$dato->nro_iglesias}}
                                            </td>
                                            <td>
                                                {{$dato->nro_grupos}}
                                            </td>
                                            <td>
                                                {{$dato->nro_filiales}}
                                            </td>
                                            <td>
                                                {{$dato->total}}
                                            </td>
                                        </tr>

                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Mes</th>
                                            <th>anio</th>
                                            <th>cantidad iglesias</th>
                                            <th>cantidad grupos</th>
                                            <th>cantidad filiales</th>
                                            <th>total</th>
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