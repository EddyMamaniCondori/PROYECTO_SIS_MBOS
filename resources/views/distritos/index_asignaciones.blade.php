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
              <div class="col-sm-6"><h3 class="mb-0">Asignaciones Distritales @foreach ($anios as $a)
                                                        {{ $a->año}}
                                                    @endforeach</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Distritos</li>
                </ol>
              </div>
            </div>

            <div class="row">
                <a href="{{route('distritos.finalizar_asignaciones')}}"><button type="button" class="btn btn-primary"> <i class="fa-solid fa-plus"></i> &nbsp Finalizar Asignaciones @foreach ($anios as $a)
                                                        {{ $a->año}}
                                                    @endforeach</button> </a><br>
               <br>
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
                                Tabla de Distritos
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>pastor distrital</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distritos as $distrito)
                                            @if ($distrito->sw_estado)
                                                <tr style="background-color: green">
                                                    <td>
                                                    {{$distrito->id_distrito}} &nbsp {{$distrito->nombre}}
                                                    </td>
                                                    <td>
                                                        @if (!is_null($distrito->id_pastor))
                                                            {{ $distrito->nombre_pastor }}
                                                            &nbsp;{{ $distrito->ape_paterno_pastor }}
                                                            &nbsp;{{ $distrito->ape_materno_pastor }}
                                                        @else
                                                            <span class="badge bg-warning mt-1 fs-6">
                                                                <i class="bi bi-exclamation-triangle-fill"></i> No Tiene Pastor Distrital
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td> 
                                                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                            <a href="{{ route('distritos.mantener', $distrito->id_distrito) }}" class="btn btn-success">
                                                                Mantener
                                                            </a>

                                                            <!-- Liberar asignación -->
                                                            <a href="{{ route('distritos.liberar', $distrito->id_distrito) }}" class="btn btn-warning">
                                                                Liberar Pastor
                                                            </a>
                                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop-{{$distrito->id_distrito}}">
                                                                Cambiar
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                            @else
                                                <tr >
                                                    <td>
                                                    {{$distrito->id_distrito}} &nbsp {{$distrito->nombre}}
                                                    </td>
                                                    <td>
                                                        @if (!is_null($distrito->id_pastor))
                                                            {{ $distrito->nombre_pastor }}
                                                            &nbsp;{{ $distrito->ape_paterno_pastor }}
                                                            &nbsp;{{ $distrito->ape_materno_pastor }}
                                                        @else
                                                            <span class="badge bg-warning mt-1 fs-6">
                                                                <i class="bi bi-exclamation-triangle-fill"></i> No Tiene Pastor Distrital
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td> 
                                                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                            <a href="{{ route('distritos.mantener', $distrito->id_distrito) }}" class="btn btn-success">
                                                                Mantener
                                                            </a>

                                                            <!-- Liberar asignación -->
                                                            <a href="{{ route('distritos.liberar', $distrito->id_distrito) }}" class="btn btn-warning">
                                                                Liberar Pastor
                                                            </a>
                                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop-{{$distrito->id_distrito}}">
                                                                Cambiar
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                         
                                                <!-- Modal -->
                                                    <div class="modal fade" id="staticBackdrop-{{$distrito->id_distrito}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel">DISTRITO - {{$distrito->nombre}}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('distritos.cambiar', $distrito->id_distrito) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                Escoge un pastor para el distrito..
                                                                <label for="id_pastor" class="form-label">Pastores Disponibles:</label>
                                                                <select name="id_pastor" id="id_pastor" class="form-select" required>
                                                                    <option value="">-- Seleccione un pastor --</option>
                                                                    @foreach($pastores_libres as $pastor)
                                                                        <option value="{{ $pastor->id_persona }}">
                                                                            {{ $pastor->nombre }} {{ $pastor->ape_paterno }} {{ $pastor->ape_materno }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                                <button type="submit" class="btn btn-primary">CONFIRMAR</button>
                                                            </div>
                                                        </form>
                                                        </div>
                                                    </div>
                                                    </div>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>pastor distrital</th>
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