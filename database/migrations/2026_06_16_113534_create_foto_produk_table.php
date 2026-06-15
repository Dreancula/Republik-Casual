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
        Schema::create('foto_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produk')->constrained('produk', 'id_produk')->onDelete('cascade');
            $table->string('foto');
            $table->timestamps();
            // $table->unsignedBigInteger('id_produk');
            // $table->foreignId('id_produk')->references('id')->on('produk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_produk');
    }
};
