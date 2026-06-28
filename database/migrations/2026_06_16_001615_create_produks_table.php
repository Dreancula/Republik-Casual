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
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');

            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_brand');

            $table->string('nama_produk');
            $table->string('foto_produk')->nullable();

            $table->integer('berat');

            $table->text('deskripsi_produk')->nullable();

            $table->enum('status_produk', [
                'aktif',
                'nonaktif'
            ])->default('aktif');

            $table->integer('stok')->default(0);

            $table->enum('size_produk', [
                'S',
                'M',
                'L'
            ])->default('S');

            $table->decimal('harga', 12, 2);

            $table->timestamps();

            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('kategori')
                ->cascadeOnDelete();

            $table->foreign('id_brand')
                ->references('id_brand')
                ->on('brand')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
