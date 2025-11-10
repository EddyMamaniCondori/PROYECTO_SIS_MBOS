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
              <div class="col-sm-6"><h3 class="mb-0">Asignar Desafios a la Campaña </h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Campañas</li>
                </ol>
              </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-9">
                    <p><strong>Codigo:</strong>{{$desafio->id_desafio_evento}} <br>
                    <strong>Nombre del Desafio Eventual:</strong>{{$desafio->nombre}}  <br>
                    <strong>Año:</strong>{{$desafio->anio}}  <br>
                    <strong>Fecha Inicio:</strong>{{$desafio->fecha_inicio}}  <br>
                    <strong>Fecha final:</strong>{{$desafio->fecha_final}}  </p>
                </div>
                <div class="col-3">
                    <a href="{{ route('desafio_eventos.asignar_distritos', $desafio->id_desafio_evento) }}" class="btn btn-primary">
                        <i class="bi bi-check-all"></i> Habilitar Distritos
                    </a>  <br>
                    <a href="{{ route('desafio_eventos.indexasignaciones') }}" class="btn btn-secondary mt-2 ">
                        <i class="bi bi-x-lg"></i> Volver
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
                                Tabla de Distritos
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Evento</th>
                                            <th>Distrito</th>
                                            <th>Desafio</th>
                                            <th>Alcanzado</th>
                                            <th>acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asignaciones as $asig)
                                        <tr>
                                            <td>
                                                {{$asig->nombre_evento}} - {{$asig->id_asigna_desafio}} 
                                            </td>
                                            <td>
                                                {{$asig->nombre_distrito}} 
                                            </td>
                                            <td>
                                                {{$asig->desafio}}
                                            </td>
                                            <td>
                                                {{$asig->alcanzado}}
                                            </td>
                                            <td> 

                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                   <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$asig->id_asigna_desafio}}"> <i class="bi bi-pencil-square"></i> Asignar</button>
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="confirmModal-{{$asig->id_asigna_desafio}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                      <h1 class="modal-title fs-5" id="exampleModalLabel">Actualizar desafios</h1>
                                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <form action="{{ route('desafio_eventos.update_asignacion_desafio', $asig->id_asigna_desafio) }}" method="POST">
                                                  @csrf
                                                  @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <strong>Distrito:</strong> {{$asig->nombre_distrito}}  <br>
                                                            <strong>Evento:</strong>  {{$asig->nombre_evento}}
                                                        </div>
                                                        <hr>
                                                        <!-- Campo editable -->
                                                        <div class="mb-3">
                                                            <label for="desafio" class="form-label">
                                                            <strong>Desafio:</strong>
                                                            </label>
                                                            <input 
                                                            type="number" 
                                                            class="form-control" 
                                                            name="desafio" 
                                                            id="desafio" 
                                                            value="{{ old('desafio', $asig->desafio) }}" 
                                                            min="0" required >
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
                                            <th>Evento</th>
                                            <th>Distrito</th>
                                            <th>Desafio</th>
                                            <th>Alcanzado</th>
                                            <th>acciones</th>
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