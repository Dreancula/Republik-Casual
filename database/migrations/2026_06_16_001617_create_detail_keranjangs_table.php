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
        Schema::create('detail_keranjang', function (Blueprint $table) {
            $table->id('id_detail_keranjang');

            $table->unsignedBigInteger('id_keranjang');
            $table->unsignedBigInteger('id_produk');

            $table->integer('quantity');

            $table->decimal('sub_total', 12, 2);

            $table->timestamps();

            $table->foreign('id_keranjang')
                ->references('id_keranjang')
                ->on('keranjang')
                ->cascadeOnDelete();

            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('produk')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_keranjangs');
    }
};
