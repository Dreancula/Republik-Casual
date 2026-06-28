<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RolePermission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 7; $i++) {
            RolePermission::create([
                'id_role' => 1,
                'id_permission' => $i,
            ]);
        }
    }
}