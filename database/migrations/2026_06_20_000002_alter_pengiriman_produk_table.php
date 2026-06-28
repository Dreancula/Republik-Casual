<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengiriman_produk', function (Blueprint $table) {
            $table->dropForeign(['id_pesanan']);
        });

        Schema::table('pengiriman_produk', function (Blueprint $table) {
            $table->dropUnique(['id_pesanan']);
        });

        Schema::table('pengiriman_produk', function (Blueprint $table) {
            $table->index(['id_pesanan']);
        });

        Schema::table('pengiriman_produk', function (Blueprint $table) {
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->cascadeOnDelete();
        });

        Schema::table('pengiriman_produk', function (Blueprint $table) {
            $table->enum('jenis_pengiriman', ['utama', 'pengganti'])->default('utama')->after('no_resi');
        });
    }

    public function down(): void
    {
        Schema::table('pengiriman_produk', function (Blueprint $table) {
            $table->dropColumn('jenis_pengiriman');
        });

        Schema::table('pengiriman_produk', function (Blueprint $table) {
            $table->dropForeign(['id_pesanan']);
        });

        Schema::table('pengiriman_produk', function (Blueprint $table) {
            $table->dropIndex(['id_pesanan']);
        });

        Schema::table('pengiriman_produk', function (Blueprint $table) {
            $table->string('id_pesanan', 50)->unique()->change();
        });

        Schema::table('pengiriman_produk', function (Blueprint $table) {
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->cascadeOnDelete();
        });
    }
};
