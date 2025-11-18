@extends('template')


@section('title', 'Crear')

@push('css')
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />


@endpush

@section('content')
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Editar Cantidad de Lideres Locales de Iglesias</h3></div>
              <div class="col-sm-6 mb-3">
                    <!-- Contenedor Flexbox que apila verticalmente (flex-column) y alinea todo a la DERECHA (align-items-end) -->
                    <div class="d-flex flex-column align-items-end">
                        <ol class="breadcrumb mb-2"> 
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('iglesias.index_pastores') }}">Mis Iglesias</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Asignacion Lideres</li>
                        </ol> 
                        <a href="{{ route('iglesias.index_pastores') }}" class="btn btn-primary ">
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
            <div class="card mt-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Líderes Locales - Asignación Masiva
                </div>
                <div class="card-body">
                    <form action="{{ route('lideres.update.masivo') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <table id="example" class="displays">
                            <thead>
                                <tr>
                                    <th>ID Iglesia</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Dir. Filial</th>
                                    <th>Dir. Congregación</th>
                                    <th>Anciano</th>
                                    <th>Diaconisas</th>
                                    <th>Diáconos</th>
                                    <th>EESS Adultos</th>
                                    <th>EESS Jóvenes</th>
                                    <th>EESS Niños</th>
                                    <th>GP</th>
                                    <th>Parejas Misioneras</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($iglesias as $ig)
                                <tr>
                                    <td>{{ $ig->id_iglesia }} / {{ $ig->codigo }}</td>
                                    <td>{{ $ig->nombre }}</td>
                                    <td>{{ $ig->tipo }}</td>

                                    <td><input type="number" name="lideres[{{ $ig->id_lideres }}][Dir_Filial]" value="{{ $ig->Dir_Filial }}" class="form-control"></td>
                                    <td><input type="number" name="lideres[{{ $ig->id_lideres }}][Dir_congregacion]" value="{{ $ig->Dir_congregacion }}" class="form-control"></td>
                                    <td><input type="number" name="lideres[{{ $ig->id_lideres }}][Anciano]" value="{{ $ig->Anciano }}" class="form-control"></td>
                                    <td><input type="number" name="lideres[{{ $ig->id_lideres }}][Diaconisas]" value="{{ $ig->Diaconisas }}" class="form-control"></td>
                                    <td><input type="number" name="lideres[{{ $ig->id_lideres }}][Diaconos]" value="{{ $ig->Diaconos }}" class="form-control"></td>
                                    <td><input type="number" name="lideres[{{ $ig->id_lideres }}][EESS_Adultos]" value="{{ $ig->EESS_Adultos }}" class="form-control"></td>
                                    <td><input type="number" name="lideres[{{ $ig->id_lideres }}][EESS_Jovenes]" value="{{ $ig->EESS_Jovenes }}" class="form-control"></td>
                                    <td><input type="number" name="lideres[{{ $ig->id_lideres }}][EESS_Niños]" value="{{ $ig->EESS_Niños }}" class="form-control"></td>
                                    <td><input type="number" name="lideres[{{ $ig->id_lideres }}][GP]" value="{{ $ig->GP }}" class="form-control"></td>
                                    <td><input type="number" name="lideres[{{ $ig->id_lideres }}][Parejas_misioneras]" value="{{ $ig->Parejas_misioneras }}" class="form-control"></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ID Iglesia</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Dir. Filial</th>
                                    <th>Dir. Congregación</th>
                                    <th>Anciano</th>
                                    <th>Diaconisas</th>
                                    <th>Diáconos</th>
                                    <th>EESS Adultos</th>
                                    <th>EESS Jóvenes</th>
                                    <th>EESS Niños</th>
                                    <th>GP</th>
                                    <th>Parejas Misioneras</th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="d-flex justify-content-end">
                            <!-- El margen automático (ms-auto) empuja el elemento al final del contenedor flex -->
                            <button class="btn btn-success">GUARDAR TODO</button>
                        </div>
                    </form>
                </div>
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
            pageLength: 50,
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