@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <form action="{{ route('pembayaran_penjualan.update', $pembayaran->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-header">
                            <h4 class="card-title">Edit Riwayat Pembayaran</h4>
                        </div>

                        <div class="card-body">
                            {{-- Transaksi --}}
                            <div class="mb-3">
                                <label for="penjualan_id" class="form-label">Transaksi</label>
                                <select name="penjualan_id" class="form-select" required disabled>
                                    <option value="{{ $pembayaran->penjualan_id }}">
                                        {{ $pembayaran->penjualan->kode_transaksi }} -
                                        {{ $pembayaran->penjualan->pelanggan->nama ?? '-' }}
                                    </option>
                                </select>
                                <input type="hidden" name="penjualan_id" value="{{ $pembayaran->penjualan_id }}">
                            </div>

                            {{-- Tanggal --}}
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal Pembayaran</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ $pembayaran->tanggal }}"
                                    required>
                            </div>

                            {{-- Nominal --}}
                            <div class="mb-3">
                                <label for="nominal" class="form-label">Nominal</label>
                                <input type="text" name="nominal" class="form-control"
                                    value="{{ number_format($pembayaran->nominal, 0, ',', '.') }}" required>
                            </div>

                            {{-- Metode --}}
                            <div class="mb-3">
                                <label for="metode" class="form-label">Metode</label>
                                <input type="text" name="metode" class="form-control" value="{{ $pembayaran->metode }}"
                                    required>
                            </div>

                            {{-- Bukti --}}
                            <div class="mb-3">
                                <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran (Opsional)</label>
                                @if ($pembayaran->bukti_pembayaran)
                                    <p><a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}"
                                            target="_blank">Lihat File</a></p>
                                @endif
                                <input type="file" name="bukti_pembayaran" class="form-control">
                            </div>

                            {{-- Keterangan --}}
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="2">{{ $pembayaran->keterangan }}</textarea>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('pembayaran_penjualan.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('input[name="nominal"]').on('input', function() {
            const value = $(this).val().replace(/\D/g, '');
            $(this).val(value.replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        });
    </script>
@endpush

@push('scripts')
    @include('layouts.components.scripts.form_validation')
    @include('layouts.components.scripts.sweetalerts')
    @include('layouts.components.scripts.confirm_delete')
@endpush
