@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Riwayat Nota Penjualan</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('penjualan.index') }}" class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control"
                                    value="{{ request('tanggal_mulai') }}">
                            </div>
                            <div class="col-md-3">
                                <label>Tanggal Akhir</label>
                                <input type="date" name="tanggal_sampai" class="form-control"
                                    value="{{ request('tanggal_sampai') }}">
                            </div>
                            <div class="col-md-3">
                                <label>Pelanggan</label>
                                <select name="pelanggan_id" class="form-control">
                                    <option value="">-- Semua Pelanggan --</option>
                                    @foreach ($pelanggans as $pelanggan)
                                        <option value="{{ $pelanggan->id }}"
                                            {{ request('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                                            {{ $pelanggan->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">-- Semua Status --</option>
                                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas
                                    </option>
                                    <option value="belum lunas" {{ request('status') == 'belum lunas' ? 'selected' : '' }}>
                                        Belum Lunas
                                    </option>
                                </select>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                                <a href="{{ route('penjualan.index') }}" class="btn btn-secondary"><i
                                        class="fa fa-sync-alt"></i> Reset</a>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Pelanggan</th>
                                        <th>Sales</th>
                                        <th>Total Harga</th>
                                        <th>Nominal Dibayar</th> {{-- Tambahan --}}
                                        <th>Jenis Pembayaran</th>
                                        <th>Status Transaksi</th>
                                        <th style="width: 15%; text-align: center;">Aksi</th>
                                        {{-- <th style="width: 15%; text-align: center;">Aksi</th> --}}
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($penjualans as $penjualan)
                                        <tr>
                                            <td>{{ $penjualan->kode_transaksi }}</td>
                                            <td data-order="{{ $penjualan->tanggal }}">
                                                {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td>
                                            <td>{{ $penjualan->pelanggan->nama ?? '-' }}</td>
                                            <td>{{ $penjualan->sales->name ?? '-' }}</td>
                                            <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                                            <td>
                                                Rp
                                                {{ number_format($penjualan->pembayaranPenjualans->sum('nominal'), 0, ',', '.') }}
                                            </td>
                                            @php
                                                $warna_metode = [
                                                    'tunai' => 'primary',
                                                    'kredit' => 'warning',
                                                ];
                                            @endphp
                                            <td>
                                                <span
                                                    class="badge bg-{{ $warna_metode[$penjualan->jenis_pembayaran] ?? 'dark' }}">
                                                    {{ ucfirst($penjualan->jenis_pembayaran) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $penjualan->status_transaksi == 'lunas' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($penjualan->status_transaksi) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('penjualan.cetak', $penjualan->id) }}" target="_blank"
                                                    class="btn btn-sm btn-info" title="Cetak Nota">
                                                    <i class="fa fa-print"></i>
                                                </a>

                                                <form id="delete-form-{{ $penjualan->id }}"
                                                    action="{{ route('penjualan.destroy', $penjualan->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete(event, this)"
                                                        data-url="{{ route('penjualan.destroy', $penjualan->id) }}"
                                                        title="Hapus Penjualan">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Belum ada data penjualan.</td>
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

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#add-row').DataTable({
                    pageLength: 5,
                });

                var action =
                    '<td><div class="form-button-action">' +
                    '<button type="button" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task">' +
                    '<i class="fa fa-edit"></i></button>' +
                    '<button type="button" class="btn btn-link btn-danger" data-original-title="Remove">' +
                    '<i class="fa fa-times"></i></button></div></td>';

                $('#addRowButton').click(function() {
                    $('#add-row')
                        .DataTable()
                        .row.add([
                            $('#addName').val(),
                            $('#addPosition').val(),
                            $('#addOffice').val(),
                            action,
                        ])
                        .draw();

                    $('#addRowModal').modal('hide');
                });
            });
        </script>
    @endpush
@endsection
{{--
@push('scripts')
    @include('layouts.components.scripts.form_validation')
    @include('layouts.components.scripts.confirm_delete')
@endpush --}}


@push('scripts')
    <script>
        @if (session('success') && session('cetak_id'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'Cetak Nota',
                cancelButtonText: 'Nanti Saja'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open("{{ route('penjualan.cetak', session('cetak_id')) }}", "_blank");
                }
            });
        @endif
    </script>
@endpush


@push('scripts')
    @include('layouts.components.scripts.form_validation')
    @include('layouts.components.scripts.sweetalerts')
    @include('layouts.components.scripts.confirm_delete')
@endpush
