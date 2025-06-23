@php
    $role = Auth::user()->role;
    $dashboardRoute = route('dashboard');
@endphp

<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ $dashboardRoute }}" class="logo">
                <img src="{{ asset('assets/img/logo-perusahaan-text.png') }}" alt="navbar brand" class="navbar-brand"
                    height="90" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <!-- DASHBOARD -->
                <li class="nav-item active">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- MASTER DATA -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Master Data</h4>
                </li>

                <!-- BARANG DAN KATEGORI (Semua Role) -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#barang">
                        <i class="fas fa-boxes"></i>
                        <p>Barang</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="barang">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('barang.index') }}"><span class="sub-item">Data Barang</span></a></li>
                            @if (Auth::user()->role !== 'sales')
                                <li><a href="{{ route('barang.create') }}"><span class="sub-item">Tambah Data
                                            Barang</span></a></li>
                                <li><a href="{{ route('kategori.index') }}"><span class="sub-item">Data
                                            Kategori</span></a>
                                </li>
                                <li><a href="{{ route('kategori.create') }}"><span class="sub-item">Tambah Data
                                            Kategori</span></a></li>
                            @endif
                        </ul>
                    </div>
                </li>

                <!-- PELANGGAN (ADMIN + SUPERADMIN ) -->
                @if (in_array($role, ['admin', 'superadmin']))
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#pelanggan">
                            <i class="fas fa-user-friends"></i>
                            <p>Pelanggan</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="pelanggan">
                            <ul class="nav nav-collapse">
                                <li><a href="{{ route('pelanggan.index') }}"><span class="sub-item">Data
                                            Pelanggan</span></a></li>
                                <li><a href="{{ route('pelanggan.create') }}"><span class="sub-item">Tambah Data
                                            Pelanggan</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- SUPPLIER (ADMIN + SUPERADMIN) -->
                @if (in_array($role, ['admin', 'superadmin']))
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#supplier">
                            <i class="fas fa-dolly-flatbed"></i>
                            <p>Supplier</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="supplier">
                            <ul class="nav nav-collapse">
                                <li><a href="{{ route('supplier.index') }}"><span class="sub-item">Data
                                            Supplier</span></a></li>
                                <li><a href="{{ route('supplier.create') }}"><span class="sub-item">Tambah Data
                                            Supplier</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- TRANSAKSI -->
                @if (in_array($role, ['admin', 'superadmin']))
                    <li class="nav-section">
                        <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                        <h4 class="text-section">Transaksi</h4>
                    </li>

                    <!-- PENJUALAN -->
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#penjualan">
                            <i class="fas fa-file-invoice"></i>
                            <p>Penjualan</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="penjualan">
                            <ul class="nav nav-collapse">
                                <li><a href="{{ route('penjualan.index') }}"><span class="sub-item">Riwayat Transaksi
                                            Penjualan</span></a></li>
                                <li><a href="{{ route('penjualan.create') }}"><span class="sub-item">Tambah Nota
                                            Penjualan</span></a></li>
                                <li><a href="{{ route('pembayaran_penjualan.index') }}"><span class="sub-item">Riwayat
                                            Pembayaran Penjualan</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- PEMBELIAN (ADMIN + SUPERADMIN) -->
                @if (in_array($role, ['admin', 'superadmin']))
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#pembelian">
                            <i class="fas fa-cart-arrow-down"></i>
                            <p>Pembelian</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="pembelian">
                            <ul class="nav nav-collapse">
                                <li><a href="{{ route('pembelian.index') }}"><span class="sub-item">Riwayat Transaksi
                                            Pembelian</span></a></li>
                                <li><a href="{{ route('pembelian.create') }}"><span class="sub-item">Tambah Nota
                                            Pembelian</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- LAPORAN (Hanya SUPERADMIN) -->
                @if ($role === 'superadmin')
                    <li class="nav-section">
                        <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                        <h4 class="text-section">Laporan</h4>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('laporan.penjualan.index') }}">
                            <i class="fas fa-chart-line"></i>
                            <p>Laporan Penjualan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('laporan.pembelian.index') }}">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <p>Laporan Pembelian</p>
                        </a>
                    </li>
                @endif

                <!-- PENGATURAN (SUPERADMIN SAJA) -->
                @if ($role === 'superadmin')
                    <li class="nav-section">
                        <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                        <h4 class="text-section">Pengaturan</h4>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user.index') }}">
                            <i class="fas fa-users-cog"></i>
                            <p>Manajemen Akun</p>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
