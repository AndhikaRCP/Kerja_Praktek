<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::get();
        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'nama' => 'required|string|max:100|unique:suppliers,nama',
                'alamat' => 'required|string|max:255',
                'kota' => 'required|string|max:100',
                'telepon' => 'required|regex:/^0[0-9]{9,19}$/',
            ],
            [
                'nama.required' => 'Nama supplier wajib diisi.',
                'nama.unique' => 'Nama supplier sudah digunakan.',
                'alamat.required' => 'Alamat wajib diisi.',
                'kota.required' => 'Kota wajib diisi.',
                'telepon.required' => 'Nomor telepon wajib diisi.',
                'telepon.regex' => 'Format nomor telepon tidak valid. Gunakan hanya angka 10-20 digit dan awali dengan angka 0',
            ]
        );

        Supplier::create($request->all());
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate(
            [
                'nama' => 'required|string|max:100|unique:suppliers,nama,' . $supplier->id,
                'alamat' => 'required|string|max:255',
                'kota' => 'required|string|max:100',
                'telepon' => 'required|regex:/^0[0-9]{9,19}$/',
            ],
            [
                'nama.required' => 'Nama supplier wajib diisi.',
                'nama.unique' => 'Nama supplier sudah digunakan.',
                'alamat.required' => 'Alamat wajib diisi.',
                'kota.required' => 'Kota wajib diisi.',
                'telepon.required' => 'Nomor telepon wajib diisi.',
                'telepon.regex' => 'Format nomor telepon tidak valid. Gunakan hanya angka 10-20 digit dan awali dengan angka 0',
            ]
        );

        $supplier->update($request->all());
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus.');
        } catch (QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('supplier.index')
                    ->with('warning', 'Supplier tidak dapat dihapus karena sedang digunakan di data transaksi.');
            }

            return redirect()->route('supplier.index')
                ->with('error', 'Terjadi kesalahan saat menghapus supplier.');
        }
    }
}
