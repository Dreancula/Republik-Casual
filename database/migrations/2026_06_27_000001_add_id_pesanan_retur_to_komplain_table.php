<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('komplain', function (Blueprint $table) {
            $table->string('id_pesanan_retur', 50)->nullable()->after('foto_return');
        });
    }

    public function down()
    {
        Schema::table('komplain', function (Blueprint $table) {
            $table->dropColumn('id_pesanan_retur');
        });
    }
};
