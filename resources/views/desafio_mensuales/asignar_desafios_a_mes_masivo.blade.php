@extends('template')

@section('title', 'Asignación Masiva de Visitas')

@push('css')


<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
<style>
    /* Mejora inputs dentro de tabla */
    .table-input {
        width: 90px;
        padding: 4px 6px;
        border-radius: 6px;
        border: 1px solid #ccc;
        text-align: center;
    }

    .save-btn {
        border-radius: 30px;
        padding: 10px 25px;
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

@php
$meses_array = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
@endphp

<x-alerts/>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Asignacion de Blanco de Visitas <strong>{{$meses_array[$mes]}} del {{$anio}}</strong></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{route('desafios_mensuales.index')}}">Desafios Visitas Meses </a></li>
                        <li class="breadcrumb-item active" aria-current="page">Asignacion Blancos Masivos</li>
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


<div class="app-content">
    <div class="container-fluid">
        <form action="{{ route('mensuales_desafios.update.masivo') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Asignación Masiva de Desafíos
                </div>

                <div class="card-body">
                    <table id="example" class="display">
                        <thead>
                            <tr>
                                <th>Cod</th>
                                <th>Distrito</th>
                                <th>Pastor</th>
                                <th>Mes - Año</th>
                                <th>Desafío Visitas</th>
                                <th>Fecha Límite</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($resultados as $item)
                            <tr>
                                <td>{{ $item->id_mensual }}</td>

                                <td>{{ $item->nombre }}</td>

                                <td>
                                    {{ $item->nombre_p }} 
                                    {{ $item->ape_paterno }} 
                                    {{ $item->ape_materno }}
                                </td>

                                <td>{{ $meses_array[$item->mes] }} - {{ $item->anio }}</td>

                                <td>
                                    <input 
                                        type="number"
                                        min="0"
                                        name="registros[{{ $item->id_mensual }}][desafio_visitas]"
                                        value="{{ $item->desafio_visitas }}"
                                        class="table-input"
                                    >
                                </td>

                                <td>{{ $item->fecha_limite }}</td>
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <th>Cod</th>
                                <th>Distrito</th>
                                <th>Pastor</th>
                                <th>Mes - Año</th>
                                <th>Desafío</th>
                                <th>Fecha Límite</th>
                            </tr>
                        </tfoot>

                    </table>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success save-btn">
                            <i class="bi bi-check-circle-fill"></i> Guardar Cambios
                        </button>
                    </div>

                </div>
            </div>
        </form>

    </div>
</div>

@endsection


@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>

<script>
$(document).ready(function() {
    $('#example').DataTable({
        scrollX: true,
        pageLength: 50,
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "No hay registros disponibles",
            zeroRecords: "No se encontraron resultados",
            paginate: {
                previous: "Anterior",
                next: "Siguiente"
            }
        }
    });
});
</script>
@endpush
