<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthorizationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. MASTER DATA MASTER ROLE
        $adminRole = Role::updateOrCreate(['id_role' => 1], ['nama_role' => 'Administrator']);
        $managerRole = Role::updateOrCreate(['id_role' => 2], ['nama_role' => 'Manajer Toko']);
        $customerRole = Role::updateOrCreate(['id_role' => 3], ['nama_role' => 'Customer']);

        // 2. MASTER DATA PERMISSIONS (Hak Akses Fitur Fisik)
        $p1 = Permission::updateOrCreate(['id_permission' => 1], [
            'nama_permission' => 'kelola_produk',
            'deskripsi' => 'Dapat merilis, menyunting, dan menghapus katalog produk.'
        ]);

        $p2 = Permission::updateOrCreate(['id_permission' => 2], [
            'nama_permission' => 'input_pemasukan',
            'deskripsi' => 'Dapat mencatat logistik barang masuk dari supplier (Hanya Manajer).'
        ]);

        $p3 = Permission::updateOrCreate(['id_permission' => 3], [
            'nama_permission' => 'kelola_user',
            'deskripsi' => 'Dapat memanajemen akun staff dan melihat data customer.'
        ]);

        $p4 = Permission::updateOrCreate(['id_permission' => 4], [
            'nama_permission' => 'view_laporan',
            'deskripsi' => 'Dapat memantau grafik bisnis dan mencetak laporan keuangan.'
        ]);


        // 3. JEMBATAN RELASI (Role Permission)
        // --- Hak Akses Administrator (Bisa Kelola Produk, User, & Laporan)
        RolePermission::updateOrCreate(['id_role' => 1, 'id_permission' => 1]);
        RolePermission::updateOrCreate(['id_role' => 1, 'id_permission' => 3]);
        RolePermission::updateOrCreate(['id_role' => 1, 'id_permission' => 4]);

        // --- Hak Akses Manajer Toko (Bisa Kelola Produk, Input Pemasukan, & Laporan)
        RolePermission::updateOrCreate(['id_role' => 2, 'id_permission' => 1]);
        RolePermission::updateOrCreate(['id_role' => 2, 'id_permission' => 2]);
        RolePermission::updateOrCreate(['id_role' => 2, 'id_permission' => 4]);


        // 4. MASTER DATA USERS
        // --- Akun Administrator
        User::updateOrCreate(
            ['email' => 'admin@republikcasual.com'],
            [
                'id_role' => 1,
                'nama' => 'Administrator',
                'password' => Hash::make('admin123'),
                'no_telp' => '081234567890',
                'alamat' => 'HQ Republik Casual Indonesia',
                'status' => 'aktif',
            ]
        );

        // --- Akun Manajer Toko
        User::updateOrCreate(
            ['email' => 'manager@republikcasual.com'],
            [
                'id_role' => 2,
                'nama' => 'Manajer Toko',
                'password' => Hash::make('manager123'),
                'no_telp' => '081298765432',
                'alamat' => 'Gudang Utama Republik Casual',
                'status' => 'aktif',
            ]
        );
    }
}