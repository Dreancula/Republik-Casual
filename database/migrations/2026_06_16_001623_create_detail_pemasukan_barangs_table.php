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
        Schema::create('detail_pemasukan_barang', function (Blueprint $table) {
            $table->id('id_detail_pemasukan'); // Primary Key tabel anak

            // PENGUNCI ERROR: Wajib menggunakan foreignId agar bertipe BIGINT UNSIGNED
            // dan langsung mengikat ke kolom 'id_pemasukan' di tabel 'pemasukan_barang'
            $table->foreignId('id_pemasukan')
                ->constrained('pemasukan_barang', 'id_pemasukan')
                ->cascadeOnDelete();

            // Kolom pelengkap berdasarkan properti $fillable di model kamu:
            $table->unsignedBigInteger('id_produk');
            $table->integer('jumlah_masuk');
            $table->bigInteger('harga_beli');

            $table->timestamps();

            // Opsional: Hubungkan foreign key ke tabel produk jika primary key di tabel produk juga bernama id_produk
            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('produk') // Sesuaikan nama tabel master produk kamu di sini
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pemasukan_barang');
    }
};