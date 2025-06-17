<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailPembelian;
use App\Models\Pembelian;
use App\Models\Barang;

class DetailPembelianSeeder extends Seeder
{
    public function run(): void
    {
        $barangList = Barang::all();

        foreach (Pembelian::all() as $pembelian) {
            for ($i = 1; $i <= rand(1, 3); $i++) {
                $barang = $barangList->random();

                DetailPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'barang_kode' => $barang->kode_barang,
                    'nama_barang_snapshot' => $barang->nama,
                    'harga_beli_snapshot' => $barang->harga_beli,
                    'jumlah' => rand(5, 20),
                ]);
            }
        }
    }
}
