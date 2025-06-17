<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PembayaranPenjualan;
use App\Models\Penjualan;

class PembayaranPenjualanSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Penjualan::all() as $penjualan) {
            $bayar = rand(1, 2);
            for ($i = 1; $i <= $bayar; $i++) {
                PembayaranPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'tanggal' => now()->subDays(rand(0, 10)),
                    'nominal' => $penjualan->total_harga / $bayar,
                    'metode' => ['cash', 'transfer', 'qris'][rand(0, 2)],
                    'bukti_pembayaran' => null,
                    'keterangan' => 'Pembayaran ke-' . $i,
                ]);
            }
        }
    }
}
