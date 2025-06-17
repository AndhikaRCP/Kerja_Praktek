<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembelian;
use App\Models\User;
use App\Models\Supplier;

class PembelianSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Pembelian::create([
                'kode_transaksi' => "PB-000$i",
                'supplier_id' => rand(1, 10),
                'user_id' => User::where('role', 'admin')->inRandomOrder()->first()->id,
                'tanggal' => now()->subDays(rand(1, 30)),
                'total_harga' => rand(150000, 700000),
                'status_transaksi' => 'selesai',
                'keterangan' => 'Pembelian rutin',
                'created_by' => User::where('role', 'admin')->inRandomOrder()->first()->id,
            ]);
        }
    }
}
