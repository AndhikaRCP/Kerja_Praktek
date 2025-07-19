@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Tambah Data Barang</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('barang.store') }}" method="POST">
                            @csrf
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <div class="form-group form-group-default">
                                        <label>Kode Barang</label>
                                        <input name="kode_barang" type="text" class="form-control"
                                            value="{{ old('kode_barang') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group form-group-default">
                                        <label>Nama Barang</label>
                                        <input name="nama" type="text" class="form-control"
                                            value="{{ old('nama') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
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

                                <div class="col-md-6 mb-3">
                                    <div class="form-group form-group-default">
                                        <label>Satuan</label>
                                        <input name="satuan" type="text" class="form-control"
                                            value="{{ old('satuan', 'pcs') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="form-group form-group-default">
                                        <label>Stok</label>
                                        <input name="stok" type="number" min="1"  onkeydown="return event.key !== '-' && event.key !== 'e'"  class="form-control"
                                            value="{{ old('stok', 1) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="form-group form-group-default">
                                        <label>Harga Beli</label>
                                        <input name="harga_beli" type="number" min="0" class="form-control"
                                            value="{{ old('harga_beli') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="form-group form-group-default">
                                        <label>Harga Jual</label>
                                        <input name="harga_jual" type="number" min="0" class="form-control"
                                            value="{{ old('harga_jual') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('layouts.components.scripts.form_validation')
    @include('layouts.components.scripts.sweetalerts')
@endpush
