@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-12">
                <div class="card">
                    {{-- Header dan Tombol Modal --}}
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Riwayat Pembayaran Penjualan</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
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
                                <form action="{{ route('pembayaran_penjualan.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTambahLabel">Tambah Pembayaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="penjualan_id" class="form-label">Transaksi</label>
                                            <select name="penjualan_id" id="penjualan_id" class="form-select" required>
                                                <option value="">-- Pilih Transaksi --</option>
                                                @foreach ($penjualans as $pj)
                                                    <option value="{{ $pj->id }}">
                                                        {{ $pj->kode_transaksi }} - {{ $pj->pelanggan->nama ?? '-' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal" class="form-label">Tanggal Pembayaran</label>
                                            <input type="date" name="tanggal" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nominal" class="form-label">Nominal</label>
                                            <input type="text" name="nominal" class="form-control" placeholder="contoh: 100000" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="metode" class="form-label">Metode</label>
                                            <input type="text" name="metode" class="form-control" placeholder="Contoh: Transfer / Tunai">
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
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Tabel Riwayat Pembayaran --}}
                        <div class="table-responsive mt-4">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Nominal</th>
                                        <th>Metode</th>
                                        <th>Bukti</th>
                                        <th>Keterangan</th>
                                        <th style="width: 15%; text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pembayaran_penjualans as $pembayaran)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pembayaran->penjualan->kode_transaksi ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d-m-Y') }}</td>
                                            <td>Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
                                            <td>{{ ucfirst($pembayaran->metode) }}</td>
                                            <td>
                                                @if ($pembayaran->bukti_pembayaran)
                                                    <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" target="_blank">Lihat</a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $pembayaran->keterangan ?? '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('pembayaran_penjualan.edit', $pembayaran->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('pembayaran_penjualan.destroy', $pembayaran->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Yakin ingin menghapus pembayaran ini?')"
                                                        title="Hapus">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            </td>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#add-row').DataTable({
                pageLength: 5,
            });
        });
    </script>
@endpush

    @push('scripts')
        @include('layouts.components.scripts.form_validation')
        @include('layouts.components.scripts.sweetalerts')
    @endpush
