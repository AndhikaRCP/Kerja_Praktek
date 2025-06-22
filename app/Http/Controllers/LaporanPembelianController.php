<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;


class LaporanPembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with(['supplier', 'user']);

        // Filter tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }

        // Filter supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter status transaksi
        if ($request->filled('status_transaksi')) {
            $query->where('status_transaksi', $request->status_transaksi);
        }

        // Ambil data yang sudah difilter
        $pembelians = $query->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();
        $total_pembelian = $query->sum('total_harga');

        // Ambil semua supplier untuk dropdown filter
        $suppliers = Supplier::orderBy('nama')->get();

        return view('laporan.pembelian.index', compact(
            'pembelians',
            'total_pembelian',
            'suppliers'
        ));
    }

    public function show($id)
    {
        $pembelian = Pembelian::with(['supplier', 'user', 'detailPembelian'])->findOrFail($id);
        return view('laporan.pembelian.show', compact('pembelian'));
    }

    public function exportPdf(Request $request)
    {
        $query = Pembelian::with(['supplier', 'user']);

        // Filter sama seperti halaman view
        if ($request->tanggal_mulai && $request->tanggal_akhir) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }

        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->status_transaksi) {
            $query->where('status_transaksi', $request->status_transaksi);
        }

        $pembelians = $query->orderBy('tanggal', 'desc')->get();
        $total_pembelian = $pembelians->sum('total_harga');

        $pdf = Pdf::loadView('laporan.pembelian.pdf', compact('pembelians', 'total_pembelian'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pembelian.pdf');
    }

    public function exportDetailPdf($id)
    {
        $pembelian = Pembelian::with(['supplier', 'user', 'detailPembelian.barang.kategori'])->findOrFail($id);

        $pdf = Pdf::loadView('laporan.pembelian.detail_pdf', compact('pembelian'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('Detail-Pembelian-' . $pembelian->kode_transaksi . '.pdf');
    }


    public function exportDetailExcel($id)
    {
        $pembelian = Pembelian::with(['supplier', 'user', 'detailPembelian.barang.kategori'])->findOrFail($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul
        $sheet->setCellValue('A1', 'Detail Pembelian - ' . $pembelian->kode_transaksi);
        $sheet->setCellValue('A2', 'Tanggal: ' . $pembelian->tanggal);
        $sheet->setCellValue('A3', 'Supplier: ' . $pembelian->supplier->nama);
        $sheet->setCellValue('A4', 'User Input: ' . $pembelian->user->name);
        $sheet->setCellValue('A5', 'Status: ' . ucfirst($pembelian->status_transaksi));
        $sheet->setCellValue('A6', 'Keterangan: ' . $pembelian->keterangan);

        // Header tabel mulai dari A8
        $sheet->fromArray([
            ['No', 'Kode Barang', 'Nama', 'Kategori', 'Satuan', 'Harga Beli', 'Jumlah', 'Subtotal']
        ], NULL, 'A8');

        // Data baris
        $start = 9;
        foreach ($pembelian->detailPembelian as $i => $detail) {
            $sheet->fromArray([
                $i + 1,
                $detail->barang_kode,
                $detail->nama_barang_snapshot,
                $detail->barang->kategori->nama_kategori ?? '-',
                $detail->barang->satuan ?? '-',
                $detail->harga_beli_snapshot,
                $detail->jumlah,
                $detail->harga_beli_snapshot * $detail->jumlah
            ], NULL, 'A' . ($start + $i));
        }

        // Kirim file
        $filename = 'Detail_Pembelian_' . $pembelian->kode_transaksi . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }

    public function exportExcel(Request $request)
    {
        $query = Pembelian::with(['supplier', 'user'])->orderBy('tanggal', 'desc');

        if ($request->tanggal_mulai && $request->tanggal_akhir) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }

        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->status_transaksi) {
            $query->where('status_transaksi', $request->status_transaksi);
        }

        $pembelians = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Transaksi');
        $sheet->setCellValue('C1', 'Tanggal');
        $sheet->setCellValue('D1', 'Supplier');
        $sheet->setCellValue('E1', 'User Input');
        $sheet->setCellValue('F1', 'Total Harga');
        $sheet->setCellValue('G1', 'Status');
        $sheet->setCellValue('H1', 'Keterangan');

        // Isi data
        $row = 2;
        foreach ($pembelians as $i => $pembelian) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $pembelian->kode_transaksi);
            $sheet->setCellValue('C' . $row, \Carbon\Carbon::parse($pembelian->tanggal)->format('d-m-Y'));
            $sheet->setCellValue('D' . $row, $pembelian->supplier->nama ?? '-');
            $sheet->setCellValue('E' . $row, $pembelian->user->name ?? '-');
            $sheet->setCellValue('F' . $row, $pembelian->total_harga);
            $sheet->setCellValue('G' . $row, ucfirst($pembelian->status_transaksi));
            $sheet->setCellValue('H' . $row, $pembelian->keterangan ?? '-');
            $row++;
        }

        // Total di akhir
        $totalHarga = $pembelians->sum('total_harga');
        $sheet->setCellValue('E' . $row, 'Total:');
        $sheet->setCellValue('F' . $row, $totalHarga);

        // Export
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_pembelian_' . now()->format('Ymd_His') . '.xlsx';

        // Simpan ke penyimpanan sementara
        $tempFile = storage_path($filename);
        $writer->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
}
