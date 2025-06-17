<?php 

use Illuminate\Support\Facades\Route;

// Import semua controller
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

/*
|--------------------------------------------------------------------------
| API Routes (Tanpa Middleware Auth)
|--------------------------------------------------------------------------
*/

Route::get('/ping', function () {
    return response()->json(['message' => 'API aktif']);
});

// CRUD API routes
Route::apiResource('users', UserController::class);
Route::apiResource('pelanggan', PelangganController::class);
Route::apiResource('supplier', SupplierController::class);
Route::apiResource('barang', BarangController::class);
Route::apiResource('kategori', KategoriController::class);
Route::apiResource('penjualan', PenjualanController::class);
Route::apiResource('detail-penjualan', DetailPenjualanController::class);
Route::apiResource('pembayaran-penjualan', PembayaranPenjualanController::class);
Route::apiResource('pembelian', PembelianController::class);
Route::apiResource('detail-pembelian', DetailPembelianController::class);

?>