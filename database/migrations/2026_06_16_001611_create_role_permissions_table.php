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
        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_role');
            $table->unsignedBigInteger('id_permission');

            $table->timestamps();

            $table->foreign('id_role')
                ->references('id_role')
                ->on('role')
                ->cascadeOnDelete();

            $table->foreign('id_permission')
                ->references('id_permission')
                ->on('permission')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
