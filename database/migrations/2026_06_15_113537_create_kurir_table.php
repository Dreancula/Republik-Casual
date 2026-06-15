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
        Schema::create('kurir', function (Blueprint $table) {
            $table->id('id_kurir');
            // Di gambar ada id_pengiriman di tabel kurir, padahal harusnya sebaliknya. Gua tulis sesuai gambar lu.
            // $table->integer('id_pengiriman');
            $table->string('nama', 100);
            $table->enum('nama_ekspedisi', ['J&T', 'JNE', 'SICEPAT']);
            $table->string('no_telp_kurir', 13);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurir');
    }
};
