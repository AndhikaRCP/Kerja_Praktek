@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard Sales</h3>
                <h6 class="op-7 mb-2">Selamat datang, {{ Auth::user()->name }}! Semangat jualannya ya 😸</h6>
            </div>
        </div>

        <div class="row">
            <!-- Kartu Total Penjualan -->
            <div class="col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Penjualan Anda</p>
                                    <h4 class="card-title">{{ $totalPenjualans }} Transaksi</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Informasi -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5>Info</h5>
                        <p>
                            Anda adalah pengguna dengan role <strong>Sales</strong>.
                            Fokus Anda adalah mencatat dan memantau penjualan kepada pelanggan.
                            Untuk mulai menjual, silakan masuk ke menu <strong>Penjualan</strong> di sidebar.
                        </p>
                        <p>
                            Jika ada kendala teknis, silakan hubungi admin atau manajer.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="row mt-3">
            <div class="col">
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-lightbulb"></i> Tip: Selalu pastikan data pelanggan sudah benar sebelum membuat nota penjualan.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
