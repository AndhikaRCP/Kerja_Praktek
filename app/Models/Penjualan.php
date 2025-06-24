<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $guarded = [];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(PembayaranPenjualan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    // App\Models\Penjualan.php

    public function pembayaranPenjualans()
    {
        return $this->hasMany(PembayaranPenjualan::class);
    }
}
