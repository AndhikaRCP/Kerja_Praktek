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

Route::get('/', function () {
    return view('welcome');
});


// Route resource (CRUD) untuk semua tabel
Route::resource('users', UserController::class);
Route::resource('pelanggan', PelangganController::class);
Route::resource('supplier', SupplierController::class);
Route::resource('barang', BarangController::class);
Route::resource('kategori', KategoriController::class);
Route::resource('penjualan', PenjualanController::class);
Route::resource('detail-penjualan', DetailPenjualanController::class);
Route::resource('pembayaran-penjualan', PembayaranPenjualanController::class);
Route::resource('pembelian', PembelianController::class);
Route::resource('detail-pembelian', DetailPembelianController::class);
