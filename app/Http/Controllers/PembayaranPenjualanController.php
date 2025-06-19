<?php

namespace App\Http\Controllers;

use App\Models\PembayaranPenjualan;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class PembayaranPenjualanController extends Controller
{
    // Tampilkan semua data pembayaran
    public function index()
    {
        $pembayaran_penjualans = PembayaranPenjualan::with('penjualan')->paginate(10);
        return view('pembayaran_penjualan.index', compact('pembayaran_penjualans'));
    }


    // Tampilkan form tambah pembayaran
    public function create()
    {
        $penjualan = Penjualan::all(); // untuk select penjualan
        return view('pembayaran_penjualan.create', compact('penjualan'));
    }

    // Simpan data pembayaran baru
    public function store(Request $request)
    {
        $request->validate([
            'penjualan_id' => 'required|exists:penjualans,id',
            'tanggal_pembayaran' => 'required|date',
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|string|max:255',
        ]);

        PembayaranPenjualan::create($request->all());

        return redirect()->route('pembayaran-penjualan.index')
            ->with('success', 'Data pembayaran berhasil ditambahkan.');
    }

    // Tampilkan form edit pembayaran
    public function edit(PembayaranPenjualan $pembayaran_penjualan)
    {
        $penjualan = Penjualan::all();
        return view('pembayaran_penjualan.edit', compact('pembayaran_penjualan', 'penjualan'));
    }

    // Simpan perubahan
    public function update(Request $request, PembayaranPenjualan $pembayaran_penjualan)
    {
        $request->validate([
            'penjualan_id' => 'required|exists:penjualans,id',
            'tanggal_pembayaran' => 'required|date',
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|string|max:255',
        ]);

        $pembayaran_penjualan->update($request->all());

        return redirect()->route('pembayaran-penjualan.index')
            ->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    // Hapus data pembayaran
    public function destroy(PembayaranPenjualan $pembayaran_penjualan)
    {
        $pembayaran_penjualan->delete();

        return redirect()->route('pembayaran-penjualan.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
