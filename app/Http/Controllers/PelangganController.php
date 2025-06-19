<?php

// app/Http/Controllers/PelangganController.php
namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index() {
        $pelanggan = Pelanggan::paginate(10);
        return view('pelanggan.index', compact('pelanggan'));
    }

    public function create() {
        return view('pelanggan.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'telepon' => 'required|string',
        ]);

        Pelanggan::create($request->all());
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan) {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan) {
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'telepon' => 'required|string',
        ]);

        $pelanggan->update($request->all());
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan) {
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan dihapus.');
    }
}
