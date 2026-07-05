<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id('id_chat');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->text('teks');
            $table->string('kategori_chat', 50)->default('Umum');
            $table->boolean('is_admin')->default(false);
            $table->string('foto_chat')->nullable();
            $table->timestamps();

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
