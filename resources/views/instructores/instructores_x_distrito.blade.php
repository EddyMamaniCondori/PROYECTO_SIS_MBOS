@extends('template')


@section('title', 'Bautisos')

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
              <div class="col-sm-6">
                    <h4 class="mb-0">Instructores Biblicos - {{$anio}}<br>
                    <span class="text-primary"> <strong> Distrito:</strong>&nbsp;{{$distrito->nombre}}</span> <br>
                    <span class="text-primary"><strong> Pastor:</strong> &nbsp;{{$persona->nombre}}&nbsp;{{$persona->ape_paterno}}&nbsp;{{$persona->ape_materno}}</span></h4>
                </div>
                
                <div class="col-sm-6 mb-3">
                    <!-- Contenedor Flexbox que apila verticalmente (flex-column) y alinea todo a la DERECHA (align-items-end) -->
                    <div class="d-flex flex-column align-items-end">
                        <ol class="breadcrumb mb-2"> 
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('instructores.mbos.distrital.ver') }}">Seguiento Distrital</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Instructores</li>
                        </ol>
                        <a href="{{ route('instructores.mbos.distrital.ver') }}" class="btn btn-primary ">
                            <i class="bi bi-box-arrow-in-left"></i> Volver
                        </a>
                    </div>
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
                                Tabla de Instructores biblicos
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                         <tr>
                                            <th>Iglesia</th>
                                            <th>Nombre</th>
                                            <th>Fecha de registro</th>
                                            <th>Sexo</th>
                                            <th>Edad</th>
                                            <th>Celular</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($instructores as $instructor)
                                        <tr>
                                            <td>
                                                {{ $instructor->nombre_iglesia ?? 'Sin Iglesia' }}
                                            </td>
                                            <td>
                                                {{ $instructor->nombre }}
                                                {{ $instructor->ape_paterno }}
                                                {{ $instructor->ape_materno ?? '' }}
                                            </td>
                                            <td >
                                                {{ $instructor->fecha_registro}}
                                            </td>
                                            <td class="text-center">
                                                {{ $instructor->sexo === 'M' ? 'Masculino' : 'Femenino' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $instructor->edad }} años
                                            </td>
                                            <td class="text-center">
                                                {{ $instructor->celular ?? '—' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Iglesia</th>
                                            <th>Nombre</th>
                                            <th>Fecha de registro</th>
                                            <th>Sexo</th>
                                            <th>Edad</th>
                                            <th>Celular</th>
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