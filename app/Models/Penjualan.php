<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    public function pelanggan() {
        return $this->belongsTo(Pelanggan::class);
    }

    public function detailPenjualans() {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(PembayaranPenjualan::class);
    }

}
