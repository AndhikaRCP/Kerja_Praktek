@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Tambah Supplier</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('supplier.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group form-group-default">
                                        <label>Nama</label>
                                        <input type="text" name="nama" class="form-control"
                                            value="{{ old('nama') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group form-group-default">
                                        <label>Alamat</label>
                                        <input type="text" name="alamat" class="form-control"
                                            value="{{ old('alamat') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group form-group-default">
                                        <label>Kota</label>
                                        <input type="text" name="kota" class="form-control"
                                            value="{{ old('kota') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group form-group-default">
                                        <label>Telepon</label>
                                        <input type="text" name="telepon" class="form-control"
                                            value="{{ old('telepon') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Batal</a>
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
