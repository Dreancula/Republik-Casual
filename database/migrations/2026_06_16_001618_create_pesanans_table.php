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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->string('id_pesanan', 50)->primary();

            $table->unsignedBigInteger('id_user');

            $table->dateTime('tgl_pesanan');

            $table->enum('status_pesanan', [
                'pending',
                'dibayar',
                'diproses',
                'dikirim',
                'selesai',
                'dibatalkan'
            ])->default('pending');

            $table->decimal('total_harga_produk', 12, 2);

            $table->decimal('total_ongkir', 12, 2);

            $table->decimal('total_bayar', 12, 2);

            $table->timestamps();

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
        Schema::dropIfExists('pesanans');
    }
};
