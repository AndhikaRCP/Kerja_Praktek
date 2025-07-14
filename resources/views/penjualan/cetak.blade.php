@extends('layouts.cetak')

@section('content')
    <h3>Nota Transaksi</h3>
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</p>
    <p><strong>Pelanggan:</strong> {{ $penjualan->pelanggan->nama ?? '-' }}</p>
    <p><strong>Sales:</strong> {{ $penjualan->sales->name ?? '-' }}</p>
    <hr>

    <table border="1" cellspacing="0" cellpadding="5" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Harga Jual</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan->detailPenjualan as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->barang->nama ?? '-' }}</td>
                    <td class="text-end">Rp {{ number_format($item->harga_jual_snapshot, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td class="text-end">Rp {{ number_format($item->harga_jual_snapshot * $item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>
    <p><strong>Total Harga:</strong> Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</p>
@endsection
