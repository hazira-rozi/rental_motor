<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-motorcycle"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Rental Motor</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ Request::is('admin/dashboard', 'user/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('user.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    @if(Auth::user()->role == 'admin')
        <div class="sidebar-heading">
            Manajemen Data
        </div>

        <li class="nav-item {{ Request::is('admin/motor*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('motor.index') }}">
                <i class="fas fa-fw fa-bicycle"></i>
                <span>Data Motor</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('admin/users*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Data Pelanggan</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Transaksi
        </div>

        <li class="nav-item {{ Request::is('admin/rentals/') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.rentals') }}">
                <i class="fas fa-fw fa-motorcycle"></i>
                <span>Data Peminjaman</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/rentals/create') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.rentals.create') }}">
                <i class="fas fa-fw fa-plus-circle"></i>
                <span>Sewa Manual</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('admin/laporan*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.rentals.laporan') }}">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Laporan Transaksi</span>
            </a>
        </li>

    @else
        <div class="sidebar-heading">
            Aktivitas Saya
        </div>

        <li class="nav-item {{ Request::is('user/dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.dashboard') }}">
                <i class="fas fa-fw fa-motorcycle"></i>
                <span>Cari Motor</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('user/riwayat*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.history') }}">
                <i class="fas fa-fw fa-history"></i>
                <span>Riwayat Sewa</span>
            </a>
        </li>

        {{-- <li class="nav-item {{ Request::is('user/profil*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.profil') }}">
                <i class="fas fa-fw fa-user-cog"></i>
                <span>Profil Saya</span>
            </a>
        </li> --}}
    @endif

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>