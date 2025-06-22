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

// LAPORAN PEMBELIAN
Route::get('/laporan/pembelian/index', [LaporanPembelianController::class, 'index'])->name('laporan.pembelian.index');
Route::get('/laporan/pembelian/{id}', [LaporanPembelianController::class, 'show'])->name('laporan.pembelian.show');
Route::get('/laporan/pembelian/export/pdf', [LaporanPembelianController::class, 'exportPdf'])
    ->name('laporan.pembelian.export.pdf');
Route::get('/laporan/pembelian/{id}/pdf', [LaporanPembelianController::class, 'exportDetailPdf'])->name('laporan.pembelian.export.detail.pdf');

Route::get('/laporan/pembelian/export/excel', [LaporanPembelianController::class, 'exportExcel'])
    ->name('laporan.pembelian.export.excel');
Route::get('/laporan/pembelian/{id}/export-excel', [LaporanPembelianController::class, 'exportDetailExcel'])
    ->name('laporan.pembelian.export.detail.excel');

// LAPORAN PENJUALAN
Route::get('/laporan/penjualan/index', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan.index');

Route::get('/laporan/penjualan/export/pdf', [LaporanPenjualanController::class, 'exportPdf'])
    ->name('laporan.penjualan.export.pdf');
Route::get('/laporan/penjualan/{id}/pdf', [LaporanPenjualanController::class, 'exportDetailPdf'])->name('laporan.penjualan.export.detail.pdf');
Route::get('/laporan/penjualan/{id}', [LaporanPenjualanController::class, 'show'])->name('laporan.penjualan.show');
Route::get('/laporan/penjualan/export/excel', [LaporanPenjualanController::class, 'exportExcel'])
    ->name('laporan.penjualan.export.excel');
