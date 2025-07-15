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
            'user_id' => Auth::id(),
            'created_by' => Auth::id(),
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

    public function edit(Pembelian $pembelian)
    {
        // Load relasi yang dibutuhkan
        $pembelian->load(['supplier', 'detailPembelian']);

        // Ambil semua data supplier dan barang untuk ditampilkan di dropdown
        $suppliers = Supplier::all();
        $barangs = Barang::all();

        return view('pembelian.edit', compact('pembelian', 'suppliers', 'barangs'));
    }

    public function update(Request $request, Pembelian $pembelian)
{
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'tanggal' => 'required|date',
        'barang_kode' => 'required|array',
        'barang_kode.*' => 'required|string|exists:barangs,kode_barang',
        'nama_barang_snapshot.*' => 'required|string',
        'harga_beli_snapshot.*' => 'required|numeric|min:0',
        'jumlah.*' => 'required|integer|min:1',
    ]);

    // 1. Kembalikan stok dari detail lama
    foreach ($pembelian->detailPembelian as $detail) {
        $barang = \App\Models\Barang::where('kode_barang', $detail->barang_kode)->first();
        if ($barang) {
            $barang->stok -= $detail->jumlah;
            $barang->save();
        }
    }

    // 2. Hapus detail lama
    $pembelian->detailPembelian()->delete();

    // 3. Hitung total harga baru
    $totalHarga = 0;
    foreach ($request->barang_kode as $i => $kode) {
        $subtotal = $request->harga_beli_snapshot[$i] * $request->jumlah[$i];
        $totalHarga += $subtotal;
    }

    // 4. Update data pembelian utama
    $pembelian->supplier_id = $request->supplier_id;
    $pembelian->tanggal = $request->tanggal;
    $pembelian->keterangan = $request->keterangan;
    $pembelian->total_harga = $totalHarga;
    $pembelian->save();

    // 5. Tambahkan detail baru + update stok baru
    foreach ($request->barang_kode as $i => $kode) {
        $pembelian->detailPembelian()->create([
            'barang_kode' => $kode,
            'nama_barang_snapshot' => $request->nama_barang_snapshot[$i],
            'harga_beli_snapshot' => $request->harga_beli_snapshot[$i],
            'jumlah' => $request->jumlah[$i],
        ]);

        // Tambah stok baru
        $barang = \App\Models\Barang::where('kode_barang', $kode)->first();
        if ($barang) {
            $barang->stok += $request->jumlah[$i];
            $barang->save();
        }
    }

    return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil diperbarui.');
}

}
