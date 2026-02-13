@extends('template')


@section('title', 'Tablas')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
                @php
                    $meses_array = [
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
                        12 => 'Diciembre'
                    ];
                @endphp

    <x-alerts/>
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><center><h3 class="mb-0">Registro de Remesas {{ $meses_array[$mes]}} - {{$anio}} </h3></center></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Remesas</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <!--div BUSCADOR-->
            <div class="row p-4">
              <div class="col-12">
                <div class="row">
                  <div class="card mb-4">
                    <form class="mt-3 mb-3" action="{{ route('remesas.mes.filtro') }}" method="POST" id="filtroForm">
                    @csrf  
                      <div class="row">
                        <!--SELECT PERSOMNALIZADO-->
                        <div class="col-3">
                          <label class="form-label fw-semibold">Seleccione el personal:</label>
                          <select id="id_personal" name="id_personal" class="form-select" required>
                            <option value="">--Selecciona--</option>
                            @foreach($personal as $per)
                                <option value="{{$per->id_persona}}">{{$per->nombre}} &nbsp; {{$per->ape_paterno}}</option>
                            @endforeach
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
                                  <input class="form-check-input me-2" type="checkbox" name="tipo[]" value="Iglesia">
                                  <i class="bi bi-house-check-fill text-success me-2"></i> Iglesias
                                </label>
                              </li>
                              <li>
                                <label class="form-check d-flex align-items-center">
                                  <input class="form-check-input me-2" type="checkbox" name="tipo[]" value="Grupo">
                                  <i class="bi bi-house-fill text-primary me-2"></i> Grupos
                                </label>
                              </li>
                              <li>
                                <label class="form-check d-flex align-items-center">
                                  <input class="form-check-input me-2" type="checkbox" name="tipo[]" value="Filial">
                                  <i class="bi bi-house-gear-fill text-warning me-2"></i> Filiales
                                </label>
                              </li>
                            </ul>
                          </div>
                        </div>
                        <!--SELECT PERSOMNALIZADO-->
                        <input type="hidden" name="anio"  id="anio" value="{{$anio}}">
                        <input type="hidden" name="mes"  id="mes" value="{{$mes}}">
                        <div class="col-2">
                          <button type="submit" class="btn btn-success mt-3"><i class="bi bi-funnel-fill"></i> &nbsp; Filtrar</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>


        <!--contenido-->
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::TABLA-->
            <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabla de Remesas
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Distrito</th>
                                            <th>Pastor</th>
                                            <th>Cod</th>
                                            <th>Iglesia</th>
                                            <th>tipo</th>
                                            <th>Encargado</th>
                                            <th>lugar</th>
                                            <th>CIE</th>
                                            <th>DEP</th>
                                            <th>DOC</th>
                                            <th>Fecha entrega</th>
                                            <th>Fecha limite</th>
                                            <th>estado dias</th>
                                            <th>estado</th>
                                            <th>observaciones</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datos as $dato)
                                        <tr @if($dato->tipo_igle === 'Filial') style="color: red;" @endif>
                                            <td>
                                            {{$dato->nombre_distrito}} 
                                            <td>
                                                {{$dato->nombre_pas}} &nbsp; {{$dato->ape_paterno}} &nbsp;{{$dato->ape_materno}}
                                            </td>
                                            <td>
                                                {{$dato->codigo}}
                                            </td>
                                            <td>
                                                {{$dato->nombre_igle}}
                                            </td>
                                            <td>
                                                {{$dato->tipo_igle}}
                                            </td>
                                            <td>
                                                {{$dato->nombre_per}} &nbsp; {{$dato->ape_paterno_per}}
                                            </td>
                                            <td>
                                                {{$dato->lugar_igle}}
                                            </td>
                                            
                                            
                                            <td>
                                                @if ($dato->cierre)
                                                    <i class="bi bi-check-square-fill" style="color: #28a745;"></i>
                                                @else
                                                    <i class="bi bi-file-excel-fill" style="color: red;"></i>
                                                @endif
                                                            
                                            </td>
                                            <td>
                                                @if ($dato->deposito)
                                                    <i class="bi bi-check-square-fill" style="color: #28a745;"></i>
                                                @else
                                                    <i class="bi bi-file-excel-fill" style="color: red;"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($dato->documentacion)
                                                    <i class="bi bi-check-square-fill" style="color: #28a745;"></i>
                                                @else
                                                    <i class="bi bi-file-excel-fill" style="color: red;"></i>
                                                @endif
                                            </td>
                                            <td>
                                                {{$dato->fecha_entrega}}
                                            </td>
                                            <td>
                                                {{$dato->fecha_limite}}
                                            </td>
                                            <td>
                                                {{$dato->estado_dias}}
                                            </td>
                                            <td>
                                                {{$dato->estado}}
                                            </td>
                                            <td>
                                                {{$dato->observacion}}
                                            </td>

                                            <td>
                                                <div class="btn-group  justify-content-center" role="group" >
                                                   @if($dato->tipo_igle == 'Filial')
                                                       <form action="{{ route('remesas.filial') }}" method="POST">@csrf

                                                          <input type="hidden" name="id_iglesia" id="id_iglesia" value="{{ $dato->id_iglesia }}">
                                                          <input type="hidden" name="anio" id="anio" value="{{ $anio }}">
                                                          <input type="hidden" name="distrito" id="distrito" value="{{ $dato->nombre_distrito}}">
                                                          <button type="submit" class="btn btn-success">
                                                              <i class="bi bi-file-earmark-bar-graph-fill"></i> Registrar
                                                          </button>
                                                      </form>

                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-primary btn-abrir-modal"
                                                                data-id_remesa="{{ $dato->id_remesa }}"
                                                                data-distrito="{{ $dato->nombre_distrito }}"
                                                                data-iglesia="{{ $dato->nombre_igle }}"
                                                                data-pastor="{{ $dato->nombre_pas }} {{ $dato->ape_paterno }} {{ $dato->ape_materno }}"
                                                                data-tipo="{{ $dato->tipo_igle }}"
                                                                data-fecha_entrega="{{ $dato->fecha_entrega }}"
                                                                data-cierre="{{ $dato->cierre }}"
                                                                data-deposito="{{ $dato->deposito }}"
                                                                data-documentacion="{{ $dato->documentacion }}"
                                                                data-monto="{{ $dato->monto ?? '' }}"
                                                                title="Registrar"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#confirmModal"
                                                        >
                                                            <i class="bi bi-x-circle"></i> Registrar
                                                        </button>

                                                    @endif

                                                    
                                                </div>
                                            </td>

                                        </tr>

                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Distrito</th>
                                            <th>Pastor</th>
                                            <th>Cod</th>
                                            <th>Iglesia</th>
                                            <th>tipo</th>
                                            <th>Encargado</th>
                                            <th>lugar</th>
                                            <th>CIE</th>
                                            <th>DEP</th>
                                            <th>DOC</th>
                                            <th>Fecha entrega</th>
                                            <th>Fecha limite</th>
                                            <th>estado dias</th>
                                            <th>estado</th>
                                            <th>observaciones</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>


            <div class="card-body">
          </div>
          <!--end::Container-->
        </div>


