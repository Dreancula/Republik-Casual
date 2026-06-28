<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengiriman_produk', function (Blueprint $table) {
            $table->id('id_pengiriman');

            $table->string('id_pesanan', 50)->unique();

            $table->string('nama_kurir');
            $table->string('layanan');

            $table->string('no_resi')->nullable();

            $table->dateTime('tgl_pengiriman')->nullable();

            $table->enum('status_pengiriman', [
                'menunggu',
                'dikirim',
                'diterima'
            ])->default('menunggu');

            $table->timestamps();

            $table->foreign('id_pesanan')
                ->references('id_pesanan')
                ->on('pesanan')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_produks');
    }
};
