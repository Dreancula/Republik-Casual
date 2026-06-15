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
        Schema::create('status_pesanan', function (Blueprint $table) {
            $table->id('id_status');
            $table->foreignId('id_kurir')->constrained('kurir', 'id_kurir');
            $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan');
            $table->foreignId('id_pengiriman')->constrained('pengiriman_produk', 'id_pengiriman');
            $table->foreignId('id_user')->constrained('user', 'id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_pesanan');
    }
};
