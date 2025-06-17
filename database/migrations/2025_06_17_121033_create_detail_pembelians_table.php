<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_pembelians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelians');
            $table->string('barang_kode', 50);
            $table->string('nama_barang_snapshot', 100);
            $table->decimal('harga_beli_snapshot', 12, 2);
            $table->integer('jumlah');
            $table->timestamps();
            $table->foreign('barang_kode')->references('kode_barang')->on('barangs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembelians');
    }
};
