<?php
// app/Http/Controllers/BarangController.php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('kategori')->get();
        $kategoris = Kategori::all();
        return view('barang.index', compact('barangs', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'kode_barang' => 'required|unique:barangs',
                'nama' => 'required|string',
                'kategori_id' => 'required|exists:kategoris,id',
                'satuan' => 'required',
                'stok' => 'integer|min:0',
                'harga_beli' => 'required|numeric|min:0',
                'harga_jual' => 'required|numeric|min:0',
            ],
            [
                'kode_barang.required' => 'Kode barang wajib diisi.',
                'kode_barang.unique' => 'Kode barang sudah digunakan.',
                'nama.required' => 'Nama barang tidak boleh kosong.',
                'kategori_id.required' => 'Kategori harus dipilih.',
                'satuan.required' => 'Satuan wajib diisi.',
                'stok.required' => 'Stok wajib diisi.',
                'stok.integer' => 'Stok harus berupa angka.',
                'harga_beli.required' => 'Harga beli wajib diisi.',
                'harga_jual.required' => 'Harga jual wajib diisi.',
            ]
        );

        Barang::create($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategoris,id',
            'satuan' => 'required|string|max:20',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $barang->update($request->except('kode_barang'));

        return redirect()->route('barang.index', $barang->kode_barang)->with('success', 'Barang berhasil diperbarui!');
    }


    public function destroy($kode_barang)
    {
        try {
            Barang::where('kode_barang', $kode_barang)->delete();

            return redirect()->route('barang.index')
                ->with('success', 'Barang berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('barang.index')
                    ->with('warning', 'Barang ini tidak bisa dihapus karena sudah pernah dicatat dalam transaksi.');
            }

            return redirect()->route('barang.index')
                ->with('error', 'Terjadi kesalahan saat menghapus barang.');
        }
    }
}
