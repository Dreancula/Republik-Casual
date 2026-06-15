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
        Schema::create('detail_pemasukan_barang', function (Blueprint $table) {
            $table->id('id_detail_pemasukan');
            $table->foreignId('id_pemasukan')->constrained('penerimaan_barang', 'id_pemasukan');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk');
            $table->integer('jumlah_masuk');
            $table->decimal('harga_beli', 10, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pemasukan_barang');
    }
};
