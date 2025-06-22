<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Http\Request;

class LaporanPembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with(['supplier', 'user']);

        // Filter tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }

        // Filter supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter status transaksi
        if ($request->filled('status_transaksi')) {
            $query->where('status_transaksi', $request->status_transaksi);
        }

        // Ambil data yang sudah difilter
        $pembelians = $query->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();
        $total_pembelian = $query->sum('total_harga');

        // Ambil semua supplier untuk dropdown filter
        $suppliers = Supplier::orderBy('nama')->get();

        return view('laporan.pembelian.index', compact(
            'pembelians',
            'total_pembelian',
            'suppliers'
        ));
    }

    public function show($id)
    {
        $pembelian = Pembelian::with(['supplier', 'user', 'detailPembelian'])->findOrFail($id);
        return view('laporan.pembelian.show', compact('pembelian'));
    }
}
