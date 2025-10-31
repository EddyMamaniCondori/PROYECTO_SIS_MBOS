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
                    <a href="#" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Dashboard administrativo</p>
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

              <li class="nav-item">
                <a href="{{ route('pastores.index')  }}" class="nav-link">
                  <i class="bi bi-person"></i>
                  <p>Pastores</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('grupo.index')  }}" class="nav-link">
                  <i class="bi bi-person"></i>
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
                  <li class="nav-item">
                    <a href="{{ route('distritos.index')  }}" class="nav-link">
                      <i class="bi bi-houses-fill"></i>&nbsp
                      <p>Distritos</p>
                    </a>
                  </li>
                    <li class="nav-item">
                        <a href="{{ route('distritos.asignaciones') }}" class="nav-link">
                            <i class="bi bi-house-gear-fill"></i>
                            <p>Asignación distrital</p>&nbsp
                        </a>
                    </li>
                    @if($sw_cambio)
                    <li class="nav-item">
                        <a href="{{ route('distritos.asiganual') }}" class="nav-link">
                            <i class="bi bi-bezier2"></i>
                            <p>Asignación distrital anual</p>&nbsp
                        </a>
                    </li>
                    @endif
                  
                  

                  <li class="nav-item">
                    <a href="{{ route('distritos.historiales')}}" class="nav-link">
                      <i class="bi bi-house-exclamation-fill"></i>&nbsp
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
                  <li class="nav-item">
                    <a href="{{ route('iglesias.index')  }}" class="nav-link">
                      <i class="bi bi-houses"></i>
                      <p>Iglesias</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./widgets/info-box.html" class="nav-link">
                      <i class="bi bi-house-check"></i>
                      <p>iglesias asignadas</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="bi bi-house-add"></i>
                      <p>asignar iglesia</p>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- desplegable-->
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="bi bi-house-fill"></i>
                  <p>
                    Desafios
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('desafios.index')  }}" class="nav-link">
                      <i class="bi bi-houses"></i>
                      <p>Desafios</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('desafios.mes')  }}" class="nav-link">
                      <i class="bi bi-house-check"></i>
                      <p>Administrador de desafios</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="bi bi-house-add"></i>
                      <p>asignar Desafio</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-clipboard-fill"></i>
                  <p>
                    Visitas
                    
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('visitas.index')}}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Visitas</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('visitas.dashboard')}}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Dashboard de Visitas</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-clipboard-fill"></i>
                  <p>
                    Bautisos
                    <span class="nav-badge badge text-bg-secondary me-3">6</span>
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('bautisos.index')}}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Bautisos</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('bautiso.dashboard')}}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Dashboard de Bautisos</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('bautisos.index')}}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Bautisos GP</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('bautisos.index')}}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Bautisos Distritales</p>
                    </a>
                  </li>
                </ul>
              </li>
              
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-pencil-square"></i>
                  <p>
                    Estudiantes Biblicos
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('estudiantes.dashboard')  }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Dashboard estudiantes biblicos</p>
                    </a>
                    <a href="{{ route('estudiantes.index')  }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>estudiantes biblicos</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-table"></i>
                  <p>
                    Instructores Biblicos
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('instructores.dashboard')  }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Dashboard instructores Biblicos</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('instructores.index')  }}" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>instructores Biblicos</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-header">FINANCIERO</li>


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
                    <a href="{{ route('remesas.index')  }} " class="nav-link">
                      <i class="bi bi-cash-coin"></i>
                      <p> Remesas</p>
                    </a>
                    <a href="{{ route('estudiantes.index')  }}" class="nav-link">
                      <i class="bi bi-stars"></i>
                      <p>Puntualidad</p>
                    </a>
                    <a href="{{ route('estudiantes.index')  }}" class="nav-link">
                      <i class="bi bi-file-earmark-bar-graph"></i>
                      <p>Informe</p>
                    </a>
                  </li>
                </ul>
              </li>

              
              <li class="nav-item">
                    
                  </li>

                  <li class="nav-item">
                    <a href="./examples/lockscreen.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p> Egresos</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./examples/lockscreen.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p> Reportes</p>
                    </a>
                  </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-box-arrow-in-right"></i>
                  <p>
                    Auth
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">

                  




                                    <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon bi bi-box-arrow-in-right"></i>
                      <p>
                        Version 1
                        <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="./examples/login.html" class="nav-link">
                          <i class="nav-icon bi bi-circle"></i>
                          <p>Login</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="./examples/register.html" class="nav-link">
                          <i class="nav-icon bi bi-circle"></i>
                          <p>Register</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon bi bi-box-arrow-in-right"></i>
                      <p>
                        Version 2
                        <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="./examples/login-v2.html" class="nav-link">
                          <i class="nav-icon bi bi-circle"></i>
                          <p>Login</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="./examples/register-v2.html" class="nav-link">
                          <i class="nav-icon bi bi-circle"></i>
                          <p>Register</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>
              <li class="nav-header">DOCUMENTATIONS</li>
              <li class="nav-item">
                <a href="./docs/introduction.html" class="nav-link">
                  <i class="nav-icon bi bi-download"></i>
                  <p>Installation</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./docs/layout.html" class="nav-link">
                  <i class="nav-icon bi bi-grip-horizontal"></i>
                  <p>Layout</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./docs/color-mode.html" class="nav-link">
                  <i class="nav-icon bi bi-star-half"></i>
                  <p>Color Mode</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-ui-checks-grid"></i>
                  <p>
                    Components
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="./docs/components/main-header.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Main Header</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./docs/components/main-sidebar.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Main Sidebar</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-filetype-js"></i>
                  <p>
                    Javascript
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="./docs/javascript/treeview.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Treeview</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="./docs/browser-support.html" class="nav-link">
                  <i class="nav-icon bi bi-browser-edge"></i>
                  <p>Browser Support</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./docs/how-to-contribute.html" class="nav-link">
                  <i class="nav-icon bi bi-hand-thumbs-up-fill"></i>
                  <p>How To Contribute</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./docs/faq.html" class="nav-link">
                  <i class="nav-icon bi bi-question-circle-fill"></i>
                  <p>FAQ</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./docs/license.html" class="nav-link">
                  <i class="nav-icon bi bi-patch-check-fill"></i>
                  <p>License</p>
                </a>
              </li>
              <li class="nav-header">MULTI LEVEL EXAMPLE</li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-circle-fill"></i>
                  <p>Level 1</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-circle-fill"></i>
                  <p>
                    Level 1
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Level 2</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>
                        Level 2
                        <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="#" class="nav-link">
                          <i class="nav-icon bi bi-record-circle-fill"></i>
                          <p>Level 3</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#" class="nav-link">
                          <i class="nav-icon bi bi-record-circle-fill"></i>
                          <p>Level 3</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#" class="nav-link">
                          <i class="nav-icon bi bi-record-circle-fill"></i>
                          <p>Level 3</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Level 2</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-circle-fill"></i>
                  <p>Level 1</p>
                </a>
              </li>
              <li class="nav-header">LABELS</li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-circle text-danger"></i>
                  <p class="text">Important</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-circle text-warning"></i>
                  <p>Warning</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-circle text-info"></i>
                  <p>Informational</p>
                </a>
              </li>
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>