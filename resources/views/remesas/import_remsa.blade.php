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
              <div class="col-sm-6"><center><h3 class="mb-0">IMPORTACION DE REMESAS </h3></center></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Remesas</li>
                </ol>
              </div>
            </div>
            <div class="row">
                <div class="col">
                    <form action="{{ route('remesas.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Subir archivo Excel:</label>
                            <input type="file" name="file" class="form-control" accept=".xls,.xlsx" required>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="bi bi-filetype-xlsx"></i>  &nbsp;Importar Archivo</button>
                    </form>
                </div>
                <div class="col">
                    <a href="{{ route('remesas.procesar', ['anio' => 2025]) }}" class="btn btn-primary"> <i class="bi bi-database-fill-down"></i>&nbsp;Guardar en Base de Datos</a>
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
                                <div class="row">
                                    <div class="col-9">
                                        <i class="bi bi-table"></i>
                                    Tabla de Remesas importadas  
                                    </div>
                                    <div class="col">
                                        <span>
                                            Total registros: {{ count($remesas) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Iglesia</th>
                                            <th>Eenero</th>
                                            <th>Febrero</th>
                                            <th>Marzo</th>
                                            <th>Abril</th>
                                            <th>Mayo</th>
                                            <th>Junio</th>
                                            <th>Julio</th>
                                            <th>Agosto</th>
                                            <th>septiembre</th>
                                            <th>Octubre</th>
                                            <th>Noviembre</th>
                                            <th>Diciembre</th>
                                            <th>Total</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($remesas as $remesa)
                                        <tr>
                                            <td>{{$remesa->codigo}}</td>
                                            <td>{{$remesa->nombre}}</td>
                                            <td>{{$remesa->valor_1}}</td>
                                            <td>{{$remesa->valor_2}}</td>
                                            <td>{{$remesa->valor_3}}</td>
                                            <td>{{$remesa->valor_4}}</td>
                                            <td>{{$remesa->valor_5}}</td>
                                            <td>{{$remesa->valor_6}}</td>
                                            <td>{{$remesa->valor_7}}</td>
                                            <td>{{$remesa->valor_8}}</td>
                                            <td>{{$remesa->valor_9}}</td>
                                            <td>{{$remesa->valor_10}}</td>
                                            <td>{{$remesa->valor_11}}</td>
                                            <td>{{$remesa->valor_12}}</td>
                                            <td>{{$remesa->total}}</td>
                                             <td>
                                                <div class="btn-group  justify-content-center" role="group" >

                                                    <button type="button" class="btn btn-warning">Editar</button>
                                                    <button type="button" class="btn btn-success">ver</button>

                                                    <form action="{{ route('remesasimport.destroy',['id'=>$remesa->id])}}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                     <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                                   
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach 
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Iglesia</th>
                                            <th>Eenero</th>
                                            <th>Febrero</th>
                                            <th>Marzo</th>
                                            <th>Abril</th>
                                            <th>Mayo</th>
                                            <th>Junio</th>
                                            <th>Julio</th>
                                            <th>Agosto</th>
                                            <th>septiembre</th>
                                            <th>Octubre</th>
                                            <th>Noviembre</th>
                                            <th>Diciembre</th>
                                            <th>Total</th>
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