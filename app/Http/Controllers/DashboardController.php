<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use App\Models\PemasukanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data Gross Revenue (Hanya pesanan yang selesai)
        $grossRevenue = Pesanan::where('status_pesanan', 'selesai')->sum('total_bayar');

        // 2. Hitung Pesanan Baru (status pending)
        $pesananBaruCount = Pesanan::where('status_pesanan', 'pending')->count();

        // 3. Hitung Jumlah Katalog Produk aktif/terdaftar
        $totalProduk = Produk::count();

        // 4. Hitung Jumlah Akun Staf/Internal
        $totalStaff = User::count();

        // 5. Ambil 5 Aktivitas Transaksi Masuk Terbaru beserta relasi detail dan produknya
        // Catatan: Jika di Model Pesanan nama relasinya 'detail_pesanan', ubah menjadi 'detail_pesanan.produk'
        $transaksiTerbaru = Pesanan::with(['detailPesanan.produk', 'user'])
            ->orderBy('tgl_pesanan', 'desc')
            ->orderBy('id_pesanan', 'desc')
            ->take(5)
            ->get();

        // 6. Ambil data pemasukan barang beserta relasi detailnya untuk menghitung pengeluaran
        $pemasukan = PemasukanBarang::with('detailPemasukanBarang')->get();

        // 7. Hitung nominal total pengeluaran restock
        $totalPengeluaran = $pemasukan->sum(function ($p) {
            return $p->detailPemasukanBarang->sum(function ($d) {
                return $d->jumlah_masuk * $d->harga_beli;
            });
        });

        // 8. PERBAIKAN UTAMA: Hitung total kuantitas produk terjual dari pesanan yang 'selesai'
        // Jika nama relasi di model Pesanan Anda menggunakan snake_case, ganti 'detailPesanan' menjadi 'detail_pesanan'
        $totalTerjual = Pesanan::where('status_pesanan', 'selesai')
            ->with(['detailPesanan'])
            ->get()
            ->sum(function ($pesanan) {
                // Pastikan nama kolom 'quantity' sesuai dengan yang ada di database Anda (misal: qty / jumlah)
                return $pesanan->detailPesanan->sum('quantity');
            });

        // 9. Hitung total kerugian dari deadstok (stok rusak retur)
        $totalDeadstokLoss = Produk::sum(DB::raw('deadstok * harga'));

        // 10. Kirim semua data ke view backend dashboard
        return view('backend.beranda.index', compact(
            'grossRevenue',
            'pesananBaruCount',
            'totalProduk',
            'totalStaff',
            'transaksiTerbaru',
            'pemasukan',
            'totalPengeluaran',
            'totalTerjual', // <-- Variabel penjinak angka 0 Item siap dikirim!
            'totalDeadstokLoss'
        ));
    }
}