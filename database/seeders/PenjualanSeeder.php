<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penjualan;
use App\Models\User;
use App\Models\Pelanggan;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Penjualan::create([
                'kode_transaksi' => "PJ-000$i",
                'pelanggan_id' => rand(1, 10),
                'user_id' => User::where('role', 'admin')->inRandomOrder()->first()->id,
                'sales_id' => User::where('role', 'sales')->inRandomOrder()->first()->id,
                'tanggal' => now()->subDays(rand(1, 30)),
                'total_harga' => rand(100000, 500000),
                'jenis_pembayaran' => ['tunai', 'kredit'][rand(0, 1)],
                'status_transaksi' => ['lunas', 'belum lunas'][rand(0, 1)],
                'keterangan' => 'Penjualan rutin',
                'created_by' => User::where('role', 'admin')->inRandomOrder()->first()->id,
            ]);
        }
    }
}
