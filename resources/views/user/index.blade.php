@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Manajemen Akun</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                                data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i> Tambah Akun
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Modal Tambah Akun --}}
                        <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('user.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">Tambah Akun Pengguna</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group form-group-default">
                                                <label>Username</label>
                                                <input type="text" name="username" class="form-control"
                                                    value="{{ old('username') }}" required>
                                            </div>
                                            <div class="form-group form-group-default">
                                                <label>Nama</label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ old('name') }}"required>
                                            </div>
                                            <div class="form-group form-group-default">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ old('email') }}" required>
                                            </div>
                                            <div class="form-group form-group-default">
                                                <label>Password</label>
                                                <input type="password" name="password" class="form-control"
                                                    value="{{ old('password') }}" required>
                                            </div>
                                            <div class="form-group form-group-default">
                                                <label>Konfirmasi Password</label>
                                                <input type="password" name="password_confirmation" class="form-control"
                                                    required>
                                            </div>

                                            <div class="form-group form-group-default">
                                                <label>Role</label>
                                                <select name="role" class="form-control" value="{{ old('role') }}"
                                                    required>
                                                    <option value="">-- Pilih Role --</option>
                                                    <option value="superadmin">SuperAdmin</option>
                                                    <option value="admin">Admin</option>
                                                    <option value="staff">Staff</option>
                                                </select>
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

                        {{-- Tabel Data --}}
                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover nowrap" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Dibuat Pada</th>
                                        <th style="text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $index => $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $user->role === 'superadmin' ? 'primary' : ($user->role === 'admin' ? 'warning text-dark' : 'info') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>{{ $user->created_at->format('d-m-Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('user.edit', $user->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Belum ada data pengguna.</td>
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
    @endpush
@endsection
