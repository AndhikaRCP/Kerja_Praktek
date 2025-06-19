<?php
// app/Http/Controllers/BarangController.php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index() {
        $barang = Barang::with('kategori')->paginate(10);
        return view('barang.index', compact('barang'));
    }

    public function create() {
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    public function store(Request $request) {
        $request->validate([
            'kode_barang' => 'required|unique:barang',
            'nama' => 'required|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'satuan' => 'required',
            'stok' => 'integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        Barang::create($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang) {
        $kategoris = Kategori::all();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

    public function update(Request $request, Barang $barang) {
        $request->validate([
            'nama' => 'required|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'satuan' => 'required',
            'stok' => 'integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $barang->update($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang) {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang dihapus.');
    }
}
