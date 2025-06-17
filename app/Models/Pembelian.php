<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function detailPembelians() {
        return $this->hasMany(DetailPembelian::class);
    }

}
