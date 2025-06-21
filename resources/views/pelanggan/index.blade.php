@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Data Pelanggan</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i>
                                Tambah Data Pelanggan
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Modal Tambah Pelanggan -->
                        <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('pelanggan.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">Tambah Pelanggan</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group form-group-default">
                                                <label>Nama</label>
                                                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                                            </div>
                                            <div class="form-group form-group-default">
                                                <label>Alamat</label>
                                                <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}" required>
                                            </div>
                                            <div class="form-group form-group-default">
                                                <label>Kota</label>
                                                <input type="text" name="kota" class="form-control" value="{{ old('kota') }}" required>
                                            </div>
                                            <div class="form-group form-group-default">
                                                <label>Telepon</label>
                                                <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}" required>
                                            </div>
                                        </div>

                                        <div class="modal-footer border-0">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Tabel -->
                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover nowrap" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th style="white-space: nowrap;">No</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Kota</th>
                                        <th>Telepon</th>
                                        <th style="width: 15%; text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pelanggans as $pelanggan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap;">{{ $pelanggan->nama }}</td>
                                            <td>{{ $pelanggan->alamat }}</td>
                                            <td>{{ $pelanggan->kota }}</td>
                                            <td>{{ $pelanggan->telepon }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('pelanggan.edit', $pelanggan->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('pelanggan.destroy', $pelanggan->id) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete(event, this)"
                                                        data-url="{{ route('pelanggan.destroy', $pelanggan->id) }}"
                                                        title="Hapus">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada data pelanggan.</td>
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
        @include('layouts.components.scripts.form_validation')
        @include('layouts.components.scripts.sweetalerts')
        @include('layouts.components.scripts.datatables')
        @include('layouts.components.scripts.confirm_delete')
    @endpush
@endsection
