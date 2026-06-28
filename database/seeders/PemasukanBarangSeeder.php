<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PemasukanBarangSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Amankan User Manajer (Dibutuhkan sebagai aktor/petugas logistik bawaan)
        $manager = User::where('email', 'manager@republikcasual.com')->first();
        if (!$manager) {
            User::create([
                'id_role' => 2, // Sesuaikan dengan id_role milik Manajer di aplikasi kamu
                'nama' => 'Manajer Toko Logistik',
                'email' => 'manager@republikcasual.com',
                'password' => Hash::make('manager123'),
                'no_telp' => '081298765432',
                'alamat' => 'Gudang Utama Republik Casual',
                'status' => 'aktif',
            ]);
        }

        // 2. Amankan Master Data Referensi (Dibutuhkan untuk membuat produk baru di form)
        Kategori::updateOrCreate(
            ['id_kategori' => 1],
            ['nama_kategori' => 'Pakaian Pria']
        );

        Brand::updateOrCreate(
            ['id_brand' => 1],
            ['nama_brand' => 'Republik Casual Apparel']
        );

        // --- DATA DUMMY NOTA & DETAIL PEMASUKAN BARANG TELAH DIHAPUS BERSIH ---
        // Halaman index produk dan pemasukan barang kamu sekarang akan kembali kosong melompong (0 data)
    }
}