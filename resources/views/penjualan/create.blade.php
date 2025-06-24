@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h4>Form Transaksi Penjualan</h4>
                </div>
                <form action="{{ route('penjualan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <!-- Pelanggan & Tanggal -->
                        <div class="row mb-3">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Pelanggan</label>
                                    <select name="pelanggan_id" class="form-select" required>
                                        <option value="">-- Pilih Pelanggan --</option>
                                        @foreach ($pelanggans as $pelanggan)
                                            <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Referal Sales (Opsional)</label>
                                    <select name="referal_id" class="form-select">
                                        <option value="">-- Tidak Ada --</option>
                                        @foreach ($sales as $salesPerson)
                                            <option value="{{ $salesPerson->id }}">{{ $salesPerson->name }}
                                                ({{ $salesPerson->username }})</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>


                        <div class="col-md-6">
                            <label>Tanggal Transaksi</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ now()->toDateString() }}"
                                required>
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
                                    <th>Harga Jual</th>
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

                    <!-- Status & Pembayaran -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Status Pembayaran</label>
                            <select name="status_pembayaran" class="form-select" required onchange="togglePembayaran()">
                                <option value="tunai">Tunai</option>
                                <option value="kredit">Kredit</option>
                                <option value="belum lunas">Belum Lunas</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="pembayaranSection">
                            <label>Nominal Pembayaran Awal</label>
                            <input type="number" name="bayar_nominal" class="form-control text-end" placeholder="0">
                            <label class="mt-2">Metode</label>
                            <input type="text" name="metode" class="form-control" placeholder="Contoh: Tunai, Transfer">
                            <label class="mt-2">Bukti Pembayaran</label>
                            <input type="file" name="bukti_pembayaran" class="form-control">
                        </div>
                    </div>
            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Batal</a>
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
            min-width: 150px;
        }

        #barangTable th:nth-child(3),
        #barangTable td:nth-child(3),
        #barangTable th:nth-child(4),
        #barangTable td:nth-child(4),
        #barangTable th:nth-child(5),
        #barangTable td:nth-child(5) {
            max-width: 100px;
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
    <script>
        const barangList = @json($barangs);

        $(document).ready(function() {
            $('select[name="pelanggan_id"]').select2({
                placeholder: "Pilih atau cari pelanggan",
                allowClear: true
            });
            togglePembayaran();
        });

        function togglePembayaran() {
            const status = document.querySelector('[name="status_pembayaran"]').value;
            const section = document.getElementById('pembayaranSection');
            section.style.display = (status === 'belum lunas') ? 'none' : 'block';
        }

        function tambahBaris() {
            let row = document.createElement('tr');

            let barangOptions = `<option value="">-- Pilih Barang --</option>`;
            barangList.forEach(barang => {
                barangOptions += `<option value="${barang.kode_barang}"
                data-nama="${barang.nama}"
                data-harga="${barang.harga_jual}">
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
            <td><input type="number" name="harga_jual_snapshot[]" class="form-control text-end" readonly></td>
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
                rowEl.find('[name="harga_jual_snapshot[]"]').val(selected.data('harga'));
                hitungTotal(rowEl.find('[name="jumlah[]"]')[0]);
            });
        }

        function hitungTotal(input) {
            const row = input.closest('tr');
            const harga = parseFloat(row.querySelector('[name="harga_jual_snapshot[]"]').value || 0);
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
