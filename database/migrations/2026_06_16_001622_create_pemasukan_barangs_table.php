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
        Schema::create('pemasukan_barang', function (Blueprint $table) {
            $table->id('id_pemasukan');
            $table->unsignedBigInteger('id_user');
            $table->dateTime('tgl_pemasukan');
            $table->string('no_faktur');
            $table->timestamps();

            // Relasi Foreign Key ke tabel users
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan_barang');
    }
};