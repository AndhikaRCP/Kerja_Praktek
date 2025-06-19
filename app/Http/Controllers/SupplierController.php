<?php

// app/Http/Controllers/SupplierController.php
namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index() {
        $supplier = Supplier::paginate(10);
        return view('supplier.index', compact('supplier'));
    }

    public function create() {
        return view('supplier.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'telepon' => 'required|string',
        ]);

        Supplier::create($request->all());
        return redirect()->route('supplier.index')->with('success', 'Supplier ditambahkan.');
    }

    public function edit(Supplier $supplier) {
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier) {
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'telepon' => 'required|string',
        ]);

        $supplier->update($request->all());
        return redirect()->route('supplier.index')->with('success', 'Supplier diperbarui.');
    }

    public function destroy(Supplier $supplier) {
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier dihapus.');
    }
}
