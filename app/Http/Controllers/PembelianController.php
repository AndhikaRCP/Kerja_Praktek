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
        $pembelians = Pembelian::with('supplier')->latest()->get();
        return view('pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $barangs = Barang::all();
        $kodeBaru = 'PB-' . str_pad(Pembelian::max('id') + 1, 5, '0', STR_PAD_LEFT); // Contoh: PB-00001

        return view('pembelian.create', compact('suppliers', 'barangs', 'kodeBaru'));
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

        // Hitung total harga
        $total = 0;
        foreach ($request->barang_kode as $i => $kode) {
            $total += $request->jumlah[$i] * $request->harga_beli_snapshot[$i];
        }

        // Simpan data pembelian
        $pembelian = Pembelian::create([
            'kode_transaksi' => 'PB-' . strtoupper(Str::random(6)),
            'supplier_id' => $request->supplier_id,
            // 'user_id' => Auth::id(),
            // 'created_by' => Auth::id(),
            'user_id' => 1, // <<--- sementara, isi dengan ID user yang sudah ada
            'created_by' => 1, // atau 'Testing'
            'tanggal' => $request->tanggal,
            'total_harga' => $total,
            'keterangan' => $request->keterangan,
        ]);

        // Simpan detail pembelian dan update stok
        foreach ($request->barang_kode as $i => $kode) {
            $barang = Barang::where('kode_barang', $kode)->firstOrFail();

            $detail = new DetailPembelian([
                'barang_kode' => $kode,
                'nama_barang_snapshot' => $barang->nama,
                'harga_beli_snapshot' => $request->harga_beli_snapshot[$i],
                'jumlah' => $request->jumlah[$i],
            ]);

            $pembelian->detailPembelian()->save($detail);

            // Update stok
            $barang->increment('stok', $request->jumlah[$i]);
        }

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil disimpan.');
    }


    public function show($id)
    {

        $pembelian = Pembelian::with(['supplier', 'user', 'detailPembelian.barang'])->findOrFail($id);

        return view('pembelian.show', compact('pembelian'));
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::with('detailPembelian.barang')->findOrFail($id);
        foreach ($pembelian->detailPembelian as $detail) {
            $barang = $detail->barang;
            if ($barang) {
                $barang->stok = max(0, $barang->stok - $detail->jumlah); // hindari negatif
                $barang->save();
            }
        }

        $pembelian->detailPembelian()->delete();
        $pembelian->delete();

        return redirect()->route('pembelian.index')
            ->with('success', 'Data pembelian berhasil dihapus dan stok diperbarui.');
    }
}
