<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Barang;
use App\Models\PembayaranPenjualan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with(['pelanggan'])->latest()->get();
        return view('penjualan.index', compact('penjualans'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        $barangs = Barang::all();
        $sales = User::where('role', 'sales')->get(); // hanya sales
        return view('penjualan.create', compact('pelanggans', 'barangs', 'sales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal' => 'required|date',
            'metode_pembayaran' => 'required|in:tunai,kredit',
            'barang_kode' => 'required|array|min:1',
            'barang_kode.*' => 'required|exists:barangs,kode_barang',
            'jumlah.*' => 'required|integer|min:1',
            'harga_jual_snapshot.*' => 'required|numeric|min:0',
            'bayar_nominal' => 'nullable|numeric|min:0',
            'metode' => 'nullable|string|max:50',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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
            'user_id' => 1, // ganti dengan auth()->id() kalau sudah login
            'created_by' => 1,
            'tanggal' => $request->tanggal,
            'total_harga' => $total,
            'status_pembayaran' => $request->status_pembayaran,
            'status_transaksi' => 'selesai',
            'keterangan' => $request->keterangan,
        ]);

        // Simpan detail penjualan dan update stok
        foreach ($request->barang_kode as $i => $kode) {
            $barang = Barang::where('kode_barang', $kode)->firstOrFail();

            if ($barang->stok < $request->jumlah[$i]) {
                return back()->with('error', "Stok barang {$barang->nama} tidak mencukupi.");
            }

            $penjualan->detailPenjualan()->create([
                'kode_barang' => $kode,
                'nama_barang_snapshot' => $barang->nama,
                'harga_jual_snapshot' => $request->harga_jual_snapshot[$i],
                'jumlah' => $request->jumlah[$i],
            ]);

            $barang->decrement('stok', $request->jumlah[$i]);
        }

        // Jika ada pembayaran awal (tunai/kredit)
        if ($request->bayar_nominal && $request->bayar_nominal > 0) {
            $path = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $path = $file->store('bukti_pembayaran', 'public');
            }

            PembayaranPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'tanggal' => $request->tanggal,
                'nominal' => $request->bayar_nominal,
                'metode' => $request->metode ?? 'Tunai',
                'bukti_pembayaran' => $path,
                'keterangan' => 'Pembayaran awal dari form penjualan',
            ]);
        }

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil disimpan.');
    }


    public function show(Penjualan $penjualan)
    {
        $penjualan->load(['pelanggan', 'detailPenjualans']);
        return view('penjualan.show', compact('penjualan'));
    }
}