<!-- Modal único reutilizable -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formRemesa" method="POST">
        @csrf
        <input type="hidden" name="id_remesa" id="modal_id_remesa">
        <input type="hidden" name="mes" id="mes" value="{{$mes}}">
        <input type="hidden" name="anio" id="value" value="{{$anio}}">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="confirmModalLabel">Complete los datos</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <center><strong style="color: green;">Verifique los datos</strong></center><br>

          <p><strong>Distrito:</strong> <span id="modal_distrito"></span><br>
          <strong>Iglesia:</strong> <span id="modal_iglesia"></span><br>
          <strong>Pastor:</strong> <span id="modal_pastor"></span></p>
          <hr>

          <label for="fecha_entrega" class="form-label">Fecha de entrega:</label>
          <input type="date" name="fecha_entrega" id="modal_fecha_entrega" class="form-control" value="{{ date('Y-m-d') }}" required>

          <br>

          <div class="form-check form-switch">
            <input type="hidden" name="cierre" value="false">
            <input class="form-check-input" type="checkbox" role="switch" id="modal_switchCierre" name="cierre" value="true">
            <label class="form-check-label" for="modal_switchCierre">Cierre:</label>
          </div>

          <div class="form-check form-switch">
            <input type="hidden" name="deposito" value="false">
            <input class="form-check-input" type="checkbox" role="switch" id="modal_switchDeposito" name="deposito" value="true">
            <label class="form-check-label" for="modal_switchDeposito">Depósito:</label>
          </div>

          <div class="form-check form-switch">
            <input type="hidden" name="documentacion" value="false">
            <input class="form-check-input" type="checkbox" role="switch" id="modal_switchDocumentacion" name="documentacion" value="true">
            <label class="form-check-label" for="modal_switchDocumentacion">Documentación:</label>
          </div>

          <div class="mt-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <input type="text" name="descripcion" id="modal_descripcion" class="form-control">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success">Confirmar</button>
        </div>
      </div>
    </form>
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

<script>
$(document).ready(function(){
    $('#confirmModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);

        // Leer atributos data-* del botón
        var id_remesa = button.data('id_remesa');
        var distrito = button.data('distrito');
        var iglesia = button.data('iglesia');
        var pastor = button.data('pastor');
        var fecha_entrega = button.data('fecha_entrega');
        var cierre = button.data('cierre');
        var deposito = button.data('deposito');
        var documentacion = button.data('documentacion');

        var modal = $(this);

        // ✅ Actualizar acción del formulario con la ruta dinámica
        var actionUrl = "{{ route('remesasiglesia.registrar', ':id_remesa') }}";
        actionUrl = actionUrl.replace(':id_remesa', id_remesa);
        modal.find('#formRemesa').attr('action', actionUrl);

        // ✅ Rellenar campos visibles
        modal.find('#modal_id_remesa').val(id_remesa);
        modal.find('#modal_distrito').text(distrito);
        modal.find('#modal_iglesia').text(iglesia);
        modal.find('#modal_pastor').text(pastor);

        // ✅ Fecha (por defecto hoy si está vacía)
        modal.find('#modal_fecha_entrega').val(fecha_entrega || new Date().toISOString().split('T')[0]);

        // ✅ Checkboxes
        modal.find('#modal_switchCierre').prop('checked', cierre == 1 || cierre === true);
        modal.find('#modal_switchDeposito').prop('checked', deposito == 1 || deposito === true);
        modal.find('#modal_switchDocumentacion').prop('checked', documentacion == 1 || documentacion === true);
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
@endpush