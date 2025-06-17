<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            'Lampu & Bohlam',
            'Kabel Listrik',
            'Stop Kontak & Saklar',
            'Paralon & Pipa PVC',
            'Alat Las & Solder',
            'Konektor & Terminal',
            'Fuse & Sekring',
            'Adaptor & Power Supply',
            'Baut & Mur Elektrik',
            'Peralatan Instalasi Listrik',
        ];

        foreach ($kategoris as $nama) {
            Kategori::create([
                'nama_kategori' => $nama
            ]);
        }
    }
}
