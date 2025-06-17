<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            ['kode_barang' => 'BRG001', 'nama' => 'Lampu LED 12W'],
            ['kode_barang' => 'BRG002', 'nama' => 'Stop Kontak 3 Lubang'],
            ['kode_barang' => 'BRG003', 'nama' => 'Kabel NYA 1.5mm'],
            ['kode_barang' => 'BRG004', 'nama' => 'Pipa PVC 1 Inch'],
            ['kode_barang' => 'BRG005', 'nama' => 'Saklar Tunggal'],
            ['kode_barang' => 'BRG006', 'nama' => 'Kabel Roll 10 Meter'],
            ['kode_barang' => 'BRG007', 'nama' => 'Terminal Listrik'],
            ['kode_barang' => 'BRG008', 'nama' => 'Adaptor 12V'],
            ['kode_barang' => 'BRG009', 'nama' => 'Obeng Listrik'],
            ['kode_barang' => 'BRG010', 'nama' => 'Fuse 10A']
        ];

        foreach ($barangs as $index => $barang) {
            Barang::create([
                'kode_barang' => $barang['kode_barang'],
                'nama' => $barang['nama'],
                'kategori_id' => rand(1, 10),
                'satuan' => 'pcs',
                'stok' => rand(10, 100),
                'harga_beli' => rand(5000, 15000),
                'harga_jual' => rand(15000, 30000),
            ]);
        }
    }
}
