<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Pelanggan::create([
                'nama' => "Toko Elektronik $i",
                'alamat' => "Jl. Sudirman No.$i, Palembang",
                'kota' => "Palembang",
                'telepon' => "083199881234$i"
            ]);
        }
    }
}
