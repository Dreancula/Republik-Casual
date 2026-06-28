<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_komplain', function (Blueprint $table) {
            $table->id('id_detail_komplain');
            $table->unsignedBigInteger('id_komplain');
            $table->unsignedBigInteger('id_produk')->nullable();
            $table->integer('qty')->default(1);
            $table->text('foto')->nullable();
            $table->timestamps();

            $table->foreign('id_komplain')->references('id_komplain')->on('komplain')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_komplain');
    }
};
