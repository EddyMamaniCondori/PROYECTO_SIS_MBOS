<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
       <!--begin::Sidebar Brand-->
        <div class="sidebar-brand"> 
          <a href="#" class="brand-link">
            <img src="{{asset('img/logoiasd.png')}}" alt="IASD Logo" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">MBOS</span>
          </a>
        </div>


        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview"  role="navigation" aria-label="Main navigation" data-accordion="false"  id="navigation">
              <li class="nav-item menu-open">
                <a href="#" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    Dashboard
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('dashboard.pastor')}}" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Dashboard Pastores</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Dashboard distrital</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('iglesias.dashboard_general')  }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Dashboard iglesia</p>
                    </a>
                  </li>
                </ul>
              </li>
              <!--SECCION sEXREATRIA-->
              <li class="nav-header"> SECRETARIA </li>
              <!-- desplegable-->
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="bi bi-people-fill"></i>
                  <p>
                    Usuarios
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item ms-3">
                    <a href="{{ route('pastores.index')  }}" class="nav-link">
                      <i class="bi bi-person"></i>
                      <p>Pastores</p>
                    </a>
                  </li>
                  <li class="nav-item ms-3">
                    <a href="{{ route('personales.index')  }}" class="nav-link">
                      <i class="bi bi-person"></i>
                      <p>Personal</p>
                    </a>
                  </li>
                  <li class="nav-item ms-3">
                    <a href="{{ route('administrativos.index')}}" class="nav-link">
                      <i class="bi bi-person"></i>
                      <p>Administrativos</p>
                    </a>
                  </li>
                </ul>
              </li>

              
              <li class="nav-item">
                <a href="{{ route('grupo.index')  }}" class="nav-link">
                  <i class="fa-solid fa-people-group"></i>
                  <p>Grupos Pequeños</p>
                </a>
              </li>
               <!-- desplegable-->
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="bi bi-house-fill"></i>
                  <p>
                    Distritos
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item ms-3" >
                    <a href="{{ route('distritos.index')  }}" class="nav-link">
                      <i class="bi bi-house-gear"></i>&nbsp
                      <p>Distritos</p>
                    </a>
                  </li>
                    <li class="nav-item ms-3">
                        <a href="{{ route('distritos.asignaciones') }}" class="nav-link">
                            <i class="bi bi-house-gear"></i>
                            <p>Asignación distrital</p>&nbsp
                        </a>
                    </li>
                    @if($sw_cambio)
                    <li class="nav-item ms-3">
                        <a href="{{ route('distritos.asiganual') }}" class="nav-link">
                            <i class="bi bi-house-gear"></i>
                            <p>Asignación distrital anual</p>&nbsp
                        </a>
                    </li>
                    @endif
                  <li class="nav-item ms-3">
                    <a href="{{ route('distritos.historiales')}}" class="nav-link">
                      <i class="bi bi-house-exclamation"></i>&nbsp
                      <p>Historial</p>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- desplegable-->
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="bi bi-house-fill"></i>
                  <p>
                    Iglesias
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item ms-3">
                    <a href="{{ route('iglesias.index')  }}" class="nav-link">
                      <i class="bi bi-houses"></i>
                      <p>Iglesias</p>
                    </a>
                  </li>
                  <li class="nav-item ms-3">
                    <a href="{{ route('iglesias.asignaciones')  }}" class="nav-link">
                      <i class="bi bi-house-check"></i>
                      <p>iglesias asignadas</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="bi bi-person-walking"></i>
                  <p>
                    Visitas
                    
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item ms-3">
                    <a href="{{ route('visitas.index_mes')}}" class="nav-link">
                      <i class="bi bi-person-add"></i>
                      <p> Registrar Visitas</p>
                    </a>
                  </li>
                  <li class="nav-item ms-3">
                    <a href="{{ route('visitas.index')}}" class="nav-link">
                      <i class="bi bi-person"></i>
                      <p> Visitas</p>
                    </a>
                  </li>
                  <li class="nav-item ms-3">
                    <a href="{{ route('visitas.dashboard')}}" class="nav-link">
                      <i class="bi bi-graph-up"></i>
                      <p>Dashboard de Visitas</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fa-solid fa-dove"></i>
                  <p>
                    Bautisos
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item ms-3">
                    <a href="{{ route('bautisos.index')}}" class="nav-link">
                      <i class="fa-solid fa-dove"></i>
                      <p>Registrar Bautisos</p>
                    </a>
                  </li>
                  <li class="nav-item ms-3">
                    <a href="{{ route('bautiso.dashboard')}}" class="nav-link">
                      <i class="bi bi-bar-chart-fill"></i>
                      <p>Dashboard de Bautisos</p>
                    </a>
                  </li>
                </ul>
              </li>
              
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fa-solid fa-user-graduate"></i>
                  <p>
                    Estudiantes Biblicos
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item ms-3">
                    <a href="{{ route('estudiantes.index')  }}" class="nav-link">
                      <i class="bi bi-person"></i>
                      <p>estudiantes biblicos</p>
                    </a>
                  </li>
                
                  <li class="nav-item ms-3">
                    <a href="{{ route('instructores.dashboard')  }}" class="nav-link">
                      <i class="bi bi-graph-up"></i>
                      <p>Dashboard estudiantes biblicos</p>
                    </a>
                  </li>
                  
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="bi bi-person-workspace"></i>
                  <p>
                    Instructores Biblicos
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item ms-3">
                    <a href="{{ route('instructores.index')  }}" class="nav-link">
                      <i class="bi bi-person-check-fill"></i>
                      <p>instructores Biblicos</p>
                    </a>
                  </li>
                  <li class="nav-item ms-3">
                    <a href="{{ route('instructores.dashboard')  }}" class="nav-link">
                      <i class="bi bi-graph-up"></i>
                      <p>Dashboard instructores Biblicos</p>
                    </a>
                  </li>
                </ul>
              </li>
              <!--SECCCION DESAFIOS-->
              <li class="nav-header"> DESAFIOS </li>
              <!--desplehgable-->
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="bi bi-house-fill"></i>
                  <p>
                    Desafios
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item ms-3">
                    <a href="{{ route('desafios.index')  }}" class="nav-link">
                      <i class="bi bi-houses"></i>
                      <p>Desafios</p>
                    </a>
                  </li>
                  <li class="nav-item ms-3">
                    <a href="{{ route('desafios.mes')  }}" class="nav-link">
                      <i class="bi bi-house-check"></i>
                      <p>Administrador de desafios</p>
                    </a>
                  </li>
                  <li class="nav-item ms-3">
                    <a href="#" class="nav-link">
                      <i class="bi bi-house-add"></i>
                      <p>asignar Desafio</p>
                    </a>
                  </li>
                </ul>
              </li>
              <!--termina desplehgable-->



              <!--desplehgable-->
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fa-solid fa-dove"></i>
                  <p>
                    Bautizos
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item ms-3">
                    <a href="{{ route('desafios.bautizos')  }}" class="nav-link">
                      <i class="fa-solid fa-dove"></i>
                      <p>Administrar Blancos</p>
                    </a>
                  </li>
                  <li class="nav-item ms-3">
                    <a href="{{ route('bautizos.dashboard')}}" class="nav-link">
                      <i class="bi bi-house-add"></i>
                      <p>Dashboard</p>
                    </a>
                  </li>
                </ul>
              </li>
              <!--termina desplehgable-->
              <li class="nav-item">
                <a href="{{route('desafios_mensuales.index')}}" class="nav-link">
                  <i class="bi bi-database-fill-gear"></i>
                  <p>Visitas Mensuales</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fa-solid fa-people-roof"></i> 
                  <p>
                    Campañas
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item ms-3">
                    <a href="{{ route('desafio_eventos.index')  }}" class="nav-link">
                      <i class="fa-solid fa-people-roof"></i>
                      <p>Administrar Campañas</p>
                    </a>
                  </li>
                  <li class="nav-item ms-3">
                    <a href="{{ route('desafio_eventos.indexasignaciones')  }}" class="nav-link">
                      <i class="bi bi-diagram-3"></i>
                      <p>Asignar Desafios</p>
                    </a>
                  </li>
                  <li class="nav-item ms-3">
                    <a href="#" class="nav-link">
                      <i class="bi bi-graph-up-arrow"></i>       
                      <p>Dashboard Resultados</p>
                    </a>
                  </li>
                </ul>
              </li>

              <!--SECCCION FINANCIERO-->
              <li class="nav-header">FINANCIERO</li>
                <!--PANEL-->
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="bi bi-wallet2"></i>
                    <p>
                      Panel de Control
                      <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="{{ route('remesas.distrital.dashboard')  }}" class="nav-link">
                        <i class="bi bi-speedometer2"></i>
                        <p> Dashboard Distrital General</p>
                      </a>
                      <a href="{{ route('remesas.distrital.dash')  }}" class="nav-link">
                        <i class="bi bi-graph-up"></i>
                        <p> Dashboard Distrital</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--REMESAS-->
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="bi bi-wallet2"></i>
                    <p>
                      Remesas
                      <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="{{ route('remesas.indeximport')  }} " class="nav-link">
                        <i class="bi bi-upload"></i>
                        <p> Importar Remesas</p>
                      </a>
                      <a href="{{ route('remesas.index')  }} " class="nav-link">
                        <i class="bi bi-list-check"></i>
                        <p> Gestionar Remesas</p>
                      </a>
                      <a href="#" class="nav-link">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <p>Informe de Remesas</p>
                      </a>
                    </li>

                  </ul>
                </li>
                <!--REMESAS-->

                  <li class="nav-item">
                    <a href="{{ route('remesas.puntualidades')  }}" class="nav-link">
                      <i class="bi bi-stopwatch"></i>
                      <p> Puntualidad</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="{{ route('blancos.index')  }}" class="nav-link">
                      <i class="bi bi-stopwatch"></i>
                      <p> Blanco de remesas</p>
                    </a>
                  </li>
                
                <!--PENDIENTES-->
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="bi bi-stopwatch"></i>
                    <p>
                      Pendientes
                      <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="{{ route('remesas.pendientes')}} " class="nav-link">
                        <i class="bi bi-stopwatch"></i>
                        <p> anuales</p>
                      </a>
                      <a href="{{ route('remesas.pendientes.mensual')}} " class="nav-link">
                        <i class="bi bi-stopwatch"></i>
                        <p> Mensual</p>
                      </a>
                      <a href="{{ route('remesas.pendientes.distrital')}}" class="nav-link">
                        <i class="bi bi-stopwatch"></i>
                        <p>Distrital</p>
                      </a>
                    </li>

                  </ul>
                </li>
                <!--PENDIENTES-->
                <!--REPORTE FILIALES-->  
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon bi bi-pencil-square"></i>
                    <p>
                      REPORTES DE FILIALES
                      <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Reporte Fondo Local</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--REPORTE FILIALES--> 

              
              <li class="nav-header">ROLES Y PERMISOS</li>
              <li class="nav-item">
                <a href="{{ route('roles.index')}}" class="nav-link">
                  <i class="nav-icon bi bi-download"></i>
                  <p>Roles</p>
                </a>
              </li>
              
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>