@extends('template')


@section('title', 'Bautisos')

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
<x-alerts />
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Asignacion de Blanco de Visitas <strong>{{$meses_array[$mes]}} del {{$anio}}</strong></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{route('desafios_mensuales.index')}}">Desafios Visitas Meses</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Asignacion Blancos</li>
                    </ol>
              </div>
            </div>
            <div class="row justify-content-end">
                <!-- El botón se mueve al final de la fila -->
                <div class="col-auto"> 
                    <a href="{{ route('desafios_mensuales.index') }}">
                        <button type="button" class="btn btn-primary "> 
                            <i class="bi bi-arrow-left"></i> &nbsp;Volver 
                        </button>
                    </a>
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
                                    Tabla de Desafios Mensuales
                                </div>
                                <div class="card-body">
                                    <table id="example" class="display">
                                        <thead>
                                            <tr>
                                                <th>Cod Desafio</th>
                                                <th>Distrito</th>
                                                <th>Pastor</th>
                                                <th>Mes - Año</th>
                                                <th>Desafio</th>
                                                <th>Alcanzado</th>
                                                <th>Fecha limite</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($resultados as $visita)
                                            <tr>
                                                <td>
                                                    {{ $visita->id_mensual}}
                                                </td>
                                                
                                                <td>
                                                    {{ $visita->nombre}}
                                                </td>
                                                <td>
                                                    {{ $visita->nombre_p}} &nbsp; {{ $visita->ape_paterno}} &nbsp; {{ $visita->ape_materno}}
                                                </td>
                                                <td>
                                                    {{$meses_array[$visita->mes]}} - {{ $visita->anio}}   
                                                </td>
                                                <td>
                                                    {{ $visita->desafio_visitas}}
                                                </td>
                                                <td>
                                                    {{ $visita->visitas_alcanzadas}}
                                                </td>
                                                <td>
                                                    {{ $visita->fecha_limite}}
                                                </td>    
                                                <td> 
                                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModalmensual-{{$visita->id_mensual}}"> <i class="bi bi-pencil-square"></i> Asignar</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="confirmModalmensual-{{$visita->id_mensual}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de Confirmacion</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('mensuales_desafios.update', $visita->id_mensual) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <strong>Mes:</strong> {{$meses_array[$visita->mes]}} <br>
                                                                <strong>Anio:</strong> {{ $visita->anio}}   
                                                            </div>
                                                            <hr>
                                                            <!-- Campo editable -->
                                                            <div class="mb-3">
                                                                <label for="desafio_visitas" class="form-label"><strong>Desafio Visitas :</strong> </label>
                                                                <input  type="number" class="form-control" name="desafio_visitas" id="desafio_visitas" value="{{ old('desafio_visitas', $visita->desafio_visitas) }}" 
                                                                min="0"  required >
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
                                                <th>Cod Desafio</th>
                                                <th>Distrito</th>
                                                <th>Pastor</th>
                                                <th>Mes - Año</th>
                                                <th>Desafio</th>
                                                <th>Alcanzado</th>
                                                <th>Fecha limite</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
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
            pageLength: 10,
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