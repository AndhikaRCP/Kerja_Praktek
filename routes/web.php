<?php

use Illuminate\Support\Facades\Route;

// Controller import
use App\Http\Controllers\UserController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\DetailPenjualanController;
use App\Http\Controllers\PembayaranPenjualanController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\DetailPembelianController;
use App\Http\Controllers\LaporanPembelianController;
use App\Http\Controllers\LaporanPenjualanController;

Route::get('/', function () {
    return view('auth.login');
});


// Route resource (CRUD) untuk semua tabel
Route::resource('user', UserController::class);
Route::resource('pelanggan', PelangganController::class);
Route::resource('supplier', SupplierController::class);
Route::resource('barang', BarangController::class);
Route::resource('kategori', KategoriController::class);
Route::resource('penjualan', PenjualanController::class);
Route::resource('detail-penjualan', DetailPenjualanController::class);
Route::resource('pembayaran_penjualan', PembayaranPenjualanController::class);
Route::resource('pembelian', PembelianController::class);
Route::resource('detail-pembelian', DetailPembelianController::class);
Route::resource('laporan_pembelian', LaporanPembelianController::class);
Route::resource('laporan_pembelian', LaporanPenjualanController::class);

Route::get('/dashboard', function () {
    return view('dashboard.admin');
})->name('dashboard.admin');

Route::get('/laporan/pembelian/index', [LaporanPembelianController::class, 'index'])->name('laporan.pembelian.index');
Route::get('/laporan/pembelian/{id}', [LaporanPembelianController::class, 'show'])->name('laporan.pembelian.show');

Route::get('/laporan/penjualan/index', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan');

