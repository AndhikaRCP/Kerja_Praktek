<?php
// app/Http/Controllers/BarangController.php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('kategori')->get();
        $kategoris = Kategori::all();
        $role = Auth::user()->role;
        return view('barang.index', compact('barangs', 'kategoris', 'role'));
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

        if ($request->harga_jual <= $request->harga_beli) {
            return redirect()->back()->withInput()->withErrors([
                'harga_jual' => 'Harga jual harus lebih besar dari harga beli.',
            ]);
        }

        Barang::create($request->all());
        return redirect()->back()->with('success', 'Data berhasil ditambahkan.');
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

    public function search(Request $request)
    {
        $search = $request->q;

        $barangs = Barang::where('kode_barang', 'like', "%{$search}%")
            ->orWhere('nama', 'like', "%{$search}%")
            ->select('kode_barang', 'nama', 'harga_beli')
            ->limit(10)
            ->get();

        $results = [];
        foreach ($barangs as $barang) {
            $results[] = [
                'id' => $barang->kode_barang,
                'text' => "{$barang->kode_barang} - {$barang->nama}",
                'nama' => $barang->nama,
                'harga_beli' => $barang->harga_beli,
            ];
        }

        return response()->json(['results' => $results]);
    }
}
