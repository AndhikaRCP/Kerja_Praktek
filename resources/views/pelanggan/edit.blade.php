@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-header">
                            <h4 class="card-title">Edit Pelanggan</h4>
                        </div>

                        <div class="card-body">
                            <!-- Nama -->
                            <div class="form-group form-group-default">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" required
                                    value="{{ old('nama', $pelanggan->nama) }}">
                                @if ($errors->has('nama'))
                                    <div class="text-danger mt-1">{{ $errors->first('nama') }}</div>
                                @endif
                            </div>

                            <!-- Alamat -->
                            <div class="form-group form-group-default">
                                <label>Alamat</label>
                                <input type="text" name="alamat" class="form-control" required
                                    value="{{ old('alamat', $pelanggan->alamat) }}">
                                @if ($errors->has('alamat'))
                                    <div class="text-danger mt-1">{{ $errors->first('alamat') }}</div>
                                @endif
                            </div>

                            <!-- Kota -->
                            <div class="form-group form-group-default">
                                <label>Kota</label>
                                <input type="text" name="kota" class="form-control" required
                                    value="{{ old('kota', $pelanggan->kota) }}">
                                @if ($errors->has('kota'))
                                    <div class="text-danger mt-1">{{ $errors->first('kota') }}</div>
                                @endif
                            </div>

                            <!-- Telepon -->
                            <div class="form-group form-group-default">
                                <label>Telepon</label>
                                <input type="text" name="telepon" class="form-control" required
                                    value="{{ old('telepon', $pelanggan->telepon) }}">
                                @if ($errors->has('telepon'))
                                    <div class="text-danger mt-1">{{ $errors->first('telepon') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">Kembali</a>
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
