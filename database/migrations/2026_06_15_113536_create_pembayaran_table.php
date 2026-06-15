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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan');
            $table->enum('metode_pembayaran', ['QRIS', 'TRANSFER VA BCA', 'TRANSFER VA BRI', 'TRANSFER VA MANDIRI']);
            $table->enum('status_pembayaran', ['PENDING', 'PAID', 'FAILED']);
            $table->date('tgl_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
