<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h3>Laporan Penjualan</h3>
    <p>Periode: {{ $tanggal_mulai ?? '-' }} s/d {{ $tanggal_akhir ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Sales</th>
                <th>User</th>
                <th>Total</th>
                <th>Jenis Pembayaran</th>
                <th>Status Transaksi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualans as $i => $penjualan)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $penjualan->kode_transaksi }}</td>
                    <td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $penjualan->pelanggan->nama ?? '-' }}</td>
                    <td>{{ $penjualan->sales->name ?? '-' }}</td>
                    <td>{{ $penjualan->user->name ?? '-' }}</td>
                    <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($penjualan->jenis_pembayaran) }}</td>
                    <td>{{ ucfirst($penjualan->status_transaksi) }}</td>
                    <td>{{ $penjualan->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 style="text-align:right;">Total Penjualan: Rp {{ number_format($total_penjualan, 0, ',', '.') }}</h4>
</body>
</html>
