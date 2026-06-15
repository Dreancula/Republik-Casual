<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('order');
            $table->foreignId('produk_id')->constrained('produk', 'id_produk');
            $table->integer('quantity')->default(1);
            $table->decimal('harga', 10, 0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item');
    }
};
