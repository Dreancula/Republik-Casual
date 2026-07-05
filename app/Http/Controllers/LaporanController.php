<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\PemasukanBarang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil input filter tanggal (jika kosong, default bulan berjalan)
        $tanggalAwal = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Ubah format string ke format date-time untuk query database
        $start = Carbon::parse($tanggalAwal)->startOfDay();
        $end = Carbon::parse($tanggalAkhir)->endOfDay();

        // 2. Query Utama: Ambil pesanan yang berstatus 'selesai' pada rentang periode
        $pesananSelesai = Pesanan::where('status_pesanan', 'selesai')
            ->whereBetween('tgl_pesanan', [$start, $end]);

        // 3. Hitung Ringkasan Statistik Utama (Highlights)
        $totalPendapatan = $pesananSelesai->sum('total_bayar');
        $pendapatanProduk = $pesananSelesai->sum('total_harga_produk');
        $pendapatanOngkir = $pesananSelesai->sum('total_ongkir');
        $pesananBerhasilCount = $pesananSelesai->count();

        $rataRataOrder = $pesananBerhasilCount > 0 ? ($pendapatanProduk / $pesananBerhasilCount) : 0;

        // Hitung total kuantitas produk terjual menggunakan kolom 'quantity'
        $totalProdukTerjual = DetailPesanan::whereHas('pesanan', function ($query) use ($start, $end) {
            $query->where('status_pesanan', 'selesai')
                ->whereBetween('tgl_pesanan', [$start, $end]);
        })->sum('quantity');

        // 4. Ambil Top 5 Produk Terlaris berdasarkan akumulasi quantity terbanyak
        $produkTerlaris = DetailPesanan::select(
            'id_produk',
            DB::raw('SUM(quantity) as total_terjual'),
            DB::raw('SUM(sub_total) as total_nilai')
        )
            ->whereHas('pesanan', function ($query) use ($start, $end) {
                $query->where('status_pesanan', 'selesai')
                    ->whereBetween('tgl_pesanan', [$start, $end]);
            })
            ->with('produk') // Mengambil relasi data produk (nama, dll)
            ->groupBy('id_produk')
            ->orderBy('total_terjual', 'desc')
            ->take(5)
            ->get();

        // 5. Hitung total pengeluaran barang (modal restock)
        $pengeluaranBarang = DB::table('detail_pemasukan_barang')
            ->select(DB::raw('COALESCE(SUM(jumlah_masuk * harga_beli), 0) as total'))
            ->first()->total ?? 0;

        // 6. Monthly trend untuk diagram garis (12 bulan terakhir)
        $monthlyTrend = Pesanan::select(
            DB::raw("DATE_FORMAT(tgl_pesanan, '%Y-%m') as bulan"),
            DB::raw('SUM(total_harga_produk) as total_produk'),
            DB::raw('SUM(total_ongkir) as total_ongkir'),
            DB::raw('COUNT(*) as jumlah_pesanan')
        )
            ->where('status_pesanan', 'selesai')
            ->whereBetween('tgl_pesanan', [
                Carbon::now()->subMonths(11)->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->groupBy(DB::raw("DATE_FORMAT(tgl_pesanan, '%Y-%m')"))
            ->orderBy('bulan')
            ->get();

        // 7. Detail pemasukan: daftar pesanan selesai di periode ini
        $detailPemasukan = Pesanan::with('user')
            ->where('status_pesanan', 'selesai')
            ->whereBetween('tgl_pesanan', [$start, $end])
            ->orderBy('tgl_pesanan', 'desc')
            ->get(['id_pesanan', 'id_user', 'total_harga_produk', 'total_ongkir', 'total_bayar', 'tgl_pesanan']);

        // 8. Detail pengeluaran: daftar stok masuk dengan produk
        $detailPengeluaran = DB::table('detail_pemasukan_barang as dpb')
            ->join('pemasukan_barang as pb', 'dpb.id_pemasukan', '=', 'pb.id_pemasukan')
            ->join('produk as p', 'dpb.id_produk', '=', 'p.id_produk')
            ->select(
                'pb.tgl_pemasukan',
                'p.nama_produk',
                'dpb.jumlah_masuk',
                'dpb.harga_beli',
                DB::raw('dpb.jumlah_masuk * dpb.harga_beli as total')
            )
            ->whereBetween('pb.tgl_pemasukan', [$start, $end])
            ->orderBy('pb.tgl_pemasukan', 'desc')
            ->get();

        // 9. Kirim seluruh variabel ke view laporan
        return view('backend.laporan.index', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'totalPendapatan',
            'pendapatanProduk',
            'pendapatanOngkir',
            'pengeluaranBarang',
            'pesananBerhasilCount',
            'totalProdukTerjual',
            'rataRataOrder',
            'produkTerlaris',
            'monthlyTrend',
            'detailPemasukan',
            'detailPengeluaran'
        ));
    }
}