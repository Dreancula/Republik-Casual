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
        Schema::create('user', function (Blueprint $table) {
            $table->id('id_user');
            $table->foreignId('id_role')->constrained('role', 'id_role');
            $table->enum('role', ['0', '1', '2'])->default('2');
            $table->boolean('status');
            $table->string('nama', 150);
            $table->string('email')->unique();
            $table->string('no_telp', 13)->nullable();
            $table->string('kota', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->string('password');
            $table->string('hp', 13)->nullable();
            $table->string('foto')->nullable();
            $table->string('google_id')->nullable();
            $table->text('google_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};

