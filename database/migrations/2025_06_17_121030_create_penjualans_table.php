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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 50)->unique();
            $table->foreignId('pelanggan_id')->constrained('pelanggans');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('sales_id')->nullable()->constrained('users');
            $table->date('tanggal');
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->enum('status_pembayaran', ['tunai', 'kredit', 'belum lunas']);
            $table->enum('status_transaksi', ['selesai', 'batal'])->default('selesai');
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
