<?php

// app/Http/Controllers/PelangganController.php
namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index() {
        $pelanggans = Pelanggan::paginate(10);
        return view('pelanggans.index', compact('pelanggans'));
    }

    public function create() {
        return view('pelanggans.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'telepon' => 'required|string',
        ]);

        Pelanggan::create($request->all());
        return redirect()->route('pelanggans.index')->with('success', 'Pelanggan ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan) {
        return view('pelanggans.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan) {
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'telepon' => 'required|string',
        ]);

        $pelanggan->update($request->all());
        return redirect()->route('pelanggans.index')->with('success', 'Pelanggan diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan) {
        $pelanggan->delete();
        return redirect()->route('pelanggans.index')->with('success', 'Pelanggan dihapus.');
    }
}
