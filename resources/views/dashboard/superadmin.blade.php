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
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 mb-4">
                <div class="col">
                    <div class="card card-stats card-round h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="col-icon me-3">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats">
                                <div class="numbers">
                                    <p class="card-category mb-1">Total User</p>
                                    <h4 class="card-title mb-0">{{ $totalUsers }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-stats card-round h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="col-icon me-3">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-boxes"></i>
                                </div>
                            </div>
                            <div class="col col-stats">
                                <div class="numbers">
                                    <p class="card-category mb-1">Total Barang</p>
                                    <h4 class="card-title mb-0">{{ $totalBarangs }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-stats card-round h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="col-icon me-3">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                            <div class="col col-stats">
                                <div class="numbers">
                                    <p class="card-category mb-1">Total Penjualan</p>
                                    <h4 class="card-title mb-0">{{ $totalPenjualans }}</h4>
                                    <small class="text-muted">7 Hari Terakhir</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-stats card-round h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="col-icon me-3">
                                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="fas fa-truck-loading"></i>
                                </div>
                            </div>
                            <div class="col col-stats">
                                <div class="numbers">
                                    <p class="card-category mb-1">Total Pembelian</p>
                                    <h4 class="card-title mb-0">{{ $totalPembelians }}</h4>
                                    <small class="text-muted">7 Hari Terakhir</small>
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
                            <h4 class="card-title">Grafik Penjualan (30 Hari Terakhir)</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart" style="min-height: 350px;"></canvas>
                        </div>
                    </div>
                </div>
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
                        <div class="card-header py-1 px-2">
                            <h6 class="card-title mb-0" style="font-size: 0.8rem;">Distribusi Pengguna</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0" style="font-size: 0.65rem;">
                                    <thead>
                                        <tr>
                                            <th class="py-1 px-1">Role</th>
                                            <th class="py-1 px-1 text-end">Jumlah</th>
                                            <th class="py-1 px-1 text-end">%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $total = max($totalUsers, 1); @endphp
                                        @foreach (['superadmin', 'admin', 'sales'] as $role)
                                            @php
                                                $count = $userPerRole[$role] ?? 0;
                                                $percentage = round(($count / $total) * 100);
                                            @endphp
                                            <tr>
                                                <td class="py-1 px-1 text-capitalize">{{ $role }}</td>
                                                <td class="py-1 px-1 text-end">{{ $count }}</td>
                                                <td class="py-1 px-1 text-end">{{ $percentage }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card.card-stats.card-round {
            padding: 0.25rem 0.75rem;
            min-height: auto;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            background-color: #fff;
        }

        .card.card-stats .card-body {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem !important;
        }

        .col-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            background: rgba(0, 0, 0, 0.04);
            flex-shrink: 0;
        }

        .icon-big i {
            font-size: 1.2rem;
            color: #495057;
        }

        .card-category {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .card .text-muted {
            font-size: 0.7rem;
        }

        .card .card-header {
            padding: 0.7rem 1rem;
            background-color: #f9f9f9;
            border-bottom: 1px solid #eee;
        }

        .card .card-body {
            padding: 1rem;
        }

        .list-group-item {
            padding: 0.5rem 0.75rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($penjualanChart, 'tanggal')) !!},
                datasets: [{
                    label: 'Total Omset Penjualan',
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
