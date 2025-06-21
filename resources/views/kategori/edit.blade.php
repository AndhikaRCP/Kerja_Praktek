@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-header">
                            <h4 class="card-title">Edit Kategori</h4>
                        </div>

                        <div class="card-body">
                            <!-- Nama Kategori -->
                            <div class="form-group form-group-default">
                                <label>Nama Kategori</label>
                                <input type="text" name="nama_kategori" class="form-control" required
                                    value="{{ old('nama_kategori', $kategori->nama_kategori) }}">

                                @if ($errors->has('nama_kategori'))
                                    <div class="text-danger mt-1">
                                        {{ $errors->first('nama_kategori') }}
                                    </div>
                                @endif
                            </div>

                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('input[required]').forEach(field => {
            field.oninvalid = function(e) {
                e.target.setCustomValidity('');
                if (!e.target.value) {
                    e.target.setCustomValidity('Harap isi kolom ini');
                }
            };

            field.oninput = function(e) {
                e.target.setCustomValidity('');
            };
        });
    </script>
@endpush
