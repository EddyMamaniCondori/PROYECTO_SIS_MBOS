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
              <li class="nav-item">
                <a href="#" class="nav-link ">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    Dashboard
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item ms-3">
                        <a href="{{ route('panel')}}" class="nav-link ">
                          <i class="bi bi-pie-chart-fill"></i>
                          <p>Panel Principal</p>
                        </a>
                      </li>

                  @can('ver dashboard pastores-panel')
                      <li class="nav-item ms-3">
                        <a href="{{ route('dashboard.pastor')}}" class="nav-link ">
                          <i class="bi bi-pie-chart-fill"></i>
                          <p>Dashboard Pastores</p>
                        </a>
                      </li>
                  @endcan
                  @can('ver meses-remesas')
                      <li class="nav-item ms-3">
                        <a href="{{ route('dashboard.tesorero')}}" class="nav-link ">
                          <i class="bi bi-pie-chart-fill"></i>
                          <p>Dashboard Tesorería</p>
                        </a>
                      </li>
                  @endcan
                  @can('ver-desafios bautisos mbos anuales')
                      <li class="nav-item ms-3">
                        <a href="{{ route('dashboard.secretario')}}" class="nav-link ">
                          <i class="bi bi-pie-chart-fill"></i>
                          <p>Dashboard Secretaria</p>
                        </a>
                      </li>
                  @endcan
                </ul>
              </li>
              <!--SECCION sEXREATRIA-->
              <li class="nav-header"> SECRETARIA </li>
              <!-- desplegable-->
              @canany(['ver-pastores', 'ver-personal', 'ver-administrativo'])
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="bi bi-people-fill"></i>
                  <p>
                    Usuarios
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @can('ver-pastores')
                    <li class="nav-item ms-3">
                      <a href="{{ route('pastores.index')  }}" class="nav-link">
                        <i class="bi bi-person"></i>
                        <p>Pastores</p>
                      </a>
                    </li>
                  @endcan
                  @can('ver-personal')
                    <li class="nav-item ms-3">
                      <a href="{{ route('personales.index')  }}" class="nav-link">
                        <i class="bi bi-person"></i>
                        <p>Personal</p>
                      </a>
                    </li>
                  @endcan
                  @can('ver-administrativo')
                    <li class="nav-item ms-3">
                      <a href="{{ route('administrativos.index')}}" class="nav-link">
                        <i class="bi bi-person"></i>
                        <p>Administrativos</p>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
              @endcanany
              @can('ver-grupos')
                <li class="nav-item">
                  <a href="{{ route('grupo.index')  }}" class="nav-link">
                    <i class="fa-solid fa-people-group"></i>
                    <p>Grupos Pequeños</p>
                  </a>
                </li>
              @endcan
              
               <!-- desplegable-->
              @can('ver-distritos')
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="bi bi-house-fill"></i>
                    <p>
                      Distritos
                      <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    @can('ver-distritos')
                      <li class="nav-item ms-3" >
                        <a href="{{ route('distritos.index')  }}" class="nav-link">
                          <i class="bi bi-house-gear"></i>&nbsp
                          <p>Distritos</p>
                        </a>
                      </li>
                    @endcan
                    @can('cambiar asignaciones ACT - distritos')
                      <li class="nav-item ms-3">
                        <a href="{{ route('distritos.asignaciones') }}" class="nav-link">
                            <i class="bi bi-house-gear"></i>
                            <p>Asignación distrital</p>&nbsp
                        </a>
                      </li>
                    @endcan
                    @can('cambiar asignaciones SIG - distritos')
                      @if($sw_cambio)
                        <li class="nav-item ms-3">
                            <a href="{{ route('distritos.asiganual') }}" class="nav-link">
                                <i class="bi bi-house-gear"></i>
                                <p>Asignación distrital anual</p>&nbsp
                            </a>
                        </li>
                      @endif
                    @endcan
                    @can('ver historial-distritos')
                        <li class="nav-item ms-3">
                          <a href="{{ route('distritos.historiales')}}" class="nav-link">
                            <i class="bi bi-house-exclamation"></i>&nbsp
                            <p>Historial</p>
                          </a>
                        </li>
                    @endcan
                  </ul>
                </li>
              @endcan
              
              @canany(['ver-iglesias','asignaciones-iglesias','ver pastor-iglesias'])
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fa-solid fa-church"></i>
                  <p>
                    Iglesias
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @can('ver-iglesias')
                  <li class="nav-item ms-3">
                    <a href="{{ route('iglesias.index')  }}" class="nav-link">
                      <i class="fa-solid fa-church"></i>
                      <p>Iglesias</p>
                    </a>
                  </li>
                  @endcan
                  @can('asignaciones-iglesias')
                  <li class="nav-item ms-3">
                    <a href="{{ route('iglesias.asignaciones')  }}" class="nav-link">
                      <i class="bi bi-house-check"></i>
                      <p>iglesias asignadas</p>
                    </a>
                  </li>
                  @endcan

                  @can('ver pastor-iglesias')
                  <li class="nav-item ms-3">
                    <a href="{{ route('iglesias.index_pastores')  }}" class="nav-link">
                      <i class="bi bi-house-check"></i>
                      <p>Mis Iglesias</p>
                    </a>
                  </li>
                  @endcan
                  @can('ver x distritos-lideres locales')
                  <li class="nav-item ms-3">
                    <a href="{{ route('iglesias.lideres_locales')  }}" class="nav-link">
                      <i class="bi bi-house-check"></i>
                      <p>Lideres Locales</p>
                    </a>
                  </li>
                  @endcan
                  
                </ul>
              </li>  
              @endcanany 
              <!-- desplegable-->
              @canany(['ver meses-visitas', 'ver anual-visitas', 'dashboard-visitas'])
                  <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="bi bi-person-walking"></i>
                  <p>
                    Visitas
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @can('ver meses-visitas')
                  <li class="nav-item ms-3">
                    <a href="{{ route('visitas.index_mes')}}" class="nav-link">
                      <i class="bi bi-person-add"></i>
                      <p> Registrar Visitas</p>
                    </a>
                  </li> 
                  @endcan
                
                  @can('ver anual-visitas')
                  <li class="nav-item ms-3">
                    <a href="{{ route('visitas.index')}}" class="nav-link">
                      <i class="bi bi-person"></i>
                      <p> Visitas</p>
                    </a>
                  </li>
                  @endcan

                  @can('dashboard-visitas')
                  <li class="nav-item ms-3">
                    <a href="{{ route('visitas.dashboard')}}" class="nav-link">
                      <i class="bi bi-graph-up"></i>
                      <p>Dashboard de Visitas</p>
                    </a>
                  </li>
                  @endcan
                </ul>
              </li>
              @endcanany
              
              @canany(['ver-bautisos','ver dashboard pastor-bautisos distrito', 'ver pastor-bautisos distrito'])
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fa-solid fa-dove"></i>
                  <p>
                    Bautismos
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @can('ver-bautisos')
                     <li class="nav-item ms-3">
                      <a href="{{ route('bautisos.index')}}" class="nav-link">
                        <i class="fa-solid fa-dove"></i>
                        <p>Registrar Bautismos</p>
                      </a>
                    </li> 
                  @endcan
                  @can('ver pastor-bautisos distrito')
                    <li class="nav-item ms-3">
                      <a href="{{ route('bautiso.pastor')}}" class="nav-link">
                        <i class="bi bi-bar-chart-fill"></i>
                        <p>Mis Bautismos</p>
                      </a>
                    </li>
                  @endcan
                  
                  @can('ver dashboard pastor-bautisos distrito')
                    <li class="nav-item ms-3">
                      <a href="{{ route('bautiso.dashboard.pastoral')}}" class="nav-link">
                        <i class="bi bi-bar-chart-fill"></i>
                        <p>Seguimiento </p>
                      </a>
                    </li>
                  @endcan

                  

                  
                </ul>
              </li>
              @endcanany
              
              @can('ver-estudiantes')
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fa-solid fa-user-graduate"></i>
                  <p>
                    Estudiantes Biblicos
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @can('ver-estudiantes')
                    <li class="nav-item ms-3">
                      <a href="{{ route('estudiantes.index')  }}" class="nav-link">
                        <i class="bi bi-person"></i>
                        <p>estudiantes biblicos</p>
                      </a>
                    </li>
                  @endcan
                  @can('ver avance-estudiantes')
                    <li class="nav-item ms-3">
                      <a href="{{ route('instructores.dashboard')  }}" class="nav-link">
                        <i class="bi bi-graph-up"></i>
                        <p>Dashboard estudiantes biblicos</p>
                      </a>
                    </li> 
                  @endcan
                </ul>
              </li>   
              @endcan
              
              @can('ver-instructores')
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="bi bi-person-workspace"></i>
                    <p>
                      Instructores Biblicos
                      <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    @can('ver-instructores')
                      <li class="nav-item ms-3">
                        <a href="{{ route('instructores.index')  }}" class="nav-link">
                          <i class="bi bi-person-check-fill"></i>
                          <p>instructores Biblicos</p>
                        </a>
                      </li> 
                    @endcan
                    @can('ver avance-instructores')
                      <li class="nav-item ms-3">
                        <a href="{{ route('instructores.dashboard')  }}" class="nav-link">
                          <i class="bi bi-graph-up"></i>
                          <p>Dashboard instructores Biblicos</p>
                        </a>
                      </li>
                    @endcan
                  </ul>
                </li> 
              @endcan
              
              <!--SECCCION DESAFIOS-->
              @canany(['ver-desafios','ver-desafios mensuales','ver-desafios eventos'])
                <li class="nav-header"> DESAFIOS </li>
                <!--desplehgable-->
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="bi bi-house-fill"></i>
                    <p>
                      Desafios Anual
                      <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item ms-3">
                      <a href="{{ route('desafios.index')  }}" class="nav-link">
                        <i class="bi bi-houses"></i>
                        <p>Administra Desafios</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--termina desplehgable-->
                @canany(['ver-desafios bautisos mbos anuales', 'dashboard-mbos bautisos'])
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="fa-solid fa-dove"></i>
                      <p>
                        Bautismos
                        <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      @can('ver-desafios bautisos mbos anuales')
                      <li class="nav-item ms-3">
                        <a href="{{ route('desafios.bautizos')  }}" class="nav-link">
                          <i class="fa-solid fa-dove"></i>
                          <p>Administrar Blancos</p>
                        </a>
                      </li>
                      @endcan
                      @can('dashboard-mbos bautisos')
                      <li class="nav-item ms-3">
                        <a href="{{ route('bautizos.dashboard')}}" class="nav-link">
                          <i class="bi bi-graph-up-arrow"></i>
                          <p>Seguimiento MBOS </p>
                        </a>
                      </li>
                      @endcan
                    </ul>
                  </li>
                @endcanany
                <!--desplehgable-->
                @canany(['ver-desafios mensuales','ver los blancos de 1 mes-desafios mensuales','editar desafios mes masivo-desafios mensuales'])
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="fa-solid fa-route"></i>
                      <p>
                        Visitas Mensuales
                        <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      @canany(['ver-desafios mensuales', 'ver los blancos de 1 mes-desafios mensuales','editar desafios mes masivo-desafios mensuales'])
                        <li class="nav-item ms-3">
                          <a href="{{route('desafios_mensuales.index')}}" class="nav-link">
                            <i class="bi bi-gear"></i>
                            <p>Administrar Visitas</p>
                          </a>
                        </li>
                      @endcanany
                      @can('graficos todos los meses MBOS-desafios mensuales')
                        <li class="nav-item ms-3">
                          <a href="{{route('mensuales.dashboard_meses')}}" class="nav-link">
                            <i class="fa-solid fa-chart-simple"></i>
                            <p>Seguimiento MBOS</p>
                          </a>
                        </li>
                      @endcan
                    </ul>
                  </li>
                @endcanany
                <!--termina desplehgable-->
                
                
                

                @can('ver mbos-desafios anual Est Inst')
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="fa-solid fa-people-pulling"></i>
                      <p>
                        EE/II
                        <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      @can('ver mbos-desafios anual Est Inst')
                        <li class="nav-item ms-3">
                          <a href="{{ route('instructores.mbos.distrital')  }}" class="nav-link">
                            <i class="bi bi-gear"></i>
                            <p>Administrar Desafios</p>
                          </a>
                        </li>
                      @endcan
                      @can('ver mbos-desafios anual Est Inst') 
                        <li class="nav-item ms-3">
                          <a href="{{ route('instructores.mbos.distrital.ver')  }}" class="nav-link">
                            <i class="bi bi-graph-up-arrow"></i>
                            <p>Seguimiento MBOS</p>
                          </a>
                        </li>
                      @endcan 
                    </ul>
                  </li>  
                @endcan

                @canany(['ver-desafios eventos','ver-asignacion desafios eventos','ver-dashboards desafios eventos'])
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="fa-solid fa-people-roof"></i> 
                      <p>
                        Campañas
                        <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      @can('ver-desafios eventos')
                        <li class="nav-item ms-3">
                          <a href="{{ route('desafio_eventos.index')  }}" class="nav-link">
                            <i class="fa-solid fa-people-roof"></i>
                            <p>Administrar Campañas</p>
                          </a>
                        </li>
                      @endcan
                      @can('ver-asignacion desafios eventos')
                        <li class="nav-item ms-3">
                          <a href="{{ route('desafio_eventos.indexasignaciones')  }}" class="nav-link">
                            <i class="bi bi-diagram-3"></i>
                            <p>Asignar Desafios</p>
                          </a>
                        </li>
                      @endcan
                      @can('ver-dashboards desafios eventos')
                          <li class="nav-item ms-3">
                          <a href="{{ route('desafio_eventos.dashboard_asignaciones')  }}" class="nav-link">
                            <i class="bi bi-graph-up-arrow"></i>       
                            <p>Seguimiento MBOS</p>
                          </a>
                        </li> 
                      @endcan
                    </ul>
                  </li>  
                @endcanany
              @endcanany
              

              <!--SECCCION FINANCIERO-->
              @canany(['ver meses-remesas', 'ver-remesas excel','ver-blanco', 'ver-puntualidad', 'ver distrital-pendientes','ver dashboar pastor-remesas dashboard'])
                  <li class="nav-header">FINANCIERO</li>
                <!--PANEL-->
                @can('ver meses-remesas')
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="bi bi-wallet2"></i>
                        <p>
                          Panel de Control
                          <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                      </a>
                      <ul class="nav nav-treeview">
  
                        <li class="nav-item ms-3">
                          <a href="{{ route('remesas.distrital.dash')  }}" class="nav-link">
                            <i class="bi bi-graph-up"></i>
                            <p> Dashboard General</p>
                          </a>
                        </li>
                        
                        <li class="nav-item ms-3">
                          <a href="{{ route('remesas.filiales.pivot')  }}" class="nav-link">
                            <i class="bi bi-graph-up"></i>
                            <p> Remesas Filiales</p>
                          </a>
                        </li>
                      </ul>
                    </li>
                @endcan
                
                <!--REMESAS-->
                @can('ver meses-remesas')
                   <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="fa-solid fa-money-check-dollar"></i>
                        <p>
                          Remesas
                          <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                      </a>
                      <ul class="nav nav-treeview">
                        <li class="nav-item ms-3">
                          <a href="{{ route('remesas.indeximport')  }} " class="nav-link">
                            <i class="bi bi-upload"></i>
                            <p> Importar Remesas</p>
                          </a>
                        </li>
                        <li class="nav-item ms-3">
                          <a href="{{ route('remesas.index')  }} " class="nav-link">
                            <i class="bi bi-list-check"></i>
                            <p> Gestionar Remesas</p>
                          </a>
                        </li>

                      </ul>
                    </li> 
                @endcan
                
                <!--REMESAS-->
                @can('ver-puntualidad')
                    <li class="nav-item">
                      <a href="{{ route('remesas.puntualidades')  }}" class="nav-link">
                        <i class="fa-solid fa-business-time"></i>
                        <p> Puntualidad</p>
                      </a>
                    </li>
                @endcan
                  
                @can('ver-blanco')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="bi bi-clipboard-data-fill"></i>
                      <p>
                        Blancos
                        <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">

                      <li class="nav-item ms-3">
                        <a href="{{ route('blancos.index')  }}" class="nav-link">
                          <i class="bi bi-clipboard2-data"></i>
                          <p> Blanco de remesas</p>
                        </a>
                      </li>
                      <li class="nav-item ms-3">
                            <a href="{{ route('remesas.tabla.distrital')  }}" class="nav-link">
                              <i class="bi bi-graph-up"></i>
                              <p> Remesas Distrital</p>
                            </a>
                      </li>
                    </ul>
                </li>
                @endcan
                  
                @canany(['ver anual-pendientes', 'ver distrital-pendientes', 'ver mensual-pendientes', 'ver dashboar remesas filiales pastor-remesas dashboard'])
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="bi bi-stopwatch"></i>
                      <p>
                        Pendientes
                        <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                    @can('ver anual-pendientes')
                        <li class="nav-item ms-3">
                        <a href="{{ route('remesas.pendientes')}} " class="nav-link">
                          <i class="bi bi-stopwatch"></i>
                          <p> anuales</p>
                        </a>
                      </li>
                    @endcan
                    
                    @can('ver mensual-pendientes')
                        <li class="nav-item ms-3">
                        <a href="{{ route('remesas.pendientes.mensual')}} " class="nav-link">
                          <i class="bi bi-stopwatch"></i>
                          <p> Mensual</p>
                        </a>
                      </li>
                    @endcan
                    @can('ver distrital-pendientes')
                        <li class="nav-item ms-3">
                        <a href="{{ route('remesas.pendientes.distrital')}}" class="nav-link">
                          <i class="bi bi-stopwatch"></i>
                          <p>Distrital</p>
                        </a>
                      </li>
                    @endcan
                    </ul>
                  </li>
                @endcanany
                <!--PENDIENTES-->
                
                <!--Solo Pastores pueden verlo -->
                @can('ver dashboar pastor-remesas dashboard')
                    <li class="nav-item">
                      <a href="{{route('remesas.distrital.dash_general')}}" class="nav-link">
                        <i class="fa-solid fa-money-bill-trend-up"></i>
                        <p>Seguimiento Distrital</p>
                      </a>
                    </li>
                @endcan
                <!--REPORTE FILIALES-->  
                @canany(['ver dashboar remesas filiales pastor-remesas dashboard', 'ver dashboar fondo local pastor-remesas dashboard'])
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="fa-solid fa-building-columns"></i>
                      <p>
                        Filiales
                        <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      @can('ver dashboar remesas filiales pastor-remesas dashboard')
                          <li class="nav-item ms-3">
                          <a href="{{route('remesas.distrital.filial.dash_general')}}" class="nav-link">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                            <p>Remesas</p>
                          </a>
                        </li>
                      @endcan
                      @can('ver dashboar fondo local pastor-remesas dashboard')
                          <li class="nav-item ms-3">
                            <a href="{{route('remesas.fondo_local.distrital.filial.dash_general')}}" class="nav-link">
                              <i class="fa-solid fa-piggy-bank"></i>
                              <p>Fondo Local</p>
                            </a>
                          </li>
                      @endcan
                      
                    </ul>
                  </li>
                @endcanany
              @endcanany
              
                <!--REPORTE FILIALES--> 

              

              @can('ver-roles')
                <li class="nav-header">ROLES Y PERMISOS</li>
                <li class="nav-item">
                  <a href="{{ route('roles.index')}}" class="nav-link">
                    <i class="nav-icon bi bi-download"></i>
                    <p>Roles</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('auditorias.index')}}" class="nav-link">
                    <i class="bi bi-fingerprint"></i>
                    <p>Auditoria</p>
                  </a>
                </li>
              @endcan
              
              
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>