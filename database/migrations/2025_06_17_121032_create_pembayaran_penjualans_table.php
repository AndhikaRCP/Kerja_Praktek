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
        Schema::create('pembayaran_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualans');
            $table->date('tanggal');
            $table->decimal('nominal', 12, 2);
            $table->string('metode', 50);
            $table->string('bukti_pembayaran', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_penjualans');
    }
};
