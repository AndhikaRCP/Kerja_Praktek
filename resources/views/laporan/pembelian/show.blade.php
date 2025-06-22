@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-10 offset-md-1">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Detail Pembelian - {{ $pembelian->kode_transaksi }}</h4>
                        <p class="mb-1">Tanggal:
                            <strong>{{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d-m-Y') }}</strong>
                        </p>
                        <p class="mb-1">Supplier: <strong>{{ $pembelian->supplier->nama }}</strong></p>
                        <p class="mb-1">User Input: <strong>{{ $pembelian->user->name }}</strong></p>
                        <p class="mb-0">Status Transaksi:
                            <span
                                class="badge bg-{{ $pembelian->status_transaksi == 'selesai' ? 'success' : 'secondary' }}">
                                {{ ucfirst($pembelian->status_transaksi) }}
                            </span>
                        </p>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-3">Barang yang Dibeli:</h5>

                        <!-- Tambahan table-responsive agar tidak overflow -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Satuan</th>
                                        <th>Harga Beli</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembelian->detailPembelian as $i => $detail)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $detail->barang_kode }}</td>
                                            <td>{{ $detail->nama_barang_snapshot }}</td>
                                            <td>{{ $detail->barang->kategori->nama_kategori ?? '-' }}</td>
                                            <td>{{ $detail->barang->satuan ?? '-' }}</td>
                                            <td>Rp {{ number_format($detail->harga_beli_snapshot, 0, ',', '.') }}</td>
                                            <td>{{ $detail->jumlah }}</td>
                                            <td>Rp
                                                {{ number_format($detail->harga_beli_snapshot * $detail->jumlah, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Total -->
                        <div class="text-end mt-3">
                            <h5>Total Pembelian:
                                <span class="badge bg-primary">
                                    Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                                </span>
                            </h5>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('laporan.pembelian.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
