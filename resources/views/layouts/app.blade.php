@php
    $notificationStyles = [
        'proveedor_desactualizado' => ['bg-warning', 'fas fa-truck'],
        'item_especifico_desactualizado' => ['bg-primary', 'fas fa-box-open'],
        'seleccion_lista' => ['bg-success', 'fas fa-list-alt'],
        'cotizacion_enviada' => ['bg-success', 'fas fa-file-alt'],
        'orden_venta_recibida' => ['bg-dark', 'fas fa-file-invoice-dollar'], // Documento financiero
    ];

    $type = $notification->data['type'] ?? 'default';
    $bgColor = $notificationStyles[$type][0] ?? 'bg-secondary';
    $icon = $notificationStyles[$type][1] ?? 'fas fa-cube';
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Leaflet CSS -->

    <link rel="icon" type="image/png" href="{{ asset('storage/StockImages/stockUser.png') }}">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">



    @livewireStyles
    <style>
        .img-redonda {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
    <style>
        .background-permanent {
            background-color: #003366 !important;
        }

        .background-default {
            background-color: initial !important;
        }

        .container-flex2 {
            display: flex;
            flex-direction: column;
            gap: 100mm;
            /* Espacio de 1 milímetro entre los elementos */
        }
    </style>

</head>

<body class="font-sans antialiased">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center"
                href="https://www.gtcgroup.com.mx/">
                <div class="sidebar-brand-text mx-3">GTConstructions <sup></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            @can('admin.dashboardAdmin')
                <!-- Nav Item - Icono de Administracion -->
                <li class="nav-item @yield('activeAdministracion')">
                    <a class="nav-link" href="{{ route('admin.dashboardAdmin') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dep. Administrativo</span></a>
                </li>
            @endcan

            @can('ventas.dashboardVentas')
                <!-- Nav Item - Icono de Administracion -->
                <li class="nav-item @yield('activeVentas')">
                    <a class="nav-link" href="{{ route('ventas.dashboardVentas') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dep. Ventas</span>
                    </a>
                </li>
            @endcan

            @can('compras.dashboardCompras')
                <!-- Nav Item - Icono de Administracion -->
                <li class="nav-item @yield('activeCompras')">
                    <a class="nav-link" href="{{ route('compras.dashboardCompras') }}">
                        <i class="fas fa-fw fa-shopping-cart"></i>
                        <span>Dep. Compras</span>
                    </a>
                </li>
            @endcan

            @can('finanzas.dashboardFinanzas')
                <!-- Nav Item - Icono de Administracion -->
                <li class="nav-item @yield('activeFinanzas')">
                    <a class="nav-link" href="{{ route('finanzas.dashboardFinanzas') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dep. Finanzas</span>
                    </a>
                </li>
            @endcan

            @can('admin.dashboardAdmin')
                <!-- Nav Item - Icono de Usuarios -->
                <li class="nav-item @yield('activeUsuarios')">
                    <a class="nav-link" href="{{ route('admin.users') }}">
                        <i class="fas fa-fw fa-users"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
            @endcan

            <style>
                .nav-link {
                    transition: background-color 0.3s ease;
                    /* Suaviza la transición del color de fondo */
                }

                .nav-link:hover {
                    background-color: rgba(0, 0, 0, 0.1);
                    /* Cambia el color de fondo aquí */
                }
            </style>

            @can('admin.dashboardAdmin')
                <!-- Nav Item - Icono de Roles -->
                <li class="nav-item @yield('activeRoles')">
                    <a class="nav-link" href="{{ route('admin.roles') }}">
                        <i class="fas fa-fw fa-user-tag"></i>
                        <span>Roles</span>
                    </a>
                </li>
            @endcan
            {{-- Recepcion de cancelacion de proyectos --}}
            @can('admin.dashboardAdmin')
                <!-- Nav Item - Icono de proyectos -->
                <li class="nav-item @yield('activeCancelaciones')">
                    <a class="nav-link" href="{{ route('admin.cancelaciones') }}">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Culminación de Proyectos</span>
                    </a>
                </li>
            @endcan

            @can('compras.collapsed')
                <!-- Nav Item - Pagina colapsada de departamentos-->
                <li class="nav-item @yield('activedesplegablefamilias')">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCompras"
                        aria-expanded="true" aria-controls="collapseCompras">
                        <i class="fas fa-fw fa-building"></i>
                        <span>Compras</span>
                    </a>
                    <div id="collapseCompras" class="collapse @yield('activeCollapseCompras') container-flex2"
                        aria-labelledby="headingPages" data-parent="#accordionSidebar">
                        <div class="bg-primary-dark text-white py-2 collapse-inner rounded">

                            <a class="collapse-item @yield('activeCategorias') text-white @yield('activeFondoPermanente') mb-2"
                                href="{{ route('compras.familias.viewFamilias') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='@yield('activeAforgot')';">
                                Familias
                            </a>
                            <a class="collapse-item @yield('activeProveedores') text-white @yield('activeFondoPermanenteProveedores') mb-2"
                                href="{{ route('compras.proveedores.viewProveedores') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='@yield('activeBackgroundProveedores')';">
                                Proveedores
                            </a>
                            <a class="collapse-item @yield('activeMateriales') text-white @yield('activeFondoPermanenteMateriales') mb-2"
                                href="{{ route('compras.items.viewItems') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='@yield('activeBackgroundMateriales')';">
                                Materiales
                            </a>

                            <div class="collapse-divider"></div>
                        </div>
                    </div>
                </li>
            @endcan

            @can('compras.collapsed')
                <!-- Nav Item - Página colapsada de Cotizaciones -->
                <li class="nav-item @yield('activedesplegablecotizaciones')">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCotizaciones"
                        aria-expanded="false" aria-controls="collapseCotizaciones">
                        <i class="fas fa-fw fa-file-invoice"></i>
                        <span>Cotizaciones</span>
                    </a>
                    <div id="collapseCotizaciones" class="collapse @yield('activeCollapseCotizaciones') container-flex2"
                        aria-labelledby="headingCotizaciones" data-parent="#accordionSidebar">
                        <div class="bg-primary-dark text-white py-2 collapse-inner rounded">
                            <a class="collapse-item @yield('activeCortisaciones') text-white @yield('activeFondoPermanentecotisaciones') mb-2"
                                href="{{ route('compras.cotisaciones.verCotisaciones') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='';">
                                Cotizaciones
                            </a>
                            <a class="collapse-item @yield('activeMisCortisaciones') text-white @yield('activeFondoPermanenteMiscotisaciones') mb-2"
                                href="{{ route('compras.cotisaciones.verMisCotisaciones') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='';">
                                Mis cotizaciones
                            </a>

                            <a class="collapse-item @yield('activeItemsCotizar') text-white @yield('activeFondoPermanenteItemsCotizar') mb-2"
                                href="{{ route('compras.catalogoCotisacion.catalogoItem') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='@yield('activeBackgroundItemsCOtizar')';">
                                Items para cotizar
                            </a>

                            <a class="collapse-item @yield('activeOrdenesCompra') text-white @yield('activeFondoPermanenteOrdenesCompra') mb-2"
                                href="{{ route('compras.cotisaciones.verOrdenesCompra') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='@yield('activeBackgroundOrdenesCompra')';">
                                Ordenes de compra
                            </a>
                            <div class="collapse-divider"></div>
                        </div>
                    </div>
                </li>
            @endcan


            @can('ventas.collapsed')
                <!-- Nav Item - Pagina colapsada de departamentos-->
                <li class="nav-item @yield('activedesplegableVentas')">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVentas"
                        aria-expanded="true" aria-controls="collapseVentas">
                        <i class="fas fa-fw fa-building"></i>
                        <span>Ventas</span>
                    </a>
                    <div id="collapseVentas" class="collapse @yield('activeCollapseVentas') container-flex2"
                        aria-labelledby="headingPages" data-parent="#accordionSidebar">
                        <div class="bg-primary-dark text-white py-2 collapse-inner rounded">

                            <a class="collapse-item @yield('activeGestionClientes') text-white @yield('activeFondoPermanenteGestionCLientes') mb-2"
                                href="{{ route('ventas.clientes.gestionClientes') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='@yield('activeBackgroundGestionClientes')';">
                                Gestion de clientes
                            </a>

                            <a class="collapse-item @yield('activeRecepcion') text-white @yield('activeFondoPermanenteRecepcion') mb-2"
                                href="{{ route('ventas.clientes.recepcionLlamadas') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='@yield('activeBackgroundRecepcion')';">
                                Recepcion de llamadas
                            </a>

                            <a class="collapse-item @yield('activeFichasTecnicas') text-white @yield('activeFondoPermanenteFichasTecnicas') mb-2"
                                href="{{ route('ventas.fichasTecnicas.fichasTecnicas') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='@yield('activeBackgroundFichasTecnicas')';">
                                Fichas tecnicas
                            </a>



                            <a class="collapse-item @yield('activeRecepcionCotizacion') text-white @yield('activeFondoPermanenteRecepcionCotizacion') mb-2"
                                href="{{ route('ventas.recepcionCotizaciones.recepcionCotizacion') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='@yield('activeBackgroundRecepcionCotizacion')';">
                                Recepcion Cotizaciones
                            </a>

                            <a class="collapse-item @yield('activeOrdenesVenta') text-white @yield('activeFondoPermanenteOrdenesVenta') mb-2"
                                href="{{ route('ventas.ordenesVenta.vistaOrdenVenta') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='@yield('activeBackgroundOrdenesVenta')';">
                                Órdenes de venta
                            </a>

                            <div class="collapse-divider"></div>
                        </div>
                    </div>
                </li>
            @endcan

            @can('finanzas.collapsed')
                <!-- Nav Item - Pagina colapsada de departamentos-->
                <li class="nav-item @yield('activedesplegableFinansas')">

                    <a class="nav-link collapsed  " href="#" data-toggle="collapse"
                        data-target="#collapseFinanzas" aria-expanded="true" aria-controls="collapseFinanzas">
                        <i class="fas fa-fw fa-building"></i>
                        <span>Finanzas</span>
                    </a>
                    <div id="collapseFinanzas" class="collapse @yield('activeCollapseFinanzas') container-flex2"
                        aria-labelledby="headingPages" data-parent="#accordionSidebar">
                        <div class="bg-primary-dark text-white py-2 collapse-inner rounded">

                            <a class="collapse-item @yield('activeIngresosEgresos') text-white @yield('activeFondoPermanenteIngresosEgresos2') mb-2"
                                href="{{ route('finanzas.ingresosEgresos.ingresosEgeresosVistaGeneral') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='@yield('activeBackgroundIngresosEgresos')';">
                                Ingresos/Egresos
                            </a>



                            <a class="collapse-item @yield('activeOrdenesCompra2') text-white @yield('activeFondoPermanenteOrdenesCompraa2') mb-2"
                                href="{{ route('finanzas.ordenCompra.vistaOrdenCompraFin') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='@yield('activeBackgroundOrdenesCompra2')';">
                                Órdenes de Compra
                            </a>
                            <a class="collapse-item @yield('activeOrdenesVenta2') text-white @yield('activeFondoPermanenteOrdenesVenta2') mb-2"
                                href="{{ route('finanzas.ordenesVenta.vistaOrdenVentaFin') }}"
                                onmouseover="this.style.backgroundColor='#003366';"
                                onmouseout="this.style.backgroundColor='@yield('activeBackgroundOrdenesVenta2')';">
                                Órdenes de venta
                            </a>

                            <div class="collapse-divider"></div>
                        </div>
                    </div>
                </li>
            @endcan

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar  static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Sección de Notificaciones Generales (Ítems, Proveedores y listas a cotizar) -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <span class="badge badge-danger badge-counter">
                                    {{ Auth::user()->unreadNotifications->whereNotIn('data.type', ['solicitud_cancelacion', 'rechazo_cancelacion', 'confirmacion_cancelacion', 'solicitudCotizacion_notificacion', 'mensaje_general'])->count() }}
                                </span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">Notificaciones</h6>
                                @forelse (Auth::user()->unreadNotifications->whereNotIn('data.type', ['solicitud_cancelacion', 'rechazo_cancelacion', 'confirmacion_cancelacion', 'solicitudCotizacion_notificacion', 'mensaje_general']) as $notification)
                                    @php
                                        $type = $notification->data['type'] ?? 'default';
                                        $bgColor = $notificationStyles[$type][0] ?? 'bg-secondary';
                                        $icon = $notificationStyles[$type][1] ?? 'fas fa-cube';
                                    @endphp
                                    <a class="dropdown-item d-flex align-items-center bg-light text-dark"
                                        href="{{ route('notifications.markAsRead', $notification->id) }}?redirect_to={{ urlencode($notification->data['url']) }}">
                                        <div class="mr-3">
                                            <div class="icon-circle {{ $bgColor }}">
                                                <i class="{{ $icon }} text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="small text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}</div>
                                            <span class="font-weight-bold">{{ $notification->data['message'] }}</span>
                                        </div>
                                    </a>
                                @empty
                                    <p class="dropdown-item">Sin notificaciones.</p>
                                @endforelse
                            </div>
                        </li>

                        <!-- Sección de Mensajes -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <span class="badge badge-danger badge-counter">
                                    {{ Auth::user()->unreadNotifications->whereIn('data.type', ['solicitud_cancelacion', 'rechazo_cancelacion', 'confirmacion_cancelacion', 'solicitudCotizacion_notificacion', 'mensaje_general'])->count() }}
                                </span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">Mensajes</h6>
                                @forelse (Auth::user()->unreadNotifications->whereIn('data.type', ['solicitudCotizacion_notificacion', 'solicitud_cancelacion', 'rechazo_cancelacion', 'confirmacion_cancelacion', 'mensaje_general']) as $notification)
                                    <a class="dropdown-item d-flex align-items-center bg-light text-dark"
                                        href="{{ isset($notification->data['url']) ? route('notifications.markAsRead', $notification->id) . '?redirect_to=' . urlencode($notification->data['url']) : '#' }}">

                                        <div class="mr-3">
                                            <div
                                                class="icon-circle 
                @if ($notification->data['type'] === 'solicitudCotizacion_notificacion') bg-info
                @elseif($notification->data['type'] === 'solicitud_cancelacion') bg-warning
                @elseif($notification->data['type'] === 'rechazo_cancelacion') bg-danger
                @elseif($notification->data['type'] === 'confirmacion_cancelacion') bg-success
                @else bg-primary @endif">
                                                <i
                                                    class="
                    @if ($notification->data['type'] === 'solicitudCotizacion_notificacion') fas fa-file-invoice-dollar
                    @elseif($notification->data['type'] === 'solicitud_cancelacion') fas fa-exclamation-triangle
                    @elseif($notification->data['type'] === 'rechazo_cancelacion') fas fa-times-circle
                    @elseif($notification->data['type'] === 'confirmacion_cancelacion') fas fa-check-circle
                    @else fas fa-envelope @endif text-white"></i>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="small text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}</div>
                                            <span class="font-weight-bold">{{ $notification->data['message'] }}</span>
                                        </div>
                                    </a>
                                @empty
                                    <p class="dropdown-item">No hay mensajes.</p>
                                @endforelse
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            @php
                                $user = Auth::user();
                                $imagePath =
                                    $user->image && $user->image !== 'users/'
                                        ? asset('storage/' . $user->image)
                                        : asset('storage/StockImages/stockUser.png');
                            @endphp

                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $user->name }}</span>
                                <img class="img-profile rounded-circle" src="{{ $imagePath }}">
                            </a>


                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile.profileView') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Mi perfil
                                </a>


                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal"
                                    data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar sesión
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                @yield('contend')
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; GTConstructions 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->


    {{-- <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a> --}}

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Deseas cerrar tu sesion?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Selecciona "Cerrar sesión" para finaliza tu sesión en este dispositivo.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <button class="btn btn-primary" type="submit">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <style>
        .notification-list {
            max-height: 300px;
            /* Ajusta la altura máxima según tus necesidades */
            overflow-y: auto;
        }
    </style>


    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>

    @livewireScripts




</body>

</html>
