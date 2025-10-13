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
        <!--contenido-->
        <div class="app-content">
            <div class="container-fluid px-3 " style="background-color:  #21324e ;"><!--#0f2e60-->

                <div class="container-fluid pt-4 ">
                        <center><h1 class="text-white" >PERFIL PASTORAL </h1></center>
                    <!--Cabecera-->
                    <div class="row" ><!--fila con3 de pading-->

                        <div class="col">
                            <h6><strong>Nombre Completo:</strong></h6>
                            <h6><strong>C.I.:</strong></h6>
                            <h6><strong>cargo:</strong></h6>
                            <h6><strong>Fecha de Nacimiento: </strong></h6>  
                            <h6><strong>Edad:</strong></h6>
                            <h6><strong>Celular:</strong></h6>
                            <h6><strong>Domicilio:</strong></h6>
                            @if($pastor->ordenado)
                                <hr>
                                <h6 style="color: white"><strong>Pastor Ordenado</strong> </h6>
                                <h6><strong>Fecha de ordenacion:</strong> </h6>     
                            @else
                                
                            @endif
                        </div>
                        <div class="col-6">
                            
                                <span class="ms-3  ">{{$pastor->nombre}} {{$pastor->ape_paterno}} {{$pastor->ape_materno}}</span><br>
                                <span class="ms-3  ">{{$pastor->ci}}</span><br>
                                <span class="ms-3  ">{{$pastor->cargo}}</span><br>
                                <span class="ms-3 ">{{$pastor->fecha_nac}}</span><br>
                                <span class="ms-3">{{$pastor->edad }} Años</span><br>
                                <span class="ms-3 "></span><br>
                                <span class="ms-3 "></span><br>
                                @if($pastor->ordenado)
                                    <br>
                                    <br>
                                    <br>
                                    <span class="ms-3">{{$pastor->fecha_ordenacion}}</span><br>     
                                @else
                                    
                                @endif

                        </div>
                        <div class="col ">
                            <div class="row mb-3">
                                <a href=""><button type="button" class="btn btn-primary"> <i class="fa-solid fa-user-pen"></i>&nbsp Editar Perfil</button> </a>
                            </div>
                            <div class="row mb-3">
                                <a href=""><button type="button" class="btn btn-primary"> <i class="fa-solid fa-file-pdf"></i>&nbsp Reporte Historial </button> </a>
                            </div>
                            <div class="row mb-3">
                                <a href=""><button type="button" class="btn btn-success"> <i class="fa-solid fa-plus"></i> &nbsp  Nueva Consulta </button> </a>   
                            </div>
                        </div>
                    </div>
                    <!-- Menu de odontograma y odotograma -->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-3 col-6">
                            <!--begin::Small Box Widget 1-->
                            <div class="small-box text-bg-primary">
                                <div class="inner">
                                    <h3>6</h3>
                                    <p>Cantidad de Distritos</p>
                                </div>
                                <svg
                                    class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path
                                    d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"
                                    ></path>
                                </svg>
                                <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                                    Mas información <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                            <!--end::Small Box Widget 1-->
                        </div>
                        <!--end::Col-->
                        <div class="col-lg-3 col-6">
                            <!--begin::Small Box Widget 2-->
                            <div class="small-box text-bg-success">
                            <div class="inner">
                                <h3>53<sup class="fs-5">%</sup></h3>
                                <p>Años de Servicio</p>
                            </div>
                            <svg
                                class="small-box-icon"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true"
                            >
                                <path
                                d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"
                                ></path>
                            </svg>
                            <a
                                href="#"
                                class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                            >
                                Mas informacion <i class="bi bi-link-45deg"></i>
                            </a>
                            </div>
                            <!--end::Small Box Widget 2-->
                        </div>
                        <!--end::Col-->
                        <div class="col-lg-3 col-6">
                            <!--begin::Small Box Widget 3-->
                            <div class="small-box text-bg-warning">
                            <div class="inner">
                                <h3>44</h3>
                                <p>_______________</p>
                            </div>
                            <svg
                                class="small-box-icon"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true"
                            >
                                <path
                                d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"
                                ></path>
                            </svg>
                            <a
                                href="#"
                                class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover"
                            >
                                Mas informacion <i class="bi bi-link-45deg"></i>
                            </a>
                            </div>
                            <!--end::Small Box Widget 3-->
                        </div>
                        <!--end::Col-->
                        <div class="col-lg-3 col-6">
                            <!--begin::Small Box Widget 4-->
                            <div class="small-box text-bg-danger">
                            <div class="inner">
                                <h3>65</h3>
                                <p>Unique Visitors</p>
                            </div>
                            <svg
                                class="small-box-icon"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true"
                            >
                                <path
                                clip-rule="evenodd"
                                fill-rule="evenodd"
                                d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z"
                                ></path>
                                <path
                                clip-rule="evenodd"
                                fill-rule="evenodd"
                                d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z"
                                ></path>
                            </svg>
                            <a
                                href="#"
                                class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                            >
                                More info <i class="bi bi-link-45deg"></i>
                            </a>
                            </div>
                            <!--end::Small Box Widget 4-->
                        </div>
                        <!--end::Col-->
                    </div>



                    <!-- Menu de odontograma y odotograma -->
                    <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
                        <div class="container-fluid">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                            <a class="navbar-brand" href="">HISTORIAL DE DISTRITOS</a>
                            
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <!--<li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#">Odontograma Niño</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link" href="#">Odontrograma Mixto</a>
                                </li>-->
                                <li class="nav-item">
                                <a  class="nav-link active" aria-current="page" href=""> Sesiones</a>
                                </li>
                            </ul>
                            <form class="d-flex" role="search">
                                <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search">
                                <button class="btn btn-outline-success" type="submit">Buscar</button>
                            </form>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
                <!-- odotograma -->
            <div class="container-fluid px-3 ">
                <div class="container-fluid " >
                    
                    <div class="row" ><!--fila con3 de pading-->
                    
                        <!-- CONSULTAS DE TRATAMIENTOS-->
                        <div class="col-md-12" ><!-- Columna con información <div class="col-md-8" >-->

                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        @endsection



@push('js')


@endpush