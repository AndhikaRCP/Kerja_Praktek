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
                        <div class="col-12">
                            <div class="d-flex flex-wrap justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-filter"></i> Terapkan Filter
                                </button>

                                <a href="{{ route('laporan.pembelian.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-sync-alt"></i> Reset
                                </a>

                                <a href="{{ route('laporan.pembelian.export.pdf', request()->all()) }}" target="_blank"
                                    class="btn btn-danger">
                                    <i class="fa fa-file-pdf"></i> Export PDF
                                </a>
                            </div>
                        </div>

                    </form>


                    <!-- Tabel -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-nowrap">No</th>
                                    <th class="text-nowrap">Kode Transaksi</th>
                                    <th class="text-nowrap">Tanggal</th>
                                    <th class="text-nowrap">Supplier</th>
                                    <th class="text-nowrap">User Input</th>
                                    <th class="text-nowrap">Total Harga</th>
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">Keterangan</th>
                                    <th class="text-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pembelians as $index => $pembelian)
                                    <tr>
                                        <td class="text-nowrap">{{ $index + $pembelians->firstItem() }}</td>
                                        <td class="text-nowrap">{{ $pembelian->kode_transaksi }}</td>
                                        <td class="text-nowrap">
                                            {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d-m-Y') }}</td>
                                        <td class="text-nowrap">{{ $pembelian->supplier->nama ?? '-' }}</td>
                                        <td class="text-nowrap">{{ $pembelian->user->name ?? '-' }}</td>
                                        <td class="text-nowrap">Rp
                                            {{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
                                        <td class="text-nowrap">
                                            <span
                                                class="badge bg-{{ $pembelian->status_transaksi === 'selesai' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($pembelian->status_transaksi) }}
                                            </span>
                                        </td>
                                        <td class="text-nowrap">{{ $pembelian->keterangan ?? '-' }}</td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('laporan.pembelian.show', $pembelian->id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i> Detail
                                            </a>
                                            <a href="{{ route('laporan.pembelian.export.detail.pdf', $pembelian->id) }}"
                                                target="_blank" class="btn btn-sm btn-danger">
                                                <i class="fa fa-file-pdf"></i> PDF
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
