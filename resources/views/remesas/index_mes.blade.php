@extends('template')


@section('title', 'Tablas')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

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
              <div class="col-sm-6"><center><h3 class="mb-0">Remesas {{$mes}} - {{$anio}} </h3></center></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Remesas</li>
                </ol>
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
                                            <th>Iglesia</th>
                                            <th>tipo</th>
                                            <th>lugar</th>
                                            <th>CIE</th>
                                            <th>DEP</th>
                                            <th>DOC</th>
                                            <th>Fecha entrega</th>
                                            <th>Fecha limite</th>
                                            <th>estado</th>
                                            <th>observaciones</th>
                                            <th>Monto</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datos as $dato)
                                        <tr @if($dato->tipo_igle === 'FILIAL') style="color: red;" @endif>
                                            <td>
                                            {{$dato->nombre_distrito}} 
                                            <td>
                                                {{$dato->nombre_pas}} {{$dato->ape_paterno}}{{$dato->ape_materno}}
                                            </td>
                                            <td>
                                                {{$dato->nombre_igle}}
                                            </td>
                                            <td>
                                                {{$dato->lugar_igle}}
                                            </td>
                                            <td>
                                                {{$dato->tipo_igle}}
                                            </td>
                                            <td>
                                                {{$dato->cierre}}
                                            </td>
                                            <td>
                                                {{$dato->deposito}}
                                            </td>
                                            <td>
                                                {{$dato->documentacion}}
                                            </td>
                                            <td>
                                                {{$dato->fecha_entrega}}
                                            </td>
                                            <td>
                                                {{$dato->fecha_limite}}
                                            </td>
                                            <td>
                                                {{$dato->estado}}
                                            </td>
                                            <td>
                                                {{$dato->observacion}}
                                            </td>
                                            <td>
                                                {{$dato->monto}}
                                            </td>
                                            <td>
                                                
                                                <div class="btn-group  justify-content-center" role="group" >
                                                    <!-- Cerrar Mes -->
                                                    <button type="button" 
                                                            class="btn btn-danger"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#confirmModal-{{ $dato->id_remesa }}"
                                                            title="Cerrar mes">
                                                        <i class="bi bi-x-circle"></i> Registrar
                                                    </button>

                                                </div>
                                            </td>

                                        </tr>

                                            <!-- Modal -->
                                            <div class="modal fade" id="confirmModal-{{ $dato->id_remesa }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Complete los datos</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <center><strong style="color: green;">Verifique los datos</strong></center><br>

                                                    <strong>Distrito:</strong> {{ $dato->nombre_distrito }} <br>
                                                    <strong>Iglesia:</strong> {{ $dato->nombre_igle }} <br>
                                                    <strong>Pastor:</strong> {{ $dato->nombre_pas }} {{ $dato->ape_paterno }} {{ $dato->ape_materno }}
                                                    <hr>

                                                    <label for="fecha_entrega" class="form-label">Fecha de entrega:</label>
                                                    <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control" required>
                                                    <br>

                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="cierre" value="false">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="switchCierre" name="cierre" value="true">
                                                        <label class="form-check-label" for="switchCierre">Cierre:</label>
                                                    </div>

                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="deposito" value="false">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="switchDeposito" name="deposito" value="true">
                                                        <label class="form-check-label" for="switchDeposito">Depósito:</label>
                                                    </div>

                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="documentacion" value="false">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="switchDocumentacion" name="documentacion" value="true">
                                                        <label class="form-check-label" for="switchDocumentacion">Documentación:</label>
                                                    </div>

                                                    <div class="mt-3">
                                                        <label for="monto" class="form-label">Monto:</label>
                                                        <input type="number" name="monto" id="monto" class="form-control" step="0.01" required>
                                                    </div>

                                                    <div class="mt-3">
                                                        <label for="descripcion" class="form-label">Descripción:</label>
                                                        <input type="text" name="descripcion" id="descripcion" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-success">Confirmar</button>
                                                </div>

                                                </div>
                                            </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Distrito</th>
                                            <th>Pastor</th>
                                            <th>Iglesia</th>
                                            <th>tipo</th>
                                            <th>lugar</th>
                                            <th>CIE</th>
                                            <th>DEP</th>
                                            <th>DOC</th>
                                            <th>Fecha entrega</th>
                                            <th>Fecha limite</th>
                                            <th>estado</th>
                                            <th>observaciones</th>
                                            <th>Monto</th>
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



@endpush