<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
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
        $pesananBerhasilCount = $pesananSelesai->count();

        $rataRataOrder = $pesananBerhasilCount > 0 ? ($totalPendapatan / $pesananBerhasilCount) : 0;

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

        // 5. Kirim seluruh variabel ke view laporan
        return view('backend.laporan.index', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'totalPendapatan',
            'pesananBerhasilCount',
            'totalProdukTerjual',
            'rataRataOrder',
            'produkTerlaris'
        ));
    }
}