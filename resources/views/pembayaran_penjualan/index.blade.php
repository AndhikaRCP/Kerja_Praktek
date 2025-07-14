@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-12">
                <div class="card">
                    {{-- Header --}}
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Riwayat Pembayaran Penjualan</h4>
                            <button class="btn btn-secondary ms-auto" data-bs-toggle="modal"
                                data-bs-target="#modalTambahPembayaran">
                                <i class="fa fa-plus"></i> Tambah Pembayaran
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Modal Tambah Pembayaran --}}
                        <div class="modal fade" id="modalTambahPembayaran" tabindex="-1" aria-labelledby="modalTambahLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('pembayaran_penjualan.store') }}" method="POST"
                                    enctype="multipart/form-data" class="modal-content">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTambahLabel">Tambah Pembayaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{-- Transaksi --}}
                                        <div class="mb-3">
                                            <label for="penjualan_id" class="form-label">Transaksi Belum Lunas</label>
                                            <select name="penjualan_id" id="select-transaksi" class="form-select"
                                                style="width: 100%" required>
                                                <option value="">-- Pilih Transaksi --</option>
                                                @foreach ($penjualans_belum_lunas as $pj)
                                                    <option value="{{ $pj->id }}">{{ $pj->kode_transaksi }} -
                                                        {{ $pj->pelanggan->nama ?? '-' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal" class="form-label">Tanggal Pembayaran</label>
                                            <input type="date" name="tanggal" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nominal" class="form-label">Nominal</label>
                                            <input type="text" name="nominal" class="form-control"
                                                placeholder="Contoh: 100000" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="metode" class="form-label">Metode</label>
                                            <input type="text" name="metode" class="form-control"
                                                placeholder="Contoh: Transfer / Tunai">
                                        </div>
                                        <div class="mb-3">
                                            <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
                                            <input type="file" name="bukti_pembayaran" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="keterangan" class="form-label">Keterangan</label>
                                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-secondary">Simpan</button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Tabel Riwayat --}}
                        <div class="table-responsive mt-4">
                            <table id="add-row" class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Nominal</th>
                                        <th>Metode</th>
                                        <th>Bukti</th>
                                        <th>Keterangan</th>
                                        @if (auth()->user()->role === 'superadmin')
                                            <th class="text-center">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pembayaran_penjualans as $pembayaran)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pembayaran->penjualan->kode_transaksi ?? '-' }}</td>
                                            <td data-order="{{ $pembayaran->tanggal }}">
                                                {{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d-m-Y') }}</td>
                                            <td>Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
                                            <td>{{ ucfirst($pembayaran->metode) }}</td>
                                            <td>
                                                @if ($pembayaran->bukti_pembayaran)
                                                    <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}"
                                                        target="_blank">Lihat</a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $pembayaran->keterangan ?? '-' }}</td>
                                            @if (auth()->user()->role === 'superadmin')
                                                <td class="text-center">

                                                    <form id="delete-form-{{ $pembayaran->id }}"
                                                        action="{{ route('pembayaran_penjualan.destroy', $pembayaran->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete(event, this)"
                                                            data-url="{{ route('pembayaran_penjualan.destroy', $pembayaran->id) }}">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>

                                                </td>
                                            @endif

                                            {{-- <td class="text-center">
                                            <a href="{{ route('pembayaran_penjualan.edit', $pembayaran->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('pembayaran_penjualan.destroy', $pembayaran->id) }}" method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pembayaran ini?')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        </td> --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Belum ada data pembayaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#add-row').DataTable({
                pageLength: 5
            });

            // Select2 init hanya saat modal dibuka
            $('#modalTambahPembayaran').on('shown.bs.modal', function() {
                $('#select-transaksi').select2({
                    dropdownParent: $('#modalTambahPembayaran'),
                    placeholder: 'Cari transaksi belum lunas...',
                    allowClear: true,
                    width: '100%'
                });
            });

            // Format nominal secara otomatis (opsional)
            $('input[name="nominal"]').on('input', function() {
                const value = $(this).val().replace(/\D/g, '');
                $(this).val(value.replace(/\B(?=(\d{3})+(?!\d))/g, "."));
            });
        });
    </script>
@endpush

@push('scripts')
    @include('layouts.components.scripts.form_validation')
    @include('layouts.components.scripts.sweetalerts')
    @include('layouts.components.scripts.confirm_delete')
@endpush
