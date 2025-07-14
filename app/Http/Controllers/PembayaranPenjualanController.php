<?php

namespace App\Http\Controllers;

use App\Models\PembayaranPenjualan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranPenjualanController extends Controller
{
    // Tampilkan semua data pembayaran
    public function index()
    {
        $pembayaran_penjualans = PembayaranPenjualan::with('penjualan')->latest()->get();

        // Ambil transaksi penjualan yang belum lunas saja untuk dropdown modal
        $penjualans_belum_lunas = Penjualan::with('pelanggan')
            ->where('status_transaksi', '!=', 'lunas')
            ->get();

        foreach ($penjualans_belum_lunas as $p) {
            $p->sisa_tagihan = $p->total_harga - $p->total_pembayaran;
        }

        return view('pembayaran_penjualan.index', [
            'pembayaran_penjualans' => $pembayaran_penjualans,
            'penjualans_belum_lunas' => $penjualans_belum_lunas, // hanya yang belum lunas
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
            'nominal' => 'required|string', // masih dalam bentuk string format Rp
            'metode' => 'nullable|string|max:50',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        // Hapus titik ribuan, lalu konversi ke integer
        $nominal = (int) str_replace('.', '', $request->nominal);

        $penjualan = Penjualan::with('pembayaran')->findOrFail($request->penjualan_id);
        $totalDibayar = $penjualan->pembayaran->sum('nominal');
        $sisaTagihan = $penjualan->total_harga - $totalDibayar;

        if ($nominal > $sisaTagihan) {
            return back()->withErrors([
                'nominal' => 'Nominal melebihi sisa tagihan. Maksimal: Rp ' . number_format($sisaTagihan, 0, ',', '.')
            ])->withInput();
        }

        // Simpan file jika ada
        $path = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $filename = time() . '_' . $request->file('bukti_pembayaran')->getClientOriginalName();

            // **GANTI**: Simpan ke folder real: public/storage/bukti_pembayaran
            $destination = public_path('storage/bukti_pembayaran');

            // **PASTIKAN** foldernya ada
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $request->file('bukti_pembayaran')->move($destination, $filename);

            // **Path disimpan di DB** agar bisa diakses dari browser
            $path = 'bukti_pembayaran/' . $filename;
        }


        // Simpan ke database
        PembayaranPenjualan::create([
            'penjualan_id' => $request->penjualan_id,
            'tanggal' => $request->tanggal,
            'nominal' => $nominal, // sudah bersih dari titik
            'metode' => $request->metode,
            'bukti_pembayaran' => $path,
            'keterangan' => $request->keterangan,
        ]);

        // Update status transaksi
        $totalSetelah = $totalDibayar + $nominal;
        $penjualan->update([
            'status_transaksi' => $totalSetelah >= $penjualan->total_harga ? 'lunas' : 'belum lunas',
        ]);

        return redirect()->route('pembayaran_penjualan.index')->with('success', 'Pembayaran berhasil ditambahkan.');
    }




    // Tampilkan form edit pembayaran
    public function edit($id)
    {
        $pembayaran = PembayaranPenjualan::with('penjualan.pelanggan')->findOrFail($id);

        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Hanya superadmin yang dapat mengedit pembayaran');
        }

        return view('pembayaran_penjualan.edit', compact('pembayaran'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Hanya superadmin yang dapat mengupdate pembayaran');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'nominal' => 'required|string', // misal: "100.000"
            'metode' => 'required|string|max:50',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $pembayaran = PembayaranPenjualan::findOrFail($id);
        $penjualan = $pembayaran->penjualan;

        // Bersihkan nominal dari titik dan ubah jadi integer
        $nominalBaru = (int) str_replace('.', '', $request->nominal);

        // Hitung total pembayaran tanpa nominal yang sedang diedit
        $totalPembayaranSebelum = $penjualan->pembayaran()
            ->where('id', '!=', $pembayaran->id)
            ->sum('nominal');

        $totalSetelahEdit = $totalPembayaranSebelum + $nominalBaru;

        if ($totalSetelahEdit > $penjualan->total_harga) {
            return back()->withErrors([
                'nominal' => 'Total pembayaran melebihi total tagihan. Maksimal: Rp ' . number_format($penjualan->total_harga - $totalPembayaranSebelum, 0, ',', '.')
            ])->withInput();
        }

        // Update file jika diupload
        if ($request->hasFile('bukti_pembayaran')) {
            // Hapus bukti lama kalau ada
            if ($pembayaran->bukti_pembayaran && file_exists(public_path('storage/' . $pembayaran->bukti_pembayaran))) {
                unlink(public_path('storage/' . $pembayaran->bukti_pembayaran));
            }

            // Simpan file baru
            $pathBaru = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            $pembayaran->bukti_pembayaran = $pathBaru;
        }

        // Update data pembayaran
        $pembayaran->update([
            'tanggal' => $request->tanggal,
            'nominal' => $nominalBaru,
            'metode' => $request->metode,
            'keterangan' => $request->keterangan,
        ]);

        // Update status penjualan
        $penjualan->update([
            'status_transaksi' => $totalSetelahEdit >= $penjualan->total_harga ? 'lunas' : 'belum lunas',
        ]);

        return redirect()->route('pembayaran_penjualan.index')->with('success', 'Data pembayaran berhasil diperbarui.');
    }


    // Hapus data pembayaran
    public function destroy(PembayaranPenjualan $pembayaran_penjualan)
    {
        // Cek langsung tanpa function tambahan
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak. Hanya superadmin yang bisa menghapus pembayaran.');
        }

        $penjualan = $pembayaran_penjualan->penjualan;

        // Hapus file bukti jika ada
        if ($pembayaran_penjualan->bukti_pembayaran && file_exists(public_path('storage/' . $pembayaran_penjualan->bukti_pembayaran))) {
            unlink(public_path('storage/' . $pembayaran_penjualan->bukti_pembayaran));
        }

        // Hapus data pembayaran
        $pembayaran_penjualan->delete();

        // Hitung ulang dan update status transaksi
        $totalPembayaran = $penjualan->pembayaran()->sum('nominal');
        $penjualan->update([
            'status_transaksi' => $totalPembayaran >= $penjualan->total_harga ? 'lunas' : 'belum lunas',
        ]);

        return redirect()->back()->with('success', 'Data pembayaran berhasil dihapus.');
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
