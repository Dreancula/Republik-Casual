<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('role')->insert([
            ['nama_role' => 'Customer'],
            ['nama_role' => 'Admin'],
            ['nama_role' => 'Manajer Toko'],
        ]);

        DB::table('permission')->insert([
            ['nama_permission' => 'manage_users', 'deskripsi' => 'Mengelola data user'],
            ['nama_permission' => 'manage_products', 'deskripsi' => 'Mengelola data produk'],
            ['nama_permission' => 'manage_orders', 'deskripsi' => 'Mengelola data pesanan'],
            ['nama_permission' => 'manage_stock', 'deskripsi' => 'Mengelola pemasukan barang'],
        ]);

        DB::table('role_permission')->insert([
            ['id_role' => 2, 'id_permission' => 1],
            ['id_role' => 2, 'id_permission' => 2],
            ['id_role' => 2, 'id_permission' => 3],
            ['id_role' => 3, 'id_permission' => 4],
            ['id_role' => 3, 'id_permission' => 2],
        ]);

        $admin = User::create([
            'id_role' => 2,
            'role' => '1',
            'nama' => 'Administrator',
            'email' => 'admin@admin.com',
            'status' => 1,
            'password' => Hash::make('admin123'),
            'no_telp' => '0812345678901',
            'kota' => 'Jakarta',
            'alamat' => 'Jl. Contoh No. 1',
            'hp' => '0812345678901',
        ]);

        User::create([
            'id_role' => 3,
            'role' => '0',
            'nama' => 'Manajer Toko',
            'email' => 'manajer@admin.com',
            'status' => 1,
            'password' => Hash::make('manajer123'),
            'no_telp' => '0812345678902',
            'kota' => 'Bandung',
            'alamat' => 'Jl. Contoh No. 2',
            'hp' => '0812345678902',
        ]);

        $customers = [
            ['nama' => 'Raka Pratama', 'email' => 'raka@gmail.com', 'hp' => '081234567891', 'kota' => 'Jakarta Selatan', 'alamat' => 'Jl. Sudirman No. 10', 'pos' => '12560'],
            ['nama' => 'Dinda Aurelia', 'email' => 'dinda@gmail.com', 'hp' => '081234567892', 'kota' => 'Bandung', 'alamat' => 'Jl. Dago No. 25', 'pos' => '40115'],
            ['nama' => 'Bima Sakti', 'email' => 'bima@gmail.com', 'hp' => '081234567893', 'kota' => 'Surabaya', 'alamat' => 'Jl. Tunjungan No. 5', 'pos' => '60271'],
            ['nama' => 'Sari Dewi', 'email' => 'sari@gmail.com', 'hp' => '081234567894', 'kota' => 'Yogyakarta', 'alamat' => 'Jl. Malioboro No. 88', 'pos' => '55161'],
        ];

        foreach ($customers as $data) {
            $user = User::create([
                'id_role' => 1,
                'role' => '2',
                'nama' => $data['nama'],
                'email' => $data['email'],
                'status' => 1,
                'password' => Hash::make('customer123'),
                'no_telp' => $data['hp'],
                'kota' => $data['kota'],
                'alamat' => $data['alamat'],
                'hp' => $data['hp'],
            ]);

            Customer::create([
                'user_id' => $user->id_user,
                'google_id' => null,
                'google_token' => null,
                'alamat' => $data['alamat'],
                'pos' => $data['pos'],
            ]);
        }

        $kategoriData = ['T-Shirt', 'Crewneck', 'Hoodie', 'Pants', 'Accessories'];
        foreach ($kategoriData as $nama) {
            Kategori::create(['nama_kategori' => $nama]);
        }

        $produkList = [
            ['id_kategori' => 1, 'user_id' => $admin->id_user, 'nama_produk' => 'Oversized T-Shirt Hitam', 'detail' => 'Oversized T-Shirt katun combed 30s, nyaman dipakai sehari-hari.', 'harga' => 85000, 'size_produk' => 'L', 'stok' => 100, 'berat' => 250, 'id_brand' => 1, 'foto' => 'tshirt-hitam.jpg', 'status' => 1],
            ['id_kategori' => 1, 'user_id' => $admin->id_user, 'nama_produk' => 'Oversized T-Shirt Putih', 'detail' => 'Oversized T-Shirt katun combed 30s, nyaman dipakai sehari-hari.', 'harga' => 85000, 'size_produk' => 'L', 'stok' => 100, 'berat' => 250, 'id_brand' => 1, 'foto' => 'tshirt-putih.jpg', 'status' => 1],
            ['id_kategori' => 1, 'user_id' => $admin->id_user, 'nama_produk' => 'Striped T-Shirt Navy', 'detail' => 'T-Shirt lengan pendek motif stripe, bahan tebal dan adem.', 'harga' => 95000, 'size_produk' => 'M', 'stok' => 75, 'berat' => 260, 'id_brand' => 1, 'foto' => 'tshirt-navy.jpg', 'status' => 1],
            ['id_kategori' => 2, 'user_id' => $admin->id_user, 'nama_produk' => 'Crewneck Hitam Polos', 'detail' => 'Crewneck polos bahan fleece tebal, cocok untuk cuaca dingin.', 'harga' => 149000, 'size_produk' => 'L', 'stok' => 50, 'berat' => 400, 'id_brand' => 1, 'foto' => 'crewneck-hitam.jpg', 'status' => 1],
            ['id_kategori' => 2, 'user_id' => $admin->id_user, 'nama_produk' => 'Crewneck Abu Premium', 'detail' => 'Crewneck premium bahan fleece anti-linting, nyaman dipakai.', 'harga' => 159000, 'size_produk' => 'L', 'stok' => 45, 'berat' => 420, 'id_brand' => 1, 'foto' => 'crewneck-abu.jpg', 'status' => 1],
            ['id_kategori' => 3, 'user_id' => $admin->id_user, 'nama_produk' => 'Hoodie Emboss Hitam', 'detail' => 'Hoodie dengan sablon emboss, bahan fleece tebal dan hangat.', 'harga' => 199000, 'size_produk' => 'L', 'stok' => 40, 'berat' => 500, 'id_brand' => 1, 'foto' => 'hoodie-hitam.jpg', 'status' => 1],
            ['id_kategori' => 3, 'user_id' => $admin->id_user, 'nama_produk' => 'Hoodie Tie Dye', 'detail' => 'Hoodie motif tie dye limited edition, bahan fleece premium.', 'harga' => 229000, 'size_produk' => 'L', 'stok' => 30, 'berat' => 500, 'id_brand' => 1, 'foto' => 'hoodie-tiedye.jpg', 'status' => 1],
            ['id_kategori' => 4, 'user_id' => $admin->id_user, 'nama_produk' => 'Cargo Pants Army', 'detail' => 'Celana cargo model kekinian dengan bahan kanvas tebal.', 'harga' => 139000, 'size_produk' => 'M', 'stok' => 60, 'berat' => 350, 'id_brand' => 1, 'foto' => 'cargo-army.jpg', 'status' => 1],
            ['id_kategori' => 4, 'user_id' => $admin->id_user, 'nama_produk' => 'Denim Pants Blue', 'detail' => 'Celana denim relaxed fit, bahan denim premium.', 'harga' => 179000, 'size_produk' => 'M', 'stok' => 55, 'berat' => 400, 'id_brand' => 1, 'foto' => 'denim-blue.jpg', 'status' => 1],
            ['id_kategori' => 4, 'user_id' => $admin->id_user, 'nama_produk' => 'Jogger Pants Hitam', 'detail' => 'Celana jogger bahan fleece tipis, cocok untuk daily wear.', 'harga' => 119000, 'size_produk' => 'L', 'stok' => 80, 'berat' => 300, 'id_brand' => 1, 'foto' => 'jogger-hitam.jpg', 'status' => 1],
            ['id_kategori' => 5, 'user_id' => $admin->id_user, 'nama_produk' => 'Bucket Hat Hitam', 'detail' => 'Bucket hat bahan twill, adjustable ukuran.', 'harga' => 45000, 'size_produk' => 'M', 'stok' => 100, 'berat' => 80, 'id_brand' => 1, 'foto' => 'buckethat-hitam.jpg', 'status' => 1],
            ['id_kategori' => 5, 'user_id' => $admin->id_user, 'nama_produk' => 'Tote Bag Canvas', 'detail' => 'Tote bag kanvas printing logo, kapasitas besar.', 'harga' => 55000, 'size_produk' => 'M', 'stok' => 90, 'berat' => 150, 'id_brand' => 1, 'foto' => 'totebag.jpg', 'status' => 1],
        ];

        DB::table('brand')->insert([
            ['nama_brand' => 'Republic Casual'],
        ]);

        foreach ($produkList as $produk) {
            DB::table('produk')->insert($produk);
        }
    }
}
