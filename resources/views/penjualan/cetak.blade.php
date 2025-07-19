@extends('layouts.cetak')

@section('content')

    {{-- Kop Surat --}}
    <div style="text-align: center; line-height: 1.5;">
        <h1>PD Karya Citra Mandiri</h1>
        <p>
            Jalan Pangeran Ayin, Lorong Krawo, No. 25<br>
            Banyuasin, Sumatera Selatan 30961<br>
            Telp : 0711-816675
        </p>
    </div>

    {{-- Garis Pembatas --}}
    <hr>

    {{-- Judul Nota --}}
    <h3 class="text-center" style="margin-bottom: 15px;">Nota Transaksi Penjualan</h3>

    {{-- Info Transaksi --}}
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</p>
    <p><strong>Pelanggan:</strong> {{ $penjualan->pelanggan->nama ?? '-' }}</p>
    <p><strong>Sales:</strong> {{ $penjualan->sales->name ?? '-' }}</p>

    <br>

    {{-- Tabel Penjualan --}}
    <table>
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
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item->barang->nama ?? '-' }}</td>
                    <td class="text-end">Rp {{ number_format($item->harga_jual_snapshot, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td class="text-end">Rp {{ number_format($item->harga_jual_snapshot * $item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <p><strong>Total Harga:</strong> Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</p>

@endsection
