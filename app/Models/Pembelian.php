<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetailPembelian;

class Pembelian extends Model
{
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
