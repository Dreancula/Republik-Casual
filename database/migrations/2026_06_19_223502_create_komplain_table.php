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
        Schema::create('komplain', function (Blueprint $table) {
            $table->id('id_komplain');

            // Menggunakan string karena id_pesanan di tabel pesanan lu adalah string
            $table->string('id_pesanan');

            // Menggunakan unsignedBigInteger mengikuti standar id user laravel
            $table->unsignedBigInteger('id_user');

            $table->string('jenis_komplain');
            $table->string('foto'); // Untuk menyimpan path/nama file foto bukti

            // Status Approval Komplain
            $table->enum('status_komplain', ['pending', 'approved', 'rejected'])->default('pending');

            // Status Pengiriman Barang Pengganti (Retur)
            $table->enum('status_pengiriman', ['none', 'diproses', 'dikirim', 'selesai'])->default('none');

            $table->timestamps(); // Otomatis membuat kolom created_at & updated_at

            // Relasi Foreign Key agar data sinkron dan aman
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komplain');
    }
};