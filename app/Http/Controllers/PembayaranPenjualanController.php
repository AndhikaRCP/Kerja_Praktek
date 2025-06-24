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
        $pembayaran_penjualans = PembayaranPenjualan::with('penjualan')->latest()->get();

        // Ambil transaksi penjualan yang belum lunas saja untuk dropdown modal
        $penjualansBelumLunas = Penjualan::with('pelanggan')
            ->where('status_transaksi', '!=', 'lunas')
            ->get();

        return view('pembayaran_penjualan.index', [
            'pembayaran_penjualans' => $pembayaran_penjualans,
            'penjualans_belum_lunas' => $penjualansBelumLunas, // hanya yang belum lunas
        ]);
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
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:1',
            'metode' => 'nullable|string|max:50',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        }

        PembayaranPenjualan::create([
            'penjualan_id' => $request->penjualan_id,
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'metode' => $request->metode,
            'bukti_pembayaran' => $path,
            'keterangan' => $request->keterangan,
        ]);

        // Hitung total pembayaran yang sudah dilakukan untuk penjualan ini
        $totalDibayar = PembayaranPenjualan::where('penjualan_id', $request->penjualan_id)->sum('nominal');

        // Ambil total harga penjualan terkait
        $penjualan = Penjualan::findOrFail($request->penjualan_id);

        // Update status jika total dibayar >= total harga
        if ($totalDibayar >= $penjualan->total_harga) {
            $penjualan->update(['status_transaksi' => 'lunas']);
        } else {
            $penjualan->update(['status_transaksi' => 'belum lunas']);
        }


        return redirect()->route('pembayaran_penjualan.index')->with('success', 'Pembayaran berhasil ditambahkan.');
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
            'jenis_pembayaran' => 'required|string|max:255',
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

    public function cariTransaksiBelumLunas(Request $request)
    {
        $search = $request->q;

        $penjualans = Penjualan::with('pelanggan')
            ->where('status_transaksi', 'belum lunas')
            ->where(function ($query) use ($search) {
                $query->where('kode_transaksi', 'like', "%$search%")
                    ->orWhereHas('pelanggan', function ($q) use ($search) {
                        $q->where('nama', 'like', "%$search%");
                    });
            })
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();

        $results = [];
        foreach ($penjualans as $pj) {
            $results[] = [
                'id' => $pj->id,
                'text' => $pj->kode_transaksi . ' - ' . ($pj->pelanggan->nama ?? '-'),
            ];
        }

        return response()->json($results);
    }
}
