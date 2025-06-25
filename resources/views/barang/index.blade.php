@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Data Barang</h4>
                            @if (in_array($role, ['admin', 'superadmin']))
                                <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                                    data-bs-target="#addRowModal">
                                    <i class="fa fa-plus"></i>
                                    Tambah Data Barang
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Modal Tambah Barang -->
                        @if (in_array($role, ['admin', 'superadmin']))
                            <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <form action="{{ route('barang.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title">
                                                    <span class="fw-mediumbold">Tambah Data Barang</span>
                                                </h5>
                                                <button type="button" class="close" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default">
                                                            <label>Kode Barang</label>
                                                            <input name="kode_barang" type="text" class="form-control"
                                                                value="{{ old('kode_barang') }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default">
                                                            <label>Nama Barang</label>
                                                            <input name="nama" type="text" class="form-control"
                                                                value="{{ old('nama') }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default">
                                                            <label>Kategori</label>
                                                            <select name="kategori_id" class="form-control" required>
                                                                <option value="">-- Pilih Kategori --</option>
                                                                @foreach ($kategoris as $kategori)
                                                                    <option value="{{ $kategori->id }}"
                                                                        {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                                                        {{ $kategori->nama_kategori }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default">
                                                            <label>Satuan</label>
                                                            <input name="satuan" type="text" class="form-control"
                                                                value="{{ old('satuan', 'pcs') }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group form-group-default">
                                                            <label>Stok</label>
                                                            <input name="stok" type="number" min="0"
                                                                class="form-control" value="{{ old('stok', 0) }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group form-group-default">
                                                            <label>Harga Beli</label>
                                                            <input name="harga_beli" type="number" min="0"
                                                                class="form-control" value="{{ old('harga_beli') }}"
                                                                required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group form-group-default">
                                                            <label>Harga Jual</label>
                                                            <input name="harga_jual" type="number" min="0"
                                                                class="form-control" value="{{ old('harga_jual') }}"
                                                                required>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="modal-footer border-0">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Batal</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover nowrap" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th style="white-space: nowrap;">No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Satuan</th>
                                        <th>Stok</th>
                                        @if ($role === 'superadmin')
                                            <th>Harga Beli</th>
                                        @endif
                                        <th>Harga Jual</th>
                                        @if (in_array($role, ['admin', 'superadmin']))
                                            <th style="width: 15%; text-align: center;">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($barangs as $item)
                                        <tr>
                                            <td style="white-space: nowrap;">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap;">{{ $item->kode_barang }}</td>
                                            <td style="white-space: nowrap;">{{ $item->nama }}</td>
                                            <td style="white-space: nowrap;">{{ $item->kategori->nama_kategori ?? '-' }}
                                            </td>
                                            <td style="white-space: nowrap;">{{ $item->satuan }}</td>
                                            <td style="white-space: nowrap;">{{ $item->stok }}</td>
                                            @if ($role === 'superadmin')
                                                <td data-order="{{ $item->harga_beli }}" style="white-space: nowrap;">
                                                    Rp
                                                    {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                            @endif
                                            <td data-order="{{ $item->harga_jual }}" style="white-space: nowrap;">Rp
                                                {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                            @if (in_array($role, ['admin', 'superadmin']))
                                                <td class="text-center">
                                                    <a href="{{ route('barang.edit', $item->kode_barang) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('barang.destroy', $item->kode_barang) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            title="Hapus" onclick="confirmDelete(event, this)"
                                                            data-url="{{ route('barang.destroy', $item->kode_barang) }}">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data barang.</td>
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
