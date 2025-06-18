<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $guarded = [];
    
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    /**
     * Relasi ke barang asli berdasarkan kode_barang
     * Note: ini hanya untuk referensi, karena nama & harga disimpan via snapshot
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }
}
