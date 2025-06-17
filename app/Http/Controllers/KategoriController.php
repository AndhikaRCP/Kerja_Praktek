<?php

// app/Http/Controllers/KategoriController.php
namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index() {
        $kategoris = Kategori::paginate(10);
        return view('kategoris.index', compact('kategoris'));
    }

    public function create() {
        return view('kategoris.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ]);

        Kategori::create($request->all());
        return redirect()->route('kategoris.index')->with('success', 'Kategori ditambahkan.');
    }

    public function edit(Kategori $kategori) {
        return view('kategoris.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori) {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ]);

        $kategori->update($request->all());
        return redirect()->route('kategoris.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Kategori $kategori) {
        $kategori->delete();
        return redirect()->route('kategoris.index')->with('success', 'Kategori dihapus.');
    }
}
