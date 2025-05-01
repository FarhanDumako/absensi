<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard.index') }}">Aplikasi Absensi</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard.index') }}">AA</a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown {{ Request::is('dashboard*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-fire"></i>
                    <span>Dashboard</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dashboard.index') }}">Dashboard Utama</a>
                    </li>
                </ul>
            </li>

            @role('admin')
            <li class="menu-header">Manajemen User</li>
            <li class="nav-item dropdown {{ Request::is('user*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-users"></i>
                    <span>Pengguna</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('user') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('user.index') }}">Daftar Pengguna</a>
                    </li>
                    <li class="{{ Request::is('user/create') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('user.create') }}">Tambah Pengguna</a>
                    </li>
                </ul>
            </li>

            <li class="menu-header">Manajemen Absen</li>
            <li class="nav-item dropdown {{ Request::is('absen*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-user-check"></i>
                    <span>Absen</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('absen') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('absens.index') }}">Daftar Absen</a>
                    </li>
                    <li class="{{ Request::is('laporan/absensi*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('laporan.absensi.index') }}">Laporan Absensi</a>
                    </li>
                </ul>
            </li>

            <li class="menu-header">Manajemen Pengajuan</li>
            <li class="nav-item dropdown {{ Request::is('pengajuan*') || Request::is('laporan*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-file-alt"></i>
                    <span>Pengajuan</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('pengajuan') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('pengajuans.index') }}">Daftar Pengajuan</a>
                    </li>
                    <li class="{{ Request::is('laporan') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('laporan.index') }}">Laporan Pengajuan</a>
                    </li>
                   
                </ul>
            </li>
            @endrole


            <li class="menu-header">QR Code</li>
            <li class="nav-item {{ Request::is('qrcode*') ? 'active' : '' }}">
                <a href="{{ route('qrcode') }}" class="nav-link">
                    <i class="fas fa-qrcode"></i>
                    <span>Generate QR Code</span>
                </a>
            </li>
        </ul>

        <div class="hide-sidebar-mini mt-4 mb-4 p-3">
            <a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Dokumentasi
            </a>
        </div>
    </aside>
</div>
