<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\DetailPenjualanController;
use App\Http\Controllers\PembayaranPenjualanController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\DetailPembelianController;
use App\Http\Controllers\LaporanPembelianController;
use App\Http\Controllers\LaporanPenjualanController;

// ============================
// AUTHENTIKASI (GUEST ONLY)
// ============================
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ============================
// LOGOUT (AUTH REQUIRED)
// ============================
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ============================
// ROUTE SETELAH LOGIN
// ============================
Route::middleware('auth')->group(function () {

    // === ROUTES ALL ROLE ===
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
    Route::middleware('role:sales,admin,superadmin')->get('/barang', [BarangController::class, 'index'])->name('barang.index');

    // === SUPERADMIN ONLY ===
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('user', UserController::class);

        // === Laporan Pembelian ===
        Route::prefix('laporan/pembelian')->name('laporan.pembelian.')->group(function () {
            Route::get('/index', [LaporanPembelianController::class, 'index'])->name('index');
            Route::get('/{id}', [LaporanPembelianController::class, 'show'])->name('show');
            Route::get('/export/pdf', [LaporanPembelianController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/{id}/pdf', [LaporanPembelianController::class, 'exportDetailPdf'])->name('export.detail.pdf');
            Route::get('/export/excel', [LaporanPembelianController::class, 'exportExcel'])->name('export.excel');
            Route::get('/{id}/export-excel', [LaporanPembelianController::class, 'exportDetailExcel'])->name('export.detail.excel');
        });

        // === Laporan Penjualan ===
        Route::prefix('laporan/penjualan')->name('laporan.penjualan.')->group(function () {
            Route::get('/index', [LaporanPenjualanController::class, 'index'])->name('index');
            Route::get('/{id}', [LaporanPenjualanController::class, 'show'])->name('show');
            Route::get('/export/pdf', [LaporanPenjualanController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/{id}/pdf', [LaporanPenjualanController::class, 'exportDetailPdf'])->name('export.detail.pdf');
            Route::get('/export/excel', [LaporanPenjualanController::class, 'exportExcel'])->name('export.excel');
            Route::get('/{id}/excel', [LaporanPenjualanController::class, 'exportDetailExcel'])->name('export.detail.excel');
        });
    });

    // === SUPERADMIN & ADMIN ===
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
        Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
        Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
        Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
        Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
        Route::get('/barang/search', [BarangController::class, 'search'])->name('barang.search');

        Route::resource('kategori', KategoriController::class);
        Route::resource('supplier', SupplierController::class);
        Route::resource('pembelian', PembelianController::class);
        Route::resource('detail-pembelian', DetailPembelianController::class);

        Route::resource('pelanggan', PelangganController::class);
        Route::resource('penjualan', PenjualanController::class);
        Route::resource('detail-penjualan', DetailPenjualanController::class);
        Route::resource('pembayaran_penjualan', PembayaranPenjualanController::class);

        Route::get('/penjualan/cari-transaksi-belum-lunas', [PembayaranPenjualanController::class, 'cariTransaksiBelumLunas'])
            ->name('penjualan.search_transaksi_belum_lunas');
    });
});
