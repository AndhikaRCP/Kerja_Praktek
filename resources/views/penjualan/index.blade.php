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
                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode Transaksi</th>
                                        <th>Pelanggan</th>
                                        <th>Sales</th>
                                        <th>Tanggal</th>
                                        <th>Total Harga</th>
                                        <th>Nominal Dibayar</th> {{-- Tambahan --}}
                                        <th>Jenis Pembayaran</th>
                                        <th>Status Transaksi</th>
                                        {{-- <th style="width: 15%; text-align: center;">Aksi</th> --}}
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($penjualans as $penjualan)
                                        <tr>
                                            <td>{{ $penjualan->kode_transaksi }}</td>
                                            <td>{{ $penjualan->pelanggan->nama ?? '-' }}</td>
                                            <td>{{ $penjualan->sales->name ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td>
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
                                                    class="badge bg-{{ $penjualan->status_transaksi === 'lunas' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($penjualan->status_transaksi) }}
                                                </span>
                                            </td>
                                            {{-- <td class="text-center">
                                                <a href="{{ route('penjualan.show', $penjualan->id) }}"
                                                    class="btn btn-sm btn-info" title="Detail">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('penjualan.edit', $penjualan->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('penjualan.destroy', $penjualan->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            </td> --}}
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

@push('scripts')
    @include('layouts.components.scripts.form_validation')
    @include('layouts.components.scripts.sweetalerts')
    @include('layouts.components.scripts.confirm_delete')
@endpush
