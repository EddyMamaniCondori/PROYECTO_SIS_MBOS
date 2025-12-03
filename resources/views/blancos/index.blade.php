@extends('template')


@section('title', 'Tablas')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')


<x-alerts/>

        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Blancos De Remesas {{$anioActual}}</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Blancos distritales</li>
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
                    <form class="mt-3 mb-3" action="{{ route('blancos.filtro') }}" method="POST" id="filtroForm">
                    @csrf  
                      <div class="row">
                        <!--SELECT PERSOMNALIZADO-->
                        <div class="col-3">
                          <label class="form-label fw-semibold">Periodo:</label>
                          <select id="periodoInicio" name="periodoInicio" class="form-select">
                                <option value="">--Selecciona--</option>
                            @foreach ($anios as $anio)
                                <option value="{{$anio->anio}}">
                                {{ (isset($anioSeleccionado) && $anioSeleccionado == $anio->anio) ? 'selected' : '' }}
                                {{ $anio->anio }}
                                </option>
                            @endforeach
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
                <!--<div class="card mb-4 p-2">
                  <button type="button" class="btn btn-primary w-100 mb-2"> <i class="bi bi-filetype-xlsx"></i> &nbsp;  Descargar EXCEL</button>
                  <button type="button" class="btn btn-success w-100"> <i class="bi bi-filetype-pdf"></i>  &nbsp; Descargar PDF</button>
                </div>-->
              </div>

            </div>
            <!--begin::TABLA-->
            <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabla de Blancos Distritales
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Distrito</th>
                                            <th>Pastor</th>
                                            <th>año</th>
                                            <th>Blanco Mensual</th>
                                            <th>Blanco Anual</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($blancos as $dato)
                                        <tr>
                                            <td>
                                                {{$dato->nombre_distrito}}
                                            </td>
                                            <td>
                                                {{$dato->nombre_pastor}} {{$dato->ape_paterno}} {{$dato->ape_materno}}
                                            </td>
                                            <td>
                                                {{$dato->anio}} 
                                            </td>
                                            <td>
                                              {{ number_format($dato->monto / 12, 2) }}
    
                                            </td> 
                                            <td>
                                                {{number_format($dato->monto, 2)}} 
                                            </td>   
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                
                                                    <!--<form action="#" method="GET">
                                                        <button type="submit" class="btn btn-primary"> <i class="bi bi-graph-up"></i> Ver Avance</button>
                                                    </form>-->

                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$dato->id_blanco}}"> <i class="bi bi-pencil-square"></i> Asignar</button>
                                             </td>

                                        </tr>
                                        <div class="modal fade" id="confirmModal-{{$dato->id_blanco}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                      <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de Confirmacion</h1>
                                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <form action="{{ route('blancos.update', $dato->id_blanco) }}" method="POST">
                                                  @csrf
                                                  @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <strong>Distrito:</strong> {{ $dato->nombre_distrito ?? 'Sin distrito' }} <br>
                                                            <strong>Pastor:</strong> 
                                                            {{ $dato->nombre_pastor ?? 'Sin' }} 
                                                            {{ $dato->ape_paterno ?? '' }} 
                                                            {{ $dato->ape_materno ?? '' }}
                                                        </div>
                                                        <hr>

                                                        <!-- Campo editable -->
                                                        <div class="mb-3">
                                                            <label for="monto" class="form-label">
                                                            <strong>Blanco Mensual (Bs.) :</strong>
                                                            </label>
                                                            <input 
                                                            type="number" 
                                                            class="form-control" 
                                                            name="monto" 
                                                            id="monto" 
                                                            value="{{ old('monto', $dato->monto) }}" 
                                                            min="0" 
                                                            step="0.01" 
                                                            required
                                                            >
                                                            @error('monto')
                                                              <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-success">Confirmar</button>
                                                    </div>
                                                  </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Distrito</th>
                                            <th>Pastor</th>
                                            <th>año</th>
                                            <th>Blanco Mensual</th>
                                            <th>Blanco Anual</th>
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