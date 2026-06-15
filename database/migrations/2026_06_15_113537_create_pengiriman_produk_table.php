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
        Schema::create('pengiriman_produk', function (Blueprint $table) {
            $table->id('id_pengiriman');
            $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan');
            $table->foreignId('id_kurir')->constrained('kurir', 'id_kurir');
            $table->integer('no_resi');
            $table->date('tgl_pengiriman');
            $table->enum('status_pengiriman', ['PROSES', 'MENUJU ALAMAT', 'TELAH TIBA']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_produk');
    }
};
