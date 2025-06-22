@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4>Form Transaksi Pembelian</h4>
                </div>
                <form action="{{ route('pembelian.store') }}" method="POST">
                    @csrf
                    <div class="card-body">

                        <!-- Supplier & Tanggal -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Supplier</label>
                                <select name="supplier_id" class="form-select" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control"
                                    value="{{ now()->toDateString() }}" required>
                            </div>
                        </div>

                        <!-- Tabel Barang -->
                        <div class="mb-3">
                            <h5>Daftar Barang</h5>
                            <table class="table table-bordered" id="barangTable">
                                <thead>
                                    <tr>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Harga Beli</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="tambahBaris()">+ Tambah
                                Barang</button>
                        </div>

                        <!-- Keterangan & Total -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label>Total Harga</label>
                                <input type="text" id="totalHarga" class="form-control text-end" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Barang Data & Script -->
    <script>
        let barangs = @json($barangs); // data dari controller

        function tambahBaris() {
            let options = barangs.map(b => `<option value="${b.kode_barang}">${b.kode_barang}</option>`).join('');
            let row = `
            <tr>
                <td>
                    <select name="barang_kode[]" class="form-select" onchange="updateBarang(this)">
                        <option value="">--Pilih--</option>
                        ${options}
                    </select>
                </td>
                <td><input type="text" name="nama_barang_snapshot[]" class="form-control" readonly></td>
                <td><input type="number" name="harga_beli_snapshot[]" class="form-control text-end" readonly></td>
                <td><input type="number" name="jumlah[]" class="form-control text-end" value="1" onchange="hitungTotal(this)"></td>
                <td><input type="text" class="form-control text-end" readonly></td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="hapusBaris(this)">X</button></td>
            </tr>
        `;
            document.querySelector('#barangTable tbody').insertAdjacentHTML('beforeend', row);
        }

        function updateBarang(select) {
            const kode = select.value;
            const barang = barangs.find(b => b.kode_barang === kode);
            const row = select.closest('tr');

            if (barang) {
                row.querySelector('[name="nama_barang_snapshot[]"]').value = barang.nama;
                row.querySelector('[name="harga_beli_snapshot[]"]').value = barang.harga_beli;
                hitungTotal(row.querySelector('[name="jumlah[]"]'));
            }
        }

        function hitungTotal(input) {
            const row = input.closest('tr');
            const harga = parseFloat(row.querySelector('[name="harga_beli_snapshot[]"]').value || 0);
            const jumlah = parseInt(input.value || 0);
            const total = harga * jumlah;
            row.querySelector('td:nth-child(5) input').value = total.toFixed(2);
            hitungGrandTotal();
        }

        function hitungGrandTotal() {
            let total = 0;
            document.querySelectorAll('#barangTable tbody tr').forEach(row => {
                total += parseFloat(row.querySelector('td:nth-child(5) input').value || 0);
            });
            document.getElementById('totalHarga').value = total.toFixed(2);
        }

        function hapusBaris(button) {
            button.closest('tr').remove();
            hitungGrandTotal();
        }
    </script>
@endsection
