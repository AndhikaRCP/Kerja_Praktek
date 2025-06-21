<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori',
            ],
            [
                'nama_kategori.required' => 'Nama kategori wajib diisi.',
                'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
                'nama_kategori.max' => 'Nama kategori maksimal 100 karakter.',
            ]
        );

        Kategori::create($request->only('nama_kategori'));
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate(
            [
                'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori,' . $kategori->id,
            ],
            [
                'nama_kategori.required' => 'Nama kategori wajib diisi.',
                'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
                'nama_kategori.max' => 'Nama kategori maksimal 100 karakter.',
            ]
        );

        $kategori->update($request->only('nama_kategori'));
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        try {
            $kategori->delete();
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
        } catch (QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('kategori.index')
                    ->with('warning', 'Kategori tidak dapat dihapus karena sedang digunakan di data barang.');
            }

            return redirect()->route('kategori.index')
                ->with('error', 'Terjadi kesalahan saat menghapus kategori.');
        }
    }
}
