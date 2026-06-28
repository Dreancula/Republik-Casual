<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE komplain MODIFY COLUMN foto TEXT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE komplain MODIFY COLUMN foto VARCHAR(255) NULL");
    }
};
