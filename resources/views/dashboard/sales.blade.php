@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <!-- Header -->
            <div class="pt-2 pb-3">
                <h4 class="fw-bold mb-1 text-center text-md-start">Dashboard Sales</h4>
                <h6 class="text-muted text-center text-md-start">Hai {{ Auth::user()->name }}, Selamat Datang</h6>
            </div>

            <!-- Filter Tanggal -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label for="from" class="form-label">Dari Tanggal</label>
                                <input type="date" name="from" id="from" class="form-control"
                                    value="{{ request('from', now()->subDays(30)->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-5">
                                <label for="to" class="form-label">Sampai Tanggal</label>
                                <input type="date" name="to" id="to" class="form-control"
                                    value="{{ request('to', now()->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary">Terapkan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Kartu Statistik -->
            <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
                <!-- Total Penjualan -->
                <div class="col">
                    <div class="card card-stats card-round h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="col-icon me-3">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                            <div class="col">
                                <p class="card-category mb-1">
                                    Total Transaksi {{ $days }} Hari Terakhir
                                </p>
                                <h4 class="card-title">{{ $totalPenjualans }} Transaksi</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Omset -->
                @isset($omset)
                    <div class="col">
                        <div class="card card-stats card-round h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="col-icon me-3">
                                    <div class="icon-big text-center icon-warning bubble-shadow-small">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <p class="card-category mb-1">
                                        Omset {{ $days }} Hari Terakhir
                                    </p>
                                    <h4 class="card-title">Rp {{ number_format($omset, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @endisset
            </div>

            <!-- Informasi -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Info Penting</h5>
                    <p class="mb-1">
                        Anda adalah pengguna dengan role <strong>Sales</strong>. Anda dapat melihat data barang dan memantau
                        total transaksi serta omset pribadi.
                    </p>
                    <p class="mb-0">
                        Jika terdapat kesalahan data atau butuh bantuan, silakan hubungi admin atau manajer.
                    </p>
                </div>
            </div>

            <!-- Tips -->
            <div class="alert alert-info mt-2 mb-4" role="alert">
                <i class="fas fa-lightbulb"></i> Pastikan Anda selalu mengecek informasi barang sebelum menawarkan barang kepada pelanggan.
            </div>

        </div>
    @endsection
