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
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->foreignId('id_kategori')->constrained('kategori', 'id_kategori');
            $table->foreignId('user_id')->constrained('user', 'id_user');
            $table->string('nama_produk', 100);
            $table->text('detail');
            $table->double('harga');
            $table->enum('size_produk', ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL']);
            $table->integer('stok');
            $table->double('berat', 8, 2);
            $table->foreignId('id_brand')->constrained('brand', 'id_brand');
            $table->string('foto')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
