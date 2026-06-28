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
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');

            $table->unsignedBigInteger('id_role');

            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');

            $table->string('no_telp')->nullable();
            $table->text('alamat')->nullable();

            $table->string('google_id')->nullable();
            $table->string('avatar')->nullable();

            $table->enum('status', [
                'aktif',
                'nonaktif'
            ])->default('aktif');

            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_role')
                ->references('id_role')
                ->on('role')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
