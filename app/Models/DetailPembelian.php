<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    public function pembelian() {
    return $this->belongsTo(Pembelian::class);
    }

    public function barang() {
        return $this->belongsTo(Barang::class, 'barang_kode', 'kode_barang');
    }

}
