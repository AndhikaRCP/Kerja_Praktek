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
        Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualans');
            $table->string('kode_barang', 50);
            $table->string('nama_barang_snapshot', 100);
            $table->decimal('harga_jual_snapshot', 12, 2);
            $table->integer('jumlah');
            $table->timestamps();
            $table->foreign('kode_barang')->references('kode_barang')->on('barangs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualans');
    }
};
