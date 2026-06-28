<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::insert([
            [
                'nama_permission' => 'Kelola Produk',
                'deskripsi' => 'Mengelola data produk',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_permission' => 'Kelola Kategori',
                'deskripsi' => 'Mengelola kategori produk',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_permission' => 'Kelola Brand',
                'deskripsi' => 'Mengelola brand produk',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_permission' => 'Kelola Pesanan',
                'deskripsi' => 'Mengelola pesanan pelanggan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_permission' => 'Kelola Pembayaran',
                'deskripsi' => 'Mengelola pembayaran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_permission' => 'Kelola Pengiriman',
                'deskripsi' => 'Mengelola pengiriman barang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_permission' => 'Kelola User',
                'deskripsi' => 'Mengelola user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}