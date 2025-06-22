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


    public function exportExcel($id)
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
}
