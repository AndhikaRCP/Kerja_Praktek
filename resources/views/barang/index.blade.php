@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Data Barang</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                                data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i>
                                Tambah Data Barang
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Modal Tambah Barang -->
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
                                                <!-- Kode Barang -->
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Kode Barang</label>
                                                        <input name="kode_barang" type="text" class="form-control"
                                                            value="{{ old('kode_barang') }}" required>
                                                    </div>
                                                </div>

                                                <!-- Nama Barang -->
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Nama Barang</label>
                                                        <input name="nama" type="text" class="form-control"
                                                            value="{{ old('nama') }}" required>
                                                    </div>
                                                </div>

                                                <!-- Kategori -->
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

                                                <!-- Satuan -->
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Satuan</label>
                                                        <input name="satuan" type="text" class="form-control"
                                                            value="{{ old('satuan', 'pcs') }}" required>
                                                    </div>
                                                </div>

                                                <!-- Stok -->
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label>Stok</label>
                                                        <input name="stok" type="number" min="0"
                                                            class="form-control" value="{{ old('stok', 0) }}" required>
                                                    </div>
                                                </div>

                                                <!-- Harga Beli -->
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label>Harga Beli</label>
                                                        <input name="harga_beli" type="number" min="0"
                                                            class="form-control" value="{{ old('harga_beli') }}" required>
                                                    </div>
                                                </div>

                                                <!-- Harga Jual -->
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label>Harga Jual</label>
                                                        <input name="harga_jual" type="number" min="0"
                                                            class="form-control" value="{{ old('harga_jual') }}" required>
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
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th style="width: 15%; text-align: center;">Aksi</th>
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
                                            <td style="white-space: nowrap;">Rp
                                                {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                            <td style="white-space: nowrap;">Rp
                                                {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('barang.edit', $item->kode_barang) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('barang.destroy', $item->kode_barang) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                                        onclick="confirmDelete(event, this)"
                                                        data-url="{{ route('barang.destroy', $item->kode_barang) }}">
                                                        <i class="fa fa-times"></i>
                                                    </button>


                                            </td>
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

    @push('scripts')
        <script>
            @if ($errors->any())
                $(document).ready(function() {
                    $('#addRowModal').modal('show');
                });
            @endif

            $(document).ready(function() {
                $('#add-row').DataTable({
                    pageLength: 10,
                });

                var action =
                    '<td><div class="form-button-action">' +
                    '<button type="button" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task">' +
                    '<i class="fa fa-edit"></i></button>' +
                    '<button type="button" class="btn btn-link btn-danger" data-original-title="Remove">' +
                    '<i class="fa fa-times"></i></button></div></td>';

                $('#addRowButton').click(function() {
                    $('#add-row')
                        .DataTable()
                        .row.add([
                            $('#addName').val(),
                            $('#addPosition').val(),
                            $('#addOffice').val(),
                            action,
                        ])
                        .draw();

                    $('#addRowModal').modal('hide');
                });
            });
        </script>
    @endpush
@endsection


@push('scripts')
    <script>
        document.querySelectorAll('input[required], select[required]').forEach(field => {
            field.oninvalid = function(e) {
                // Reset error
                e.target.setCustomValidity('');

                // Cek apakah kosong
                if (!e.target.value) {
                    e.target.setCustomValidity('Harap isi kolom ini');
                }

                // Cek angka negatif untuk input type number
                if (e.target.type === 'number' && parseFloat(e.target.value) < 0) {
                    e.target.setCustomValidity('tidak boleh negatif');
                };
            }
        });

        // Sukses Umum (misalnya tambah/update/hapus berhasil)
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        // Peringatan (data belum lengkap, konfirmasi manual, dll)
        @if (session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: '{{ session('warning') }}',
                confirmButtonColor: '#f0ad4e'
            });
        @endif

        // Error Validasi (dari Validator Laravel)
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33'
            });
        @endif

        // Info umum/netral
        @if (session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: '{{ session('info') }}',
                confirmButtonColor: '#3085d6'
            });
        @endif

        //  Notifikasi Hapus Sukses
        @if (session('deleted'))
            Swal.fire({
                icon: 'success',
                title: 'Data Dihapus!',
                text: '{{ session('deleted') }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        // Konfirmasi hapus (dipanggil via JS onClick)
        function confirmDelete(e, el) {
            e.preventDefault();
            const url = el.getAttribute('data-url');
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form dinamis lalu submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    const csrf = document.createElement('input');
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);

                    const method = document.createElement('input');
                    method.name = '_method';
                    method.value = 'DELETE';
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
