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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->foreignId('id_user')->constrained('user', 'id_user');
            $table->foreignId('id_keranjang')->constrained('keranjang', 'id_keranjang');
            $table->date('tgl_pesanan');
            $table->decimal('total_harga', 10, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
