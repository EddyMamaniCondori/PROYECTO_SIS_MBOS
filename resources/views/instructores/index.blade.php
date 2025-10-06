@extends('template')


@section('title', 'Bautisos')

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
              <div class="col-sm-6"><h3 class="mb-0">Instructores Biblicos</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Instructores</li>
                </ol>
              </div>
              <div class="row">
                <a href="{{route('instructores.create')}}"><button type="button" class="btn btn-primary"> <i class="fa-solid fa-plus"></i> &nbsp Añadir nuevo Instructor Biblico</button> </a><br>
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
                                            <th>Nombre Completo</th>
                                            <th>Sexo</th>
                                            <th>Fecha Nacimiento</th>
                                            <th>Edad</th>
                                            <th>Celular</th>
                                            <th>Iglesia</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($instructores as $instructor)
                                        <tr>
                                            <td>
                                                <strong>{{ $instructor->nombre }}</strong>
                                                {{ $instructor->ape_paterno }}
                                                {{ $instructor->ape_materno ?? '' }}
                                            </td>

                                            <td class="text-center">
                                                {{ $instructor->sexo === 'M' ? 'Masculino' : 'Femenino' }}
                                            </td>

                                            <td class="text-center">
                                                {{ $instructor->fecha_nacimiento ? \Carbon\Carbon::parse($instructor->fecha_nacimiento)->format('d/m/Y') : '—' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $instructor->edad ?? '—' }}
                                            </td>
                                            <td>
                                                {{ $instructor->celular ?? '—' }}
                                            </td>
                                            <td>
                                                {{ $instructor->nombre_iglesia ?? 'Sin Iglesia' }}
                                            </td>
                                            
                                            <td> 
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                                <form action="" method="get">
                                                    <button type="submit" class="btn btn-warning">Editar</button>
                                                </form>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$instructor->id_instructor}}">Eliminar</button>
                                            </td>
                                        </tr>
                                         <div class="modal fade" id="confirmModal-{{$instructor->id_instructor}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de Confirmacion</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <strong style="color: red;">¿Seguro que quieres eliminar a este bautiso? </strong><br>
                                                    <strong> Nombre: </strong> <strong>{{ $instructor->nombre }}</strong>
                                                {{ $instructor->ape_paterno }}
                                                {{ $instructor->ape_materno ?? '' }} 
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <form action="{{ route('instructores.destroy', ['instructore' => $instructor->id_instructor]) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Confirmar</button>
                                                    </form>

                                                    
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Nombre Completo</th>
                                            <th>Sexo</th>
                                            <th>Fecha Nacimiento</th>
                                            <th>Edad</th>
                                            <th>Celular</th>
                                            <th>Iglesia</th>
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