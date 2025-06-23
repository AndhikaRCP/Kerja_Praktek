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
        $penjualans = Penjualan::with(['pelanggan'])->latest()->paginate(10);
        return view('penjualan.index', compact('penjualans'));
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
        ]);

        // Hitung total harga
        $total = 0;
        foreach ($request->barang_kode as $i => $kode) {
            $total += $request->jumlah[$i] * $request->harga_jual_snapshot[$i];
        }

        // Simpan data penjualan
        $penjualan = Penjualan::create([
            'kode_transaksi' => 'PJ-' . strtoupper(Str::random(6)),
            'pelanggan_id' => $request->pelanggan_id,
            'user_id' => 1, // <<--- sementara, isi ID user login
            'created_by' => 1,
            'tanggal' => $request->tanggal,
            'total_harga' => $total,
            'status_pembayaran' => $request->status_pembayaran ?? 'tunai',
            'status_transaksi' => 'selesai',
            'keterangan' => $request->keterangan,
        ]);

        // Simpan detail penjualan dan update stok
        foreach ($request->barang_kode as $i => $kode) {
            $barang = Barang::where('kode_barang', $kode)->firstOrFail();

            // Cek stok cukup
            if ($barang->stok < $request->jumlah[$i]) {
                return back()->with('error', "Stok barang {$barang->nama} tidak mencukupi.");
            }

            $detail = new DetailPenjualan([
                'kode_barang' => $kode,
                'nama_barang_snapshot' => $barang->nama,
                'harga_jual_snapshot' => $request->harga_jual_snapshot[$i],
                'jumlah' => $request->jumlah[$i],
            ]);

            $penjualan->detailPenjualan()->save($detail);

            // Kurangi stok
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
