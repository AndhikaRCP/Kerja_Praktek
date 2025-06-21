@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-12">
                <div class="card">
                    <form action="{{ route('barang.update', $barang->kode_barang) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-header">
                            <h4 class="card-title">Edit Data Barang</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">

                                <!-- Kode Barang (readonly) -->
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Kode Barang</label>
                                        <input type="text" name="kode_barang" class="form-control"
                                            value="{{ $barang->kode_barang }}" readonly>
                                    </div>
                                </div>

                                <!-- Nama Barang -->
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Nama Barang</label>
                                        <input type="text" name="nama" class="form-control"
                                            value="{{ $barang->nama }}" required>
                                    </div>
                                </div>

                                <!-- Kategori -->
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Kategori</label>
                                        <select name="kategori_id" class="form-control" required>
                                            @foreach ($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}"
                                                    {{ $barang->kategori_id == $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Satuan -->
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Satuan</label>
                                        <input type="text" name="satuan" class="form-control"
                                            value="{{ $barang->satuan }}" required>
                                    </div>
                                </div>

                                <!-- Stok -->
                                <div class="col-md-4">
                                    <div class="form-group form-group-default">
                                        <label>Stok</label>
                                        <input type="number" name="stok" class="form-control"
                                            value="{{ $barang->stok }}" required>
                                    </div>
                                </div>

                                <!-- Harga Beli -->
                                <div class="col-md-4">
                                    <div class="form-group form-group-default">
                                        <label>Harga Beli</label>
                                        <input type="number" name="harga_beli" class="form-control"
                                            value="{{ $barang->harga_beli }}" required>
                                    </div>
                                </div>

                                <!-- Harga Jual -->
                                <div class="col-md-4">
                                    <div class="form-group form-group-default">
                                        <label>Harga Jual</label>
                                        <input type="number" name="harga_jual" class="form-control"
                                            value="{{ $barang->harga_jual }}" required>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('layouts.components.scripts.form_validation')
@endpush
