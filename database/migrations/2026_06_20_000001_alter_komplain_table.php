<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('komplain', function (Blueprint $table) {
            $table->dropColumn('status_pengiriman');
        });

        Schema::table('komplain', function (Blueprint $table) {
            $table->string('no_resi_return', 50)->nullable()->after('foto');
            $table->string('foto_return')->nullable()->after('no_resi_return');
            $table->unsignedBigInteger('id_produk')->nullable()->after('id_user');
            $table->integer('qty')->default(1)->after('id_produk');

            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('set null');
        });

        DB::statement("ALTER TABLE komplain MODIFY COLUMN status_komplain ENUM('pending','approved','rejected','selesai') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE komplain MODIFY COLUMN status_komplain ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");

        Schema::table('komplain', function (Blueprint $table) {
            $table->dropForeign(['id_produk']);
            $table->dropColumn(['id_produk', 'qty', 'no_resi_return', 'foto_return']);
        });

        Schema::table('komplain', function (Blueprint $table) {
            $table->enum('status_pengiriman', ['none', 'diproses', 'dikirim', 'selesai'])->default('none');
        });
    }
};
