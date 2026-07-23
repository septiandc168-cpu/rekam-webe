<aside class="main-sidebar sidebar-dark-secondary elevation-4" style="background-color: #001f3f !important;">
    <!-- Brand Logo -->
    <div class="brand-link text-center">
        <!-- <img src="{{ asset('public/adminlte/dist/img/logo_webe.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8"> -->
        <span class="brand-text font-weight-light-active"><b>Rekam WeBe</b></span>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" style="background-color: #001f3f !important;">
        <!-- Sidebar user panel (optional) -->
        {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div> --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2" style="font-size: 0.85rem;">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                {{-- <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Starter Pages
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Active Page</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inactive Page</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @if (Auth::user()->role_id == 1)
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}"
                            class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Pengguna
                            </p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('rencana_kegiatan.index') }}"
                        class="nav-link {{ request()->routeIs('rencana_kegiatan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            Rencana Kegiatan
                        </p>
                    </a>
                </li>
                @if (in_array(Auth::user()->role->role_name, ['anggota', 'admin']))
                    <li class="nav-item">
                        <a href="{{ route('laporan_kegiatan.index') }}"
                            class="nav-link {{ request()->routeIs('laporan_kegiatan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>
                                Laporan Kegiatan
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<style>
    /* Custom styles untuk nav-link active tanpa background */
    .sidebar-dark-secondary .nav-sidebar .nav-link.active {
        background-color: transparent !important;
        color: #ffffff !important;
        box-shadow: none !important;
        border: none !important;
        outline: none !important;
    }

    .sidebar-dark-secondary .nav-sidebar .nav-link.active i {
        color: #ffffff !important;
    }

    .sidebar-dark-secondary .nav-sidebar .nav-link.active p {
        color: #ffffff !important;
    }

    /* Default state - lebih redup */
    .sidebar-dark-secondary .nav-sidebar .nav-link {
        background-color: transparent !important;
        color: rgba(255, 255, 255, 0.6) !important;
        box-shadow: none !important;
        border: none !important;
        outline: none !important;
    }

    .sidebar-dark-secondary .nav-sidebar .nav-link i {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    .sidebar-dark-secondary .nav-sidebar .nav-link p {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    /* Hover state - tanpa background sama sekali */
    .sidebar-dark-secondary .nav-sidebar .nav-link:hover,
    .sidebar-dark-secondary .nav-sidebar > .nav-item > .nav-link:hover,
    .sidebar-dark-secondary .nav-sidebar .nav-link:focus,
    .sidebar-dark-secondary .nav-sidebar > .nav-item > .nav-link:focus {
        background-color: transparent !important;
        background: transparent !important;
        color: #ffffff !important;
        box-shadow: none !important;
        border: none !important;
        outline: none !important;
        border-left: none !important;
        border-right: none !important;
        border-top: none !important;
        border-bottom: none !important;
    }

    .sidebar-dark-secondary .nav-sidebar .nav-link:hover i,
    .sidebar-dark-secondary .nav-sidebar > .nav-item > .nav-link:hover i,
    .sidebar-dark-secondary .nav-sidebar .nav-link:focus i,
    .sidebar-dark-secondary .nav-sidebar > .nav-item > .nav-link:focus i {
        color: #ffffff !important;
    }

    .sidebar-dark-secondary .nav-sidebar .nav-link:hover p,
    .sidebar-dark-secondary .nav-sidebar > .nav-item > .nav-link:hover p,
    .sidebar-dark-secondary .nav-sidebar .nav-link:focus p,
    .sidebar-dark-secondary .nav-sidebar > .nav-item > .nav-link:focus p {
        color: #ffffff !important;
    }

    /* Override AdminLTE specific classes */
    .sidebar-dark-secondary .nav-sidebar > .nav-item > .nav-link:hover,
    .sidebar-dark-secondary .nav-sidebar > .nav-item > .nav-link:focus {
        background-color: transparent !important;
        background: transparent !important;
    }

    /* Remove any AdminLTE hover effects */
    .nav-sidebar .nav-link:hover,
    .nav-sidebar .nav-link:focus {
        background-color: transparent !important;
        background: transparent !important;
    }
</style>
