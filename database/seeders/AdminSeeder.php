<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'id_role' => 1,
            'nama' => 'Administrator',
            'email' => 'admin@republikcasual.com',
            'password' => Hash::make('admin123'),
            'no_telp' => '081234567890',
            'alamat' => 'Indonesia',
            'status' => 'aktif',
        ]);
    }
}