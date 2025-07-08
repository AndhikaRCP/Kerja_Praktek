<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::get();
        return view('pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'nama' => 'required|string|max:100|unique:pelanggans,nama',
                'alamat' => 'required|string|max:255',
                'kota' => 'required|string|max:100',
                'telepon' => 'required|regex:/^0[0-9]{9,19}$/',
            ],
            [
                'nama.required' => 'Nama pelanggan wajib diisi.',
                'nama.unique' => 'Nama pelanggan sudah digunakan.',
                'alamat.required' => 'Alamat wajib diisi.',
                'kota.required' => 'Kota wajib diisi.',
                'telepon.required' => 'Nomor telepon wajib diisi.',
                'telepon.regex' => 'Format nomor telepon tidak valid. Gunakan hanya angka 10-20 digit dan awali dengan angka 0',
            ]
        );

        Pelanggan::create($request->all());
        return redirect()->back()->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate(
            [
                'nama' => 'required|string|max:100|unique:pelanggans,nama,' . $pelanggan->id,
                'alamat' => 'required|string|max:255',
                'kota' => 'required|string|max:100',
                'telepon' => 'required|regex:/^0[0-9]{9,19}$/',
            ],
            [
                'nama.required' => 'Nama pelanggan wajib diisi.',
                'nama.unique' => 'Nama pelanggan sudah digunakan.',
                'alamat.required' => 'Alamat wajib diisi.',
                'kota.required' => 'Kota wajib diisi.',
                'telepon.required' => 'Nomor telepon wajib diisi.',
                'telepon.regex' => 'Format nomor telepon tidak valid. Gunakan hanya angka 10-20 digit dan awali dengan angka 0',
            ]
        );

        $pelanggan->update($request->all());
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }


    public function destroy(Pelanggan $pelanggan)
    {
        try {
            $pelanggan->delete();
            return redirect()->route('pelanggan.index')
                ->with('success', 'Pelanggan berhasil dihapus.');
        } catch (QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('pelanggan.index')
                    ->with('warning', 'Pelanggan tidak dapat dihapus karena sedang digunakan di data transaksi.');
            }

            return redirect()->route('pelanggan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus pelanggan.');
        }
    }
}
