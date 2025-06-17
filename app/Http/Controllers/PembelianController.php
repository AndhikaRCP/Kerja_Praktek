<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Supplier;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with('supplier')->latest()->paginate(10);
        return view('pembelians.index', compact('pembelians'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $barangs = Barang::all();
        return view('pembelians.create', compact('suppliers', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'barang_kode' => 'required|array|min:1',
            'barang_kode.*' => 'required|exists:barangs,kode_barang',
            'jumlah.*' => 'required|integer|min:1',
            'harga_beli_snapshot.*' => 'required|numeric|min:0',
        ]);

        // Hitung total
        $total = 0;
        foreach ($request->barang_kode as $i => $kode) {
            $total += $request->jumlah[$i] * $request->harga_beli_snapshot[$i];
        }

        // Simpan header pembelian
        $pembelian = Pembelian::create([
            'kode_transaksi' => 'PB-' . strtoupper(Str::random(6)),
            'supplier_id' => $request->supplier_id,
            'user_id' => Auth::id(),
            'created_by' => Auth::id(),
            'tanggal' => $request->tanggal,
            'total_harga' => $total,
            'keterangan' => $request->keterangan,
        ]);

        // Simpan detail pembelian + update stok
        foreach ($request->barang_kode as $i => $kode) {
            $barang = Barang::where('kode_barang', $kode)->first();

            $detail = new DetailPembelian([
                'barang_kode' => $kode,
                'nama_barang_snapshot' => $barang->nama,
                'harga_beli_snapshot' => $request->harga_beli_snapshot[$i],
                'jumlah' => $request->jumlah[$i],
            ]);

            $pembelian->detailPembelians()->save($detail);

            // Update stok
            $barang->increment('stok', $request->jumlah[$i]);
        }

        return redirect()->route('pembelians.index')->with('success', 'Pembelian berhasil disimpan.');
    }

    public function show(Pembelian $pembelian)
    {
        $pembelian->load(['supplier', 'detailPembelians']);
        return view('pembelians.show', compact('pembelian'));
    }
}
