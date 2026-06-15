<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user', 'id_user')->onDelete('cascade');
            $table->string('google_id')->nullable();
            $table->text('google_token')->nullable();
            $table->text('alamat')->nullable();
            $table->string('pos')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
