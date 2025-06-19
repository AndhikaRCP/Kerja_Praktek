<?php

// app/Http/Controllers/KategoriController.php
namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index() {
        $kategori = Kategori::paginate(10);
        return view('kategori.index', compact('kategori'));
    }

    public function create() {
        return view('kategori.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ]);

        Kategori::create($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori ditambahkan.');
    }

    public function edit(Kategori $kategori) {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori) {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ]);

        $kategori->update($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Kategori $kategori) {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori dihapus.');
    }
}
