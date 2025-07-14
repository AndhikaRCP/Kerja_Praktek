@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode Transaksi</th>
                                        <th>Supplier</th>
                                        <th>User Input</th>
                                        <th>Tanggal</th>
                                        <th>Total Harga</th>
                                        <th>Status Transaksi</th>
                                        <th style="width: 15%; text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pembelians as $pembelian)
                                        <tr>
                                            <td>{{ $pembelian->kode_transaksi }}</td>
                                            <td>{{ $pembelian->supplier->nama ?? '-' }}</td>
                                            <td>{{ $pembelian->user->name ?? '-' }}</td>
                                            <td data-order="{{ $pembelian->tanggal }}">
                                                {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d-m-Y') }}</td>
                                            <td data-order="{{ $pembelian->total_harga }}">Rp
                                                {{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
                                            @php
                                                $warnaStatus = [
                                                    'selesai' => 'success', // Hijau
                                                    'batal' => 'danger', // Merah
                                                    'diproses' => 'warning', // Kuning
                                                    'draft' => 'secondary', // Abu
                                                ];
                                            @endphp

                                            <td>
                                                <span
                                                    class="badge bg-{{ $warnaStatus[$pembelian->status_transaksi] ?? 'dark' }}">
                                                    {{ ucfirst($pembelian->status_transaksi) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('pembelian.show', $pembelian->id) }}"
                                                    class="btn btn-sm btn-info" title="Detail">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('pembelian.edit', $pembelian->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form id="delete-form-{{ $pembelian->id }}"
                                                    action="{{ route('pembelian.destroy', $pembelian->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete(event, this)"
                                                        data-url="{{ route('pembelian.destroy', $pembelian->id) }}"
                                                        title="Hapus Penjualan">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Belum ada data pembelian.</td>
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
    @include('layouts.components.scripts.form_validation')
    @include('layouts.components.scripts.sweetalerts')
    @include('layouts.components.scripts.datatables')
    @include('layouts.components.scripts.confirm_delete')
@endpush
