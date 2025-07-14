@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h4>Detail Pembelian - {{ $pembelian->kode_transaksi }}</h4>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Supplier:</strong><br>
                            {{ $pembelian->supplier->nama ?? '-' }}
                        </div>
                        <div class="col-md-4">
                            <strong>User Input:</strong><br>
                            {{ $pembelian->user->name ?? '-' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Tanggal:</strong><br>
                            {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d-m-Y') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Status Transaksi:</strong><br>
                            @php
                                $warnaStatus = [
                                    'selesai' => 'success',
                                    'batal' => 'danger',
                                    'diproses' => 'warning',
                                    'draft' => 'secondary',
                                ];
                            @endphp
                            <span class="badge bg-{{ $warnaStatus[$pembelian->status_transaksi] ?? 'dark' }}">
                                {{ ucfirst($pembelian->status_transaksi) }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <strong>Total Harga:</strong><br>
                            Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                        </div>
                    </div>

                    <hr>

                    <h5>Daftar Barang</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga Beli</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembelian->detailPembelian as $detail)
                                    <tr>
                                        <td>{{ $detail->barang->nama }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>Rp {{ number_format($detail->harga_beli_snapshot, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format(($detail->harga_beli_snapshot*$detail->jumlah ), 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
