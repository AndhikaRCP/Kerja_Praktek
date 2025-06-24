@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Tambah Pembayaran Penjualan</h4>
                    </div>
                    <form action="{{ route('pembayaran_penjualan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="penjualan_id" class="form-label">Kode Transaksi Penjualan</label>
                                <select name="penjualan_id" class="form-select select2" required>
                                    <option value="">-- Pilih Transaksi --</option>
                                    @foreach ($penjualans as $penjualan)
                                        <option value="{{ $penjualan->id }}">
                                            {{ $penjualan->kode_transaksi }} - {{ $penjualan->pelanggan->nama ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal Pembayaran</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ now()->toDateString() }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="nominal" class="form-label">Nominal Pembayaran</label>
                                <input type="text" name="nominal" class="form-control text-end" placeholder="Contoh: 500.000" required>
                            </div>

                            <div class="mb-3">
                                <label for="metode" class="form-label">Metode Pembayaran</label>
                                <input type="text" name="metode" class="form-control" placeholder="Contoh: Transfer, Cash, QRIS" required>
                            </div>

                            <div class="mb-3">
                                <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran (Opsional)</label>
                                <input type="file" name="bukti_pembayaran" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                                <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Pembayaran cicilan ke-2"></textarea>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <a href="{{ route('pembayaran_penjualan.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Pilih transaksi",
                allowClear: true
            });

            $('input[name="nominal"]').on('input', function () {
                let angka = $(this).val().replace(/\./g, '');
                if (!angka) return $(this).val('');
                $(this).val(angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
            });
        });
    </script>
@endpush
