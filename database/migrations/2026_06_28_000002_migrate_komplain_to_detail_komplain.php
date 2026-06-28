<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $komplains = DB::table('komplain')->whereNotNull('id_produk')->get();
        foreach ($komplains as $k) {
            DB::table('detail_komplain')->insert([
                'id_komplain' => $k->id_komplain,
                'id_produk' => $k->id_produk,
                'qty' => $k->qty,
                'foto' => $k->foto,
                'created_at' => $k->created_at ?? now(),
                'updated_at' => $k->updated_at ?? now(),
            ]);
        }
    }

    public function down()
    {
        DB::table('detail_komplain')->truncate();
    }
};
