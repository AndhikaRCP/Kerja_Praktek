@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Laporan Pembelian</h4>
                </div>

                <div class="card-body">
                    <!-- Filter -->
                    <form method="GET" action="{{ route('laporan.pembelian.index') }}" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control"
                                value="{{ request('tanggal_mulai') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" class="form-control"
                                value="{{ request('tanggal_akhir') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Supplier</label>
                            <select name="supplier_id" class="form-control">
                                <option value="">-- Semua Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Status Transaksi</label>
                            <select name="status_transaksi" class="form-control">
                                <option value="">-- Semua Status --</option>
                                <option value="selesai" {{ request('status_transaksi') == 'selesai' ? 'selected' : '' }}>
                                    Selesai</option>
                                <option value="batal" {{ request('status_transaksi') == 'batal' ? 'selected' : '' }}>Batal
                                </option>
                            </select>
                        </div>
                        <div class="col-md-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2">Terapkan Filter</button>
                            <a href="{{ route('laporan.pembelian.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>

                    <!-- Tabel -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Supplier</th>
                                    <th>User Input</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pembelians as $index => $pembelian)
                                    <tr>
                                        <td>{{ $index + $pembelians->firstItem() }}</td>
                                        <td>{{ $pembelian->kode_transaksi }}</td>
                                        <td>{{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d-m-Y') }}</td>
                                        <td>{{ $pembelian->supplier->nama ?? '-' }}</td>
                                        <td>{{ $pembelian->user->name ?? '-' }}</td>
                                        <td>Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $pembelian->status_transaksi === 'selesai' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($pembelian->status_transaksi) }}
                                            </span>
                                        </td>
                                        <td>{{ $pembelian->keterangan ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('laporan.pembelian.show', $pembelian->id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i> Detail
                                            </a>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data pembelian yang ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Total -->
                    <div class="mt-4 text-end">
                        <h5>
                            Total Nilai Pembelian:
                            <span class="badge bg-primary">
                                Rp {{ number_format($total_pembelian, 0, ',', '.') }}
                            </span>
                        </h5>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $pembelians->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
