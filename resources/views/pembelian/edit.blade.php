@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h4>Edit Transaksi Pembelian - {{ $pembelian->kode_transaksi }}</h4>
                </div>
                <form action="{{ route('pembelian.update', $pembelian->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        <!-- Supplier & Tanggal -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Supplier</label>
                                <select name="supplier_id" class="form-select" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ $supplier->id == $pembelian->supplier_id ? 'selected' : '' }}>
                                            {{ $supplier->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control"
                                    value="{{ $pembelian->tanggal }}" required>
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
                                <tbody>
                                    @foreach ($pembelian->detailPembelian as $detail)
                                        <tr>
                                            <td>
                                                <select name="barang_kode[]" class="form-select-barang" required>
                                                    <option value="">-- Pilih Barang --</option>
                                                    @foreach ($barangs as $barang)
                                                        <option value="{{ $barang->kode_barang }}"
                                                            data-nama="{{ $barang->nama }}"
                                                            data-harga="{{ $barang->harga_beli }}"
                                                            {{ $barang->kode_barang == $detail->barang_kode ? 'selected' : '' }}>
                                                            {{ $barang->kode_barang }} - {{ $barang->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="nama_barang_snapshot[]" class="form-control" value="{{ $detail->nama_barang_snapshot }}" readonly></td>
                                            <td><input type="number" name="harga_beli_snapshot[]" class="form-control text-end" value="{{ $detail->harga_beli_snapshot }}"></td>
                                            <td><input type="number" name="jumlah[]" class="form-control text-end" value="{{ $detail->jumlah }}" onchange="hitungTotal(this)"></td>
                                            <td><input type="text" class="form-control text-end" value="{{ $detail->jumlah * $detail->harga_beli_snapshot }}" readonly></td>
                                            <td><button type="button" class="btn btn-sm btn-danger" onclick="hapusBaris(this)">X</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="tambahBaris()">+ Tambah Barang</button>
                        </div>

                        <!-- Keterangan & Total -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3">{{ $pembelian->keterangan }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label>Total Harga</label>
                                <input type="text" id="totalHarga" class="form-control text-end"
                                    value="{{ $pembelian->total_harga }}" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Gunakan CSS sama seperti di halaman create */
    </style>
@endpush

@push('scripts')
    <script>
        const barangList = @json($barangs);

        $(document).ready(function () {
            $('select[name="supplier_id"]').select2({ placeholder: "Pilih supplier", allowClear: true });

            $('.form-select-barang').select2().on('change', function () {
                let selected = $(this).find('option:selected');
                let row = $(this).closest('tr');
                row.find('[name="nama_barang_snapshot[]"]').val(selected.data('nama'));
                row.find('[name="harga_beli_snapshot[]"]').val(selected.data('harga'));
                hitungTotal(row.find('[name="jumlah[]"]')[0]);
            });

            hitungGrandTotal(); // hitung ulang total saat pertama kali tampil
        });

        function tambahBaris() {
            let row = document.createElement('tr');

            let barangOptions = `<option value="">-- Pilih Barang --</option>`;
            barangList.forEach(barang => {
                barangOptions += `<option value="${barang.kode_barang}" data-nama="${barang.nama}" data-harga="${barang.harga_beli}">
                    ${barang.kode_barang} - ${barang.nama}
                </option>`;
            });

            row.innerHTML = `
                <td><select name="barang_kode[]" class="form-select-barang">${barangOptions}</select></td>
                <td><input type="text" name="nama_barang_snapshot[]" class="form-control" readonly></td>
                <td><input type="number" name="harga_beli_snapshot[]" class="form-control text-end"></td>
                <td><input type="number" name="jumlah[]" class="form-control text-end" value="1" onchange="hitungTotal(this)"></td>
                <td><input type="text" class="form-control text-end" readonly></td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="hapusBaris(this)">X</button></td>
            `;
            document.querySelector('#barangTable tbody').appendChild(row);

            $(row).find('.form-select-barang').select2().on('change', function () {
                let selected = $(this).find('option:selected');
                let rowEl = $(this).closest('tr');
                rowEl.find('[name="nama_barang_snapshot[]"]').val(selected.data('nama'));
                rowEl.find('[name="harga_beli_snapshot[]"]').val(selected.data('harga'));
                hitungTotal(rowEl.find('[name="jumlah[]"]')[0]);
            });
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
@endpush
