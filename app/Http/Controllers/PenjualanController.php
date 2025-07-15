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
        $penjualans = Penjualan::with(['pelanggan', 'sales', 'pembayaranPenjualans'])->latest()->get();
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
        // Convert format ribuan ke angka mentah
        $cleanHarga = array_map(function ($harga) {
            return floatval(str_replace('.', '', $harga));
        }, $request->harga_jual_snapshot);

        $request->merge(['harga_jual_snapshot' => $cleanHarga]);

        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal' => 'required|date',
            'jenis_pembayaran' => 'required|in:tunai,kredit',
            'barang_kode' => 'required|array|min:1',
            'barang_kode.*' => 'required|exists:barangs,kode_barang',
            'jumlah.*' => 'required|integer|min:1',
            'harga_jual_snapshot.*' => 'required|numeric|min:0',
            'bayar_nominal' => 'nullable|string', // ribuan (ex: "1.000.000")
            'metode' => 'nullable|string|max:50',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Hitung total penjualan
        $total = 0;
        foreach ($request->barang_kode as $i => $kode) {
            $total += $request->jumlah[$i] * $request->harga_jual_snapshot[$i];
        }

        // Bersihkan bayar_nominal (dari format ribuan)
        $bayarNominal = str_replace('.', '', $request->bayar_nominal ?? 0);
        $bayarNominal = intval($bayarNominal);

        // Cegah jika pembayaran melebihi total
        if ($bayarNominal > $total) {
            return back()->withInput()->with('error', 'Nominal pembayaran tidak boleh melebihi total harga.');
        }

        //  status otomatis
        $status = 'belum lunas';
        if ($bayarNominal >= $total && $total > 0) {
            $status = 'lunas';
        }

        // Cek stok semua barang terlebih dahulu
        foreach ($request->barang_kode as $i => $kode) {
            $barang = Barang::where('kode_barang', $kode)->first();
            if (!$barang) {
                return back()->with('error', "Barang dengan kode {$kode} tidak ditemukan.");
            }

            if ($barang->stok < $request->jumlah[$i]) {
                return back()->with('error', "Stok barang {$barang->nama} tidak mencukupi.");
            }
        }

        // Simpan transaksi penjualan
        $penjualan = Penjualan::create([
            'kode_transaksi' => 'PJ-' . strtoupper(Str::random(6)),
            'pelanggan_id' => $request->pelanggan_id,
            'user_id' => Auth::id(),
            'created_by' => Auth::id(),
            'tanggal' => $request->tanggal,
            'total_harga' => $total,
            'jenis_pembayaran' => $request->jenis_pembayaran,
            'status_transaksi' => $status,
            'keterangan' => $request->keterangan,
            'sales_id' => $request->sales_id ?? null,
        ]);

        // Simpan detail penjualan dan kurangi stok
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

        // Simpan pembayaran awal jika ada
        if ($bayarNominal > 0) {
            $path = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $filename = time() . '_' . $request->file('bukti_pembayaran')->getClientOriginalName();
                $destination = public_path('storage/bukti_pembayaran');

                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }

                $request->file('bukti_pembayaran')->move($destination, $filename);
                $path = 'bukti_pembayaran/' . $filename;
            }

            PembayaranPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'tanggal' => $request->tanggal,
                'nominal' => $bayarNominal,
                'metode' => $request->metode ?? 'Tunai',
                'bukti_pembayaran' => $path,
                'keterangan' => 'Pembayaran awal dari form penjualan',
            ]);
        }

        return redirect()->route('penjualan.index')->with([
            'success' => 'Data berhasil disimpan!',
            'cetak_id' => $penjualan->id
        ]);
    }



    public function show(Penjualan $penjualan)
    {
        $penjualan->load(['pelanggan', 'detailPenjualans']);
        return view('penjualan.show', compact('penjualan'));
    }

    public function cetakNota($id)
    {
        $penjualan = Penjualan::with([
            'pelanggan',
            'sales',
            'detailPenjualan.barang',
            'pembayaranPenjualans'
        ])->findOrFail($id);

        return view('penjualan.cetak', compact('penjualan'));
    }

    public function destroy(Penjualan $penjualan)
    {
        // Cek apakah penjualan sudah punya pembayaran
        if ($penjualan->pembayaranPenjualans()->count() > 0) {
            return redirect()->route('penjualan.index')->with('error', 'Transaksi tidak dapat dihapus karena sudah memiliki riwayat pembayaran.');
        }

        // Kembalikan stok barang (restitusi)
        foreach ($penjualan->detailPenjualan as $detail) {
            $barang = $detail->barang;
            if ($barang) {
                $barang->increment('stok', $detail->jumlah);
            }
        }

        // Hapus detail penjualan
        $penjualan->detailPenjualan()->delete();

        // Hapus penjualan
        $penjualan->delete();

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil dihapus.');
    }
}
