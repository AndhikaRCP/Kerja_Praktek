<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembelian</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h3>Laporan Pembelian</h3>
    <p>Periode: {{ request('tanggal_mulai') ?? '-' }} s/d {{ request('tanggal_akhir') ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>User</th>
                <th>Total</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembelians as $i => $pembelian)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $pembelian->kode_transaksi }}</td>
                    <td>{{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $pembelian->supplier->nama }}</td>
                    <td>{{ $pembelian->user->name }}</td>
                    <td>Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($pembelian->status_transaksi) }}</td>
                    <td>{{ $pembelian->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 style="text-align:right;">Total Pembelian: Rp {{ number_format($total_pembelian, 0, ',', '.') }}</h4>
</body>
</html>
