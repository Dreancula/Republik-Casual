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
        Schema::create('penerimaan_barang', function (Blueprint $table) {
            $table->id('id_pemasukan');
            $table->foreignId('id_user')->constrained('user', 'id_user');
            $table->foreignId('id_brand')->constrained('brand', 'id_brand');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk');
            $table->foreignId('id_kategori')->constrained('kategori', 'id_kategori');
            $table->integer('no_faktur');
            $table->date('tgl_pemasukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan_barang');
    }
};
