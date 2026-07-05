<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    public function berandaBackend()
    {
        // 1. Ambil data Gross Revenue (Total Pendapatan dari pesanan yang selesai)
        $grossRevenue = Pesanan::where('status_pesanan', 'selesai')->sum('total_bayar');
        $pendapatanProduk = Pesanan::where('status_pesanan', 'selesai')->sum('total_harga_produk');
        $pendapatanOngkir = Pesanan::where('status_pesanan', 'selesai')->sum('total_ongkir');
        $pengeluaranOngkir = $pendapatanOngkir;

        // 2. Hitung Total Pengeluaran dari Pemasukan Barang dengan penanganan error aman
        try {
            // Kita hitung pengeluaran dari harga beli dikali jumlah stok yang masuk
            // Jika nama model Anda bukan PemasukanBarang, silakan sesuaikan di baris ini
            if (class_exists('App\Models\PemasukanBarang')) {
                $totalPengeluaran = \App\Models\PemasukanBarang::select(\DB::raw('SUM(jumlah_masuk * harga_beli) as total'))
                    ->first()
                    ->total ?? 0;
            } else {
                $totalPengeluaran = 0;
            }
        } catch (\Exception $e) {
            // Jika tabel atau kolom belum sinkron, amankan dengan nilai default 0
            $totalPengeluaran = 0;
        }

        // 3. Hitung Untung Bersih (Pendapatan Produk - Pengeluaran Barang)
        $netProfit = $pendapatanProduk - $totalPengeluaran;
        $totalPengeluaranKeseluruhan = $totalPengeluaran + $pendapatanOngkir;

        // 4. Metrik pendukung lainnya
        $pesananBaruCount = Pesanan::where('status_pesanan', 'pending')->count();
        $totalProduk = Produk::count();
        $totalStaff = User::count();

        // 5. Ambil 5 Aktivitas Transaksi Masuk Terbaru
        $transaksiTerbaru = Pesanan::with(['detailPesanan.produk'])
            ->orderBy('tgl_pesanan', 'desc')
            ->orderBy('id_pesanan', 'desc')
            ->take(5)
            ->get();

        // 6. Mengirimkan seluruh variabel secara utuh ke View
        return view('backend.beranda.index', [
            'judul' => 'Beranda',
            'sub' => 'Halaman Beranda',
            'grossRevenue' => $grossRevenue,
            'pendapatanProduk' => $pendapatanProduk,
            'pendapatanOngkir' => $pendapatanOngkir,
            'pengeluaranOngkir' => $pengeluaranOngkir,
            'totalPengeluaranKeseluruhan' => $totalPengeluaranKeseluruhan,
            'totalPengeluaran' => $totalPengeluaran,
            'netProfit' => $netProfit,
            'pesananBaruCount' => $pesananBaruCount,
            'totalProduk' => $totalProduk,
            'totalStaff' => $totalStaff,
            'transaksiTerbaru' => $transaksiTerbaru
        ]);
    }
}