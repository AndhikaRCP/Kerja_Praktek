@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Laporan Penjualan</h4>
                </div>

                <div class="card-body">
                    <!-- Filter -->
                    <form method="GET" action="{{ route('laporan.penjualan.index') }}" class="row g-3 mb-4">
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
                            <label>Pelanggan</label>
                            <select name="pelanggan_id" class="form-control">
                                <option value="">-- Semua Pelanggan --</option>
                                @foreach ($pelanggans as $pelanggan)
                                    <option value="{{ $pelanggan->id }}"
                                        {{ request('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                                        {{ $pelanggan->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Sales</label>
                            <select name="sales_id" class="form-control">
                                <option value="">-- Semua Sales --</option>
                                @foreach ($salesList as $sales)
                                    <option value="{{ $sales->id }}"
                                        {{ request('sales_id') == $sales->id ? 'selected' : '' }}>
                                        {{ $sales->name }}
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

                                <a href="{{ route('laporan.penjualan.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-sync-alt"></i> Reset
                                </a>

                                <a href="{{ route('laporan.penjualan.export.pdf', request()->all()) }}" target="_blank"
                                    class="btn btn-danger">
                                    <i class="fa fa-file-pdf"></i> Export PDF
                                </a>

                                <a href="{{ route('laporan.penjualan.export.excel', request()->all()) }}" target="_blank"
                                    class="btn btn-success">
                                    <i class="fa fa-file-excel"></i> Export Excel
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Tabel -->
                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover nowrap" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Sales</th>
                                    <th>User Input</th>
                                    <th>Total Harga</th>
                                    <th>Jenis Pembayaran</th>
                                    <th>Status Transaksi</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penjualans as $index => $penjualan)
                                    <tr>
                                        <td style="white-space: nowrap;">{{ $loop->iteration }}</td>
                                        <td>{{ $penjualan->kode_transaksi }}</td>
                                        <td data-order="{{ $penjualan->tanggal }}">
                                            {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td>
                                        <td>{{ $penjualan->pelanggan->nama ?? '-' }}</td>
                                        <td>{{ $penjualan->sales->name ?? '-' }}</td>
                                        <td>{{ $penjualan->user->name ?? '-' }}</td>
                                        <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                                        <td>{{ ucfirst($penjualan->jenis_pembayaran) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $penjualan->status_transaksi == 'lunas' ? 'success' : 'danger' }}">
                                                {{ ucfirst($penjualan->status_transaksi) }}
                                            </span>
                                        </td>
                                        <td>{{ $penjualan->keterangan ?? '-' }}</td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('laporan.penjualan.show', $penjualan->id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i> Detail
                                            </a>
                                            <a href="{{ route('laporan.penjualan.export.detail.pdf', $penjualan->id) }}"
                                                target="_blank" class="btn btn-sm btn-danger">
                                                <i class="fa fa-file-pdf"></i> PDF
                                            </a>
                                            <a href="{{ route('laporan.penjualan.export.detail.excel', $penjualan->id) }}"
                                                class="btn btn-sm btn-success">
                                                <i class="fa fa-file-excel"></i> Excel
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data penjualan yang ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Total -->
                    <div class="mt-4 text-end">
                        <h5>
                            Total Nilai Penjualan:
                            <span class="badge bg-primary">
                                Rp {{ number_format($total_penjualan, 0, ',', '.') }}
                            </span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    @include('layouts.components.scripts.sweetalerts')
    @include('layouts.components.scripts.datatables')
@endpush
