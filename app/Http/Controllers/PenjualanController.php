<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::with(['pelanggan'])->latest()->paginate(10);
        return view('penjualan.index', compact('penjualan'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        $barangs = Barang::all();
        return view('penjualan.create', compact('pelanggans', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal' => 'required|date',
            'barang_kode' => 'required|array|min:1',
            'barang_kode.*' => 'required|exists:barangs,kode_barang',
            'jumlah.*' => 'required|integer|min:1',
            'harga_jual_snapshot.*' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:tunai,kredit,belum lunas',
            'keterangan' => 'nullable|string',
        ]);

        $total = 0;
        foreach ($request->barang_kode as $i => $kode) {
            $total += $request->jumlah[$i] * $request->harga_jual_snapshot[$i];
        }

        // Simpan header penjualan
        $penjualan = Penjualan::create([
            'kode_transaksi' => 'PJ-' . strtoupper(Str::random(6)),
            'pelanggan_id' => $request->pelanggan_id,
            'user_id' => Auth::id(),
            'sales_id' => Auth::user()->role === 'sales' ? Auth::id() : null,
            'tanggal' => $request->tanggal,
            'total_harga' => $total,
            'status_pembayaran' => $request->status_pembayaran,
            'status_transaksi' => 'selesai',
            'keterangan' => $request->keterangan,
            'created_by' => Auth::id(),
        ]);

        // Simpan detail & kurangi stok
        foreach ($request->barang_kode as $i => $kode) {
            $barang = Barang::where('kode_barang', $kode)->first();

            DetailPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'kode_barang' => $kode,
                'nama_barang_snapshot' => $barang->nama,
                'harga_jual_snapshot' => $request->harga_jual_snapshot[$i],
                'jumlah' => $request->jumlah[$i],
            ]);

            $barang->decrement('stok', $request->jumlah[$i]);
        }

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil disimpan.');
    }

    public function show(Penjualan $penjualan)
    {
        $penjualan->load(['pelanggan', 'detailPenjualans']);
        return view('penjualan.show', compact('penjualan'));
    }
}
