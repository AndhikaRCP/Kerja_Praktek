<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KategoriSeeder::class,
            BarangSeeder::class,
            PelangganSeeder::class,
            SupplierSeeder::class,
            PembelianSeeder::class,
            DetailPembelianSeeder::class,
            PenjualanSeeder::class,
            DetailPenjualanSeeder::class,
            PembayaranPenjualanSeeder::class,
        ]);
    }
}
