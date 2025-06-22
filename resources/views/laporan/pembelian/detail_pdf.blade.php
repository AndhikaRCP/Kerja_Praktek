<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Pembelian</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Detail Pembelian - {{ $pembelian->kode_transaksi }}</h2>
    <p>Tanggal: {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d-m-Y') }}</p>
    <p>Supplier: {{ $pembelian->supplier->nama }}</p>
    <p>User Input: {{ $pembelian->user->name }}</p>
    <p>Status: {{ ucfirst($pembelian->status_transaksi) }}</p>
    <p>Keterangan: {{ $pembelian->keterangan ?? '-' }}</p>

    <h4>Barang yang Dibeli:</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama</th>
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
                    <td>Rp {{ number_format($detail->harga_beli_snapshot * $detail->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 style="text-align: right;">Total: Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</h4>
</body>
</html>
