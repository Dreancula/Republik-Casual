<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('total_harga', 10, 0)->default(0);
            $table->enum('status', ['pending', 'Paid', 'Kirim', 'Selesai'])->default('pending');
            $table->string('noresi')->nullable();
            $table->string('kurir')->nullable();
            $table->string('layanan_ongkir')->nullable();
            $table->decimal('biaya_ongkir', 10, 0)->default(0);
            $table->string('estimasi_ongkir')->nullable();
            $table->decimal('total_berat', 8, 2)->default(0);
            $table->text('alamat')->nullable();
            $table->string('pos')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
