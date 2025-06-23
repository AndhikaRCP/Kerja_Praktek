<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\Pembelian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{

    public function index()
    {
        $role = Auth::user()->role;

        return match ($role) {
            'superadmin' => $this->superadminDashboard(),
            'admin' => $this->adminDashboard(),
            'sales' => $this->salesDashboard(),
            default => abort(403, 'Akses tidak diizinkan'),
        };
    }


    public function superadminDashboard()
    {
        $totalUsers = User::count();
        $totalBarangs = Barang::count();
        $totalPenjualans = Penjualan::count();
        $totalPembelians = Pembelian::count();

        // Ubah jadi array biasa agar bisa pakai array_column di view
        $penjualanChart = Penjualan::selectRaw('DATE(tanggal) as tanggal, SUM(total_harga) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->take(7)
            ->get()
            ->reverse()
            ->values()
            ->toArray(); // <-- ini penting!

        $topBarangs = DB::table('detail_penjualans')
            ->select('nama_barang_snapshot', DB::raw('SUM(jumlah) as jumlah'))
            ->groupBy('nama_barang_snapshot')
            ->orderByDesc('jumlah')
            ->limit(5)
            ->get();

        $userPerRole = [
            'manager' => User::where('role', 'manager')->count(),
            'admin' => User::where('role', 'admin')->count(),
            'sales' => User::where('role', 'sales')->count(),
        ];

        return view('dashboard.superadmin', compact(
            'totalUsers',
            'totalBarangs',
            'totalPenjualans',
            'totalPembelians',
            'penjualanChart',
            'topBarangs',
            'userPerRole'
        ));
    }

    public function adminDashboard()
    {
        $totalBarangs = Barang::count();
        $totalPenjualans = Penjualan::count();
        $totalPembelians = Pembelian::count();

        return view('dashboard.admin', compact('totalBarangs', 'totalPenjualans', 'totalPembelians'));
    }

    public function salesDashboard()
    {
        $totalPenjualans = Penjualan::where('sales_id', Auth::id())->count();

        return view('dashboard.sales', compact('totalPenjualans'));
    }


    public function redirect()
    {
        $role = Auth::user()->role;

        return match ($role) {
            'superadmin' => $this->superadminDashboard(),
            'admin' => $this->adminDashboard(),
            'sales' => $this->salesDashboard(),
            default => abort(403, 'Akses tidak diizinkan'),
        };
    }
}
