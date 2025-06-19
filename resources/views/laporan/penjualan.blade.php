@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Laporan Penjualan</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                                data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i>
                                Add Row
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Modal -->
                        <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold"> New</span>
                                            <span class="fw-light"> Row </span>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="small">
                                            Create a new row using this form, make sure you
                                            fill them all
                                        </p>
                                        <form>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Name</label>
                                                        <input id="addName" type="text" class="form-control"
                                                            placeholder="fill name" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Position</label>
                                                        <input id="addPosition" type="text" class="form-control"
                                                            placeholder="fill position" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Office</label>
                                                        <input id="addOffice" type="text" class="form-control"
                                                            placeholder="fill office" />
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" id="addRowButton" class="btn btn-primary">
                                            Add
                                        </button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Pelanggan</th>
                                        <th>Sales</th>
                                        <th>User Input</th>
                                        <th>Total Harga</th>
                                        <th>Status Pembayaran</th>
                                        <th>Status Transaksi</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($penjualans as $index => $penjualan)
                                        <tr>
                                            <td>{{ $index + $penjualans->firstItem() }}</td>
                                            <td>{{ $penjualan->kode_transaksi }}</td>
                                            <td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td>
                                            <td>{{ $penjualan->pelanggan->nama ?? '-' }}</td>
                                            <td>{{ $penjualan->sales->name ?? '-' }}</td>
                                            <td>{{ $penjualan->user->name ?? '-' }}</td>
                                            <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                                            <td>{{ ucfirst($penjualan->status_pembayaran) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $penjualan->status_transaksi == 'selesai' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($penjualan->status_transaksi) }}
                                                </span>
                                            </td>
                                            <td>{{ $penjualan->keterangan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">Belum ada data penjualan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Total -->
                        <div class="mt-4 text-end">
                            <h5>
                                Total Nilai Penjualan:
                                <span class="badge bg-primary">
                                    Rp {{ number_format($total_penjualan, 0, ',', '.') }}
                                </span>
                            </h5>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $penjualans->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#add-row').DataTable({
                    pageLength: 5,
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
