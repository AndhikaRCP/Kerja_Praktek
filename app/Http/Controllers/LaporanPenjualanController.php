<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penjualan::with(['pelanggan', 'user', 'sales']);
        $pelanggans = Pelanggan::orderBy('nama')->get();

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }

        $penjualans = $query->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();
        $total_penjualan = (clone $query)->sum('total_harga');

        return view('laporan.penjualan.index', compact('penjualans', 'total_penjualan', 'pelanggans'));
    }


    public function show($id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'user', 'sales', 'detailPenjualan'])->findOrFail($id);

        return view('laporan.penjualan.show', compact('penjualan'));
    }

    public function exportPdf(Request $request)
    {
        $query = Penjualan::with(['pelanggan', 'user', 'sales']);

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }

        $penjualans = $query->orderBy('tanggal', 'desc')->get();
        $total_penjualan = $penjualans->sum('total_harga');

        $pdf = Pdf::loadView('laporan.penjualan.pdf', compact('penjualans', 'total_penjualan'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penjualan.pdf');
    }

    public function exportDetailPdf($id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'user', 'detailPenjualan.barang'])->findOrFail($id);
        $pdf = Pdf::loadView('laporan.penjualan.detail_pdf', compact('penjualan'));
        return $pdf->download('detail-penjualan-' . $penjualan->kode_transaksi . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = Penjualan::with(['pelanggan', 'user', 'sales']);

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }

        $penjualans = $query->orderBy('tanggal', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->fromArray([
            ['No', 'Kode Transaksi', 'Tanggal', 'Pelanggan', 'Sales', 'User Input', 'Total Harga', 'Status Pembayaran', 'Status Transaksi', 'Keterangan']
        ], NULL, 'A1');

        // Data
        $row = 2;
        foreach ($penjualans as $i => $penjualan) {
            $sheet->fromArray([
                $i + 1,
                $penjualan->kode_transaksi,
                $penjualan->tanggal,
                $penjualan->pelanggan->nama ?? '-',
                $penjualan->sales->name ?? '-',
                $penjualan->user->name ?? '-',
                $penjualan->total_harga,
                ucfirst($penjualan->status_pembayaran),
                ucfirst($penjualan->status_transaksi),
                $penjualan->keterangan ?? '-'
            ], NULL, 'A' . $row);
            $row++;
        }

        // Total
        $total = $penjualans->sum('total_harga');
        $sheet->setCellValue('F' . $row, 'Total:');
        $sheet->setCellValue('G' . $row, $total);

        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_penjualan_' . now()->format('Ymd_His') . '.xlsx';
        $tempFile = storage_path($filename);
        $writer->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
}
