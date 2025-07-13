@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex justify-content-between align-items-center pt-2 pb-3">
                <div>
                    <h3 class="fw-bold mb-1">Dashboard Superadmin</h3>
                    <p class="text-muted mb-0">Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Berikut ringkasan
                        data operasional PD Karya Citra Mandiri</p>
                </div>
            </div>

            {{-- Ringkasan Statistik --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3">
                                    <div class="numbers">
                                        <p class="card-category">Total User</p>
                                        <h4 class="card-title">{{ $totalUsers }}</h4>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="fas fa-boxes"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3">
                                    <div class="numbers">
                                        <p class="card-category">Total Barang</p>
                                        <h4 class="card-title">{{ $totalBarangs }}</h4>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3">
                                    <div class="numbers">
                                        <p class="card-category">Total Penjualan</p>
                                        <h4 class="card-title">{{ $totalPenjualans }}</h4>
                                        <small class="text-muted">7 Hari Terakhir</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="fas fa-truck-loading"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3">
                                    <div class="numbers">
                                        <p class="card-category">Total Pembelian</p>
                                        <h4 class="card-title">{{ $totalPembelians }}</h4>
                                        <small class="text-muted">7 Hari Terakhir</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Statistik Lanjutan --}}
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Grafik Penjualan (7 Hari Terakhir)</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Sidebar Statistik --}}
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="card-title">Top 5 Barang Terlaris</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @forelse($topBarangs as $barang)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $barang->nama_barang_snapshot }}
                                        <span class="badge bg-primary rounded-pill">{{ $barang->jumlah }}x</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-muted">Belum ada data.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Distribusi Pengguna</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @php $total = max($totalUsers, 1); @endphp
                                <li class="list-group-item">
                                    Manager
                                    <span class="float-end">{{ $userPerRole['manager'] ?? 0 }}
                                        ({{ round((($userPerRole['manager'] ?? 0) / $total) * 100) }}%)</span>
                                </li>
                                <li class="list-group-item">
                                    Admin
                                    <span class="float-end">{{ $userPerRole['admin'] ?? 0 }}
                                        ({{ round((($userPerRole['admin'] ?? 0) / $total) * 100) }}%)</span>
                                </li>
                                <li class="list-group-item">
                                    Sales
                                    <span class="float-end">{{ $userPerRole['sales'] ?? 0 }}
                                        ({{ round((($userPerRole['sales'] ?? 0) / $total) * 100) }}%)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($penjualanChart, 'tanggal')) !!},
                datasets: [{
                    label: 'Total Penjualan',
                    data: {!! json_encode(array_column($penjualanChart, 'total')) !!},
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });
    </script>
@endpush
