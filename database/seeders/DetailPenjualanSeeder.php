<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\Barang;

class DetailPenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $barangList = Barang::all();

        foreach (Penjualan::all() as $penjualan) {
            for ($i = 1; $i <= rand(1, 3); $i++) {
                $barang = $barangList->random();

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'kode_barang' => $barang->kode_barang,
                    'nama_barang_snapshot' => $barang->nama,
                    'harga_jual_snapshot' => $barang->harga_jual,
                    'jumlah' => rand(1, 10),
                ]);
            }
        }
    }
}
