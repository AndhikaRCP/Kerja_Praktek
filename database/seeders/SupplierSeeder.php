<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Supplier::create([
                'nama' => "Toko Supplier $i",
                'alamat' => "Jl. Supplier Raya No. $i, Jakarta",
                'kota' => "Jakarta",
                'telepon' => "082299881234$i"
            ]);
        }
    }
}
