@extends('template')


@section('title', 'Tablas')

@push('css')
    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />

@endpush


        @section('content')
        <!-- CONTENIDO DEL Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
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
                                Tabla de Iglesias
                            </div>
                            <div class="card-body">
                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Nombre</font></font></th>
                                            <th><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Posición</font></font></th>
                                            <th><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Oficina</font></font></th>
                                            <th><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Edad</font></font></th>
                                            <th><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Fecha de inicio</font></font></th>
                                            <th><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Salario</font></font></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Tigre Nixon</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Arquitecto de sistemas</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Edimburgo</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">61</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">25 de abril de 2011</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$320,800</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Garrett Winters</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Contador</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Tokio</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">63</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">25 de julio de 2011</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$170,750</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Ashton Cox</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Autor técnico junior</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">San Francisco</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">66</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">12 de enero de 2009</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$86,000</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Cedric Kelly</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Desarrollador sénior de JavaScript</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Edimburgo</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">22</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">29 de marzo de 2012</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$433,060</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Airi Sato</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Contador</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Tokio</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">33</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">28 de noviembre de 2008</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$162,700</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Brielle Williamson</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Especialista en Integración</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Nueva York</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">61</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">02-12-2012</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$372,000</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Herrod Chandler</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Asistente de ventas</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">San Francisco</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">59</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">6 de agosto de 2012</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$137,500</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Rhona Davidson</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Especialista en Integración</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Tokio</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">55</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">14 de octubre de 2010</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$327,900</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Colleen Hurst</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Desarrollador de JavaScript</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">San Francisco</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">39</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">15 de septiembre de 2009</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$205,500</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Sonia Frost</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Ingeniero de software</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Edimburgo</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">23</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">13 de diciembre de 2008</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$103,600</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Jena Gaines</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Gerente de oficina</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Londres</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">30</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">19 de diciembre de 2008</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$90,560</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Quinn Flynn</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Líder de soporte</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Edimburgo</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">22</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">03-03-2013</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$342,000</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Charde Marshall</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Director regional</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">San Francisco</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">36</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">16 de octubre de 2008</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$470,600</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Haley Kennedy</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Diseñador Senior de Marketing</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Londres</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">43</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">18 de diciembre de 2012</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$313,500</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Tatiana Fitzpatrick</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Director regional</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Londres</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">19</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">17 de marzo de 2010</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$385,750</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Michael Silva</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Diseñador de marketing</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Londres</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">66</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">27 de noviembre de 2012</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$198,500</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Paul Byrd</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Director financiero (CFO)</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Nueva York</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">64</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">09-06-2010</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$725,000</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Gloria Little</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Administrador de sistemas</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Nueva York</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">59</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">10 de abril de 2009</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$237,500</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Bradley Greer</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Ingeniero de software</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Londres</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">41</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">13 de octubre de 2012</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$132,000</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Dai Ríos</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Líder de personal</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Edimburgo</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">35</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">26 de septiembre de 2012</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$217,500</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Jenette Caldwell</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Líder de desarrollo</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Nueva York</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">30</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">03-09-2011</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$345,000</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Yuri Berry</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Director de Marketing (CMO)</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Nueva York</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">40</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">25 de junio de 2009</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$675,000</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">César Vance</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Soporte de preventa</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Nueva York</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">21</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">12 de diciembre de 2011</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$106,450</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Doris Wilder</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Asistente de ventas</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Sídney</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">23</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">20 de septiembre de 2010</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$85,600</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Angélica Ramos</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Director ejecutivo (CEO)</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Londres</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">47</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">09-10-2009</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$1,200,000</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Gavin Joyce</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Revelador</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Edimburgo</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">42</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">22 de diciembre de 2010</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$92,575</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Jennifer Chang</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Director regional</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Singapur</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">28</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">14 de noviembre de 2010</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$357,650</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Brenden Wagner</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Ingeniero de software</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">San Francisco</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">28</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">07-06-2011</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$206,850</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Fiona Green</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Director de Operaciones (COO)</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">San Francisco</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">48</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">11 de marzo de 2010</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$850,000</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Shou Itou</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Marketing regional</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Tokio</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">20</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">14 de agosto de 2011</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$163,000</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Casa Michelle</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Especialista en Integración</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Sídney</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">37</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">02-06-2011</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$95,400</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Suki Burks</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Revelador</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Londres</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">53</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">22 de octubre de 2009</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$114,500</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Prescott Bartlett</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Autor técnico</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Londres</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">27</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">7 de mayo de 2011</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$145,000</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Gavin Cortez</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Jefe de equipo</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">San Francisco</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">22</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">26 de octubre de 2008</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$235,500</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Martena McCray</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Soporte posventa</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Edimburgo</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">46</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">09-03-2011</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$324,050</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Mayordomo de Unity</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Diseñador de marketing</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">San Francisco</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">47</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">9 de diciembre de 2009</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$85,675</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Howard Hatfield</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Gerente de oficina</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">San Francisco</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">51</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">16 de diciembre de 2008</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$164,500</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Esperanza Fuentes</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Secretario</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">San Francisco</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">41</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">12 de febrero de 2010</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$109,850</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Vivian Harrell</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Controlador financiero</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">San Francisco</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">62</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">14 de febrero de 2009</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$452,500</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Timothy Mooney</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Gerente de oficina</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Londres</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">37</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">11 de diciembre de 2008</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">$136,200</font></font></td>
                                        </tr>
                                        <tr>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Jackson Bradshaw</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Director</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Nueva York</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">65</font></font></td>
                                            <td><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">26 de septiembre de 2008</font></font></td>
                                            <td>$645,750</td>
                                        </tr>
                                        <tr>
                                            <td>Olivia Liang</td>
                                            <td>Support Engineer</td>
                                            <td>Singapore</td>
                                            <td>64</td>
                                            <td>2011-02-03</td>
                                            <td>$234,500</td>
                                        </tr>
                                        <tr>
                                            <td>Bruno Nash</td>
                                            <td>Software Engineer</td>
                                            <td>London</td>
                                            <td>38</td>
                                            <td>2011-05-03</td>
                                            <td>$163,500</td>
                                        </tr>
                                        <tr>
                                            <td>Sakura Yamamoto</td>
                                            <td>Support Engineer</td>
                                            <td>Tokyo</td>
                                            <td>37</td>
                                            <td>2009-08-19</td>
                                            <td>$139,575</td>
                                        </tr>
                                        <tr>
                                            <td>Thor Walton</td>
                                            <td>Developer</td>
                                            <td>New York</td>
                                            <td>61</td>
                                            <td>2013-08-11</td>
                                            <td>$98,540</td>
                                        </tr>
                                        <tr>
                                            <td>Finn Camacho</td>
                                            <td>Support Engineer</td>
                                            <td>San Francisco</td>
                                            <td>47</td>
                                            <td>2009-07-07</td>
                                            <td>$87,500</td>
                                        </tr>
                                        <tr>
                                            <td>Serge Baldwin</td>
                                            <td>Data Coordinator</td>
                                            <td>Singapore</td>
                                            <td>64</td>
                                            <td>2012-04-09</td>
                                            <td>$138,575</td>
                                        </tr>
                                        <tr>
                                            <td>Zenaida Frank</td>
                                            <td>Software Engineer</td>
                                            <td>New York</td>
                                            <td>63</td>
                                            <td>2010-01-04</td>
                                            <td>$125,250</td>
                                        </tr>
                                        <tr>
                                            <td>Zorita Serrano</td>
                                            <td>Software Engineer</td>
                                            <td>San Francisco</td>
                                            <td>56</td>
                                            <td>2012-06-01</td>
                                            <td>$115,000</td>
                                        </tr>
                                        <tr>
                                            <td>Jennifer Acosta</td>
                                            <td>Junior JavaScript Developer</td>
                                            <td>Edinburgh</td>
                                            <td>43</td>
                                            <td>2013-02-01</td>
                                            <td>$75,650</td>
                                        </tr>
                                        <tr>
                                            <td>Cara Stevens</td>
                                            <td>Sales Assistant</td>
                                            <td>New York</td>
                                            <td>46</td>
                                            <td>2011-12-06</td>
                                            <td>$145,600</td>
                                        </tr>
                                        <tr>
                                            <td>Hermione Butler</td>
                                            <td>Regional Director</td>
                                            <td>London</td>
                                            <td>47</td>
                                            <td>2011-03-21</td>
                                            <td>$356,250</td>
                                        </tr>
                                        <tr>
                                            <td>Lael Greer</td>
                                            <td>Systems Administrator</td>
                                            <td>London</td>
                                            <td>21</td>
                                            <td>2009-02-27</td>
                                            <td>$103,500</td>
                                        </tr>
                                        <tr>
                                            <td>Jonas Alexander</td>
                                            <td>Developer</td>
                                            <td>San Francisco</td>
                                            <td>30</td>
                                            <td>2010-07-14</td>
                                            <td>$86,500</td>
                                        </tr>
                                        <tr>
                                            <td>Shad Decker</td>
                                            <td>Regional Director</td>
                                            <td>Edinburgh</td>
                                            <td>51</td>
                                            <td>2008-11-13</td>
                                            <td>$183,000</td>
                                        </tr>
                                        <tr>
                                            <td>Michael Bruce</td>
                                            <td>JavaScript Developer</td>
                                            <td>Singapore</td>
                                            <td>29</td>
                                            <td>2011-06-27</td>
                                            <td>$183,000</td>
                                        </tr>
                                        <tr>
                                            <td>Donna Snider</td>
                                            <td>Customer Support</td>
                                            <td>New York</td>
                                            <td>27</td>
                                            <td>2011-01-25</td>
                                            <td>$112,000</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Office</th>
                                            <th>Age</th>
                                            <th>Start date</th>
                                            <th>Salary</th>
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