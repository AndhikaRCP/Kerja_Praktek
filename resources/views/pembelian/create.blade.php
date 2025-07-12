@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
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
@endsection

@push('styles')
    <style>
        #barangTable th,
        #barangTable td {
            font-size: 0.85rem;
            padding: 0.4rem !important;
            vertical-align: middle;
        }

        #barangTable select,
        #barangTable input {
            font-size: 0.85rem;
            padding: 0.3rem 0.5rem;
        }

        #barangTable th:nth-child(1),
        #barangTable td:nth-child(1) {
            max-width: 110px;
            width: 110px;
        }

        #barangTable th:nth-child(2),
        #barangTable td:nth-child(2) {
            width: 100px;
        }

        #barangTable th:nth-child(3),
        #barangTable td:nth-child(3) {
            width: 130px;
        }

        #barangTable th:nth-child(4),
        #barangTable td:nth-child(4),
        #barangTable th:nth-child(5),
        #barangTable td:nth-child(5) {
            width: 100px;
        }

        #barangTable th:nth-child(6),
        #barangTable td:nth-child(6) {
            width: 50px;
            text-align: center;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        const barangList = @json($barangs);

        $(document).ready(function() {
            $('select[name="supplier_id"]').select2({
                placeholder: "Pilih atau cari supplier",
                allowClear: true
            });
        });

        function tambahBaris() {
            let row = document.createElement('tr');

            let barangOptions = `<option value="">-- Pilih Barang --</option>`;
            barangList.forEach(barang => {
                barangOptions += `<option value="${barang.kode_barang}"
                                data-nama="${barang.nama}"
                                data-harga="${barang.harga_beli}">
                                ${barang.kode_barang} - ${barang.nama}
                            </option>`;
            });

            row.innerHTML = `
            <td>
                <select name="barang_kode[]" class="form-select-barang" style="width: 100%" required>
                    ${barangOptions}
                </select>
            </td>
            <td><input type="text" name="nama_barang_snapshot[]" class="form-control" readonly></td>
            <td><input type="number" name="harga_beli_snapshot[]" class="form-control text-end" ></td>
            <td><input type="number" name="jumlah[]" class="form-control text-end" value="1" onchange="hitungTotal(this)"></td>
            <td><input type="text" class="form-control text-end" readonly></td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="hapusBaris(this)">X</button></td>
        `;

            document.querySelector('#barangTable tbody').appendChild(row);

            $(row).find('.form-select-barang').select2({
                placeholder: 'Pilih barang',
                allowClear: true
            }).on('change', function() {
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
