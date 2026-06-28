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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');

            $table->string('id_pesanan', 50)->unique();

            $table->string('transaction_id')->nullable();

            $table->string('snap_token')->nullable();

            $table->string('metode_pembayaran')->nullable();

            $table->dateTime('tgl_pembayaran')->nullable();

            $table->enum('status_pembayaran', [
                'pending',
                'paid',
                'failed',
                'expired'
            ])->default('pending');

            $table->timestamps();

            $table->foreign('id_pesanan')
                ->references('id_pesanan')
                ->on('pesanan')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
