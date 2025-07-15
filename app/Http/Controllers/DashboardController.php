<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $role = Auth::user()->role;

        return match ($role) {
            'superadmin' => $this->superadminDashboard(),
            'admin' => $this->adminDashboard(),
            'sales' => $this->salesDashboard($request),
            default => abort(403, 'Akses tidak diizinkan'),
        };
    }


   public function superadminDashboard()
{
    $totalUsers = User::count();
    $totalBarangs = Barang::count();
    $totalPenjualans = Penjualan::count();
    $totalPembelians = Pembelian::count();
    
    $startDate = Carbon::now()->subDays(29)->startOfDay();

    // Data dari DB yg sudah dijumlah per tgl
    $dataFromDB = Penjualan::selectRaw('DATE(tanggal) as tanggal, SUM(total_harga) as total')
        ->where('tanggal', '>=', $startDate)
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'asc')
        ->get()
        ->keyBy('tanggal');

    // Generate semua tanggal dari 30 hari terakhir
    $penjualanChart = [];
    for ($i = 0; $i < 30; $i++) {
        $date = Carbon::now()->subDays(29 - $i)->toDateString();
        $penjualanChart[] = [
            'tanggal' => Carbon::parse($date)->format('d M'),
            'total' => isset($dataFromDB[$date]) ? (int)$dataFromDB[$date]->total : 0,
        ];
    }

    // Top 5 barang terlaris
    $topBarangs = DB::table('detail_penjualans')
        ->select('nama_barang_snapshot', DB::raw('SUM(jumlah) as jumlah'))
        ->groupBy('nama_barang_snapshot')
        ->orderByDesc('jumlah')
        ->limit(5)
        ->get();

    // Jumlah user per role
    $userPerRole = [
        'superadmin' => User::where('role', 'superadmin')->count(),
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

         return view('dashboard.admin', [
        'totalBarangs' => Barang::count(),
        'totalPenjualans' => Penjualan::count(),
        'totalKategoris' => Kategori::count(),
        'totalSuppliers' => Supplier::count(),
        'totalPelanggans' => Pelanggan::count(),
        'penjualanTerbaru' => Penjualan::with('pelanggan')->latest()->take(5)->get(),
    ]);

    }


public function salesDashboard(Request $request)
{
    $salesId = Auth::user()->id;

    $from = $request->input('from') ?? now()->subDays(30)->toDateString();
    $to = $request->input('to') ?? now()->toDateString();

    $days = Carbon::parse($from)->diffInDays(Carbon::parse($to)) + 1;

    $penjualans = \App\Models\Penjualan::where('sales_id', $salesId)
        ->whereBetween('tanggal', [$from, $to])
        ->get();

    $totalPenjualans = $penjualans->count();
    $omset = $penjualans->sum('total_harga');

    return view('dashboard.sales', compact(
        'totalPenjualans', 'omset', 'days'
    ));
}

    public function redirect(Request $request)
    {
        $role = Auth::user()->role;

        return match ($role) {
            'superadmin' => $this->superadminDashboard(),
            'admin' => $this->adminDashboard(),
            'sales' => $this->salesDashboard($request),
            default => abort(403, 'Akses tidak diizinkan'),
        };
    }
}
