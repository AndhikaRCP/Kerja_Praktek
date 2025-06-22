@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <form action="{{ route('user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-header">
                            <h4 class="card-title">Edit Akun Pengguna</h4>
                        </div>

                        <div class="card-body">

                            <div class="form-group form-group-default">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required
                                    value="{{ old('username', $user->username) }}">
                                @error('username')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group form-group-default">
                                <label>Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" required
                                    value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group form-group-default">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required
                                    value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group form-group-default">
                                <label>Password Baru (opsional)</label>
                                <input type="password" name="password" class="form-control">
                                @error('password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group form-group-default">
                                <label>Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>

                            <div class="form-group form-group-default">
                                <label>Role</label>
                                <select name="role" class="form-control" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="superadmin"
                                        {{ old('role', $user->role) === 'superadmin' ? 'selected' : '' }}>SuperAdmin
                                    </option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                        Admin</option>
                                    <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>
                                        Staff</option>
                                </select>
                                @error('role')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group form-group-default">
                                <label>Status Akun</label>
                                <select name="is_active" class="form-control" required>
                                    <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>
                                        Aktif</option>
                                    <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>
                                        Nonaktif</option>
                                </select>
                                @error('is_active')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
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
