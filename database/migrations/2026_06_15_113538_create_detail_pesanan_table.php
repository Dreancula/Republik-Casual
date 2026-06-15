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
        // NOTE: Sesuai gambar lu, detail pesanan berelasi ke pengiriman & pembayaran.
        // Nanti pas implementasi jangan kaget kalau susah nyari 'produk apa aja yang dibeli' ya bro.
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('id_detail_pesanan');
            $table->foreignId('id_pengiriman')->constrained('pengiriman_produk', 'id_pengiriman');
            $table->foreignId('id_pembayaran')->constrained('pembayaran', 'id_pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
