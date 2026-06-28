<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class AiStylistController extends Controller
{
    public function index()
    {
        return view(
            'frontend.customer.ai-stylist.index'
        );
    }

    public function recommend(Request $request)
    {
        // 1. Tambahkan validasi gender
        $request->validate([
            'style' => 'required',
            'gender' => 'required|in:laki-laki,perempuan', // Input baru
            'tinggi' => 'required|numeric|min:100|max:250',
            'berat' => 'required|numeric|min:30|max:200',
        ]);

        $style = strtolower($request->style);
        $gender = $request->gender;
        $tinggi = $request->tinggi;
        $berat = $request->berat;

        /*
        |--------------------------------------------------------------------------
        | AI Size Recommendation
        |--------------------------------------------------------------------------
        | Sedikit penyesuaian: Biasanya untuk perempuan berat badannya memiliki
        | batas size yang berbeda dengan laki-laki.
        */
        if ($gender == 'perempuan') {
            if ($berat <= 45) {
                $size = 'S';
            } elseif ($berat <= 55) {
                $size = 'M';
            } elseif ($berat <= 65) {
                $size = 'L';
            } elseif ($berat <= 75) {
                $size = 'XL';
            } else {
                $size = 'XXL';
            }
        } else {
            // Logika standar Anda untuk laki-laki
            if ($berat <= 50) {
                $size = 'S';
            } elseif ($berat <= 60) {
                $size = 'M';
            } elseif ($berat <= 75) {
                $size = 'L';
            } elseif ($berat <= 90) {
                $size = 'XL';
            } else {
                $size = 'XXL';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Callback Query Reusable untuk Filter Gender & Size
        |--------------------------------------------------------------------------
        */
        $filterOutfit = function ($query) use ($size, $gender) {
            $query->where('status_produk', 'aktif')
                ->where('size_produk', 'LIKE', "%{$size}%");

            // Filter berbasis Judul / Nama Produk
            $query->where(function ($q) use ($gender) {
                if ($gender === 'laki-laki') {
                    $q->where('nama_produk', 'LIKE', '%pria%')
                        ->orWhere('nama_produk', 'LIKE', '%cowok%')
                        ->orWhere('nama_produk', 'LIKE', '%unisex%')
                        // Mencegah tercampurnya produk spesifik wanita
                        ->where('nama_produk', 'NOT LIKE', '%wanita%')
                        ->where('nama_produk', 'NOT LIKE', '%cewek%');
                } else {
                    $q->where('nama_produk', 'LIKE', '%wanita%')
                        ->orWhere('nama_produk', 'LIKE', '%cewek%')
                        ->orWhere('nama_produk', 'LIKE', '%unisex%')
                        // Mencegah tercampurnya produk spesifik pria
                        ->where('nama_produk', 'NOT LIKE', '%pria%')
                        ->where('nama_produk', 'NOT LIKE', '%cowok%');
                }
            });
        };

        /*
        |--------------------------------------------------------------------------
        | Ambil Produk Berdasarkan Kategori
        |--------------------------------------------------------------------------
        */
        $kaos = Produk::with('kategori')
            ->whereHas('kategori', function ($q) {
                $q->where('nama_kategori', 'Kaos'); })
            ->where($filterOutfit)
            ->inRandomOrder()
            ->first();

        $celana = Produk::with('kategori')
            ->whereHas('kategori', function ($q) {
                $q->where('nama_kategori', 'Celana'); })
            ->where($filterOutfit)
            ->inRandomOrder()
            ->first();

        $jaket = Produk::with('kategori')
            ->whereHas('kategori', function ($q) {
                $q->where('nama_kategori', 'Jaket'); })
            ->where($filterOutfit)
            ->inRandomOrder()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | AI Score
        |--------------------------------------------------------------------------
        */
        $score = match ($size) {
            'S' => rand(88, 92),
            'M' => rand(90, 95),
            'L' => rand(92, 97),
            'XL' => rand(90, 96),
            default => rand(88, 95)
        };

        /*
        |--------------------------------------------------------------------------
        | Total Outfit
        |--------------------------------------------------------------------------
        */
        $total = ($kaos->harga ?? 0) + ($celana->harga ?? 0) + ($jaket->harga ?? 0);

        /*
        |--------------------------------------------------------------------------
        | Alasan & Tips Outfit (Disesuaikan Gaya & Gender)
        |--------------------------------------------------------------------------
        */
        $alasan = match ($style) {
            'streetwear' => "Kombinasi pilihan ini memberikan tampilan streetwear modern yang chic untuk gaya harian Anda.",
            'oversized' => "Outfit ini dipilih untuk menghasilkan siluet oversized yang santai, trendi, dan tetap nyaman.",
            'cargo' => "Kombinasi utility streetwear yang kuat, memberikan kesan aktif namun anggun.",
            'casual' => "Gaya casual esensial yang sangat fleksibel untuk hang-out maupun daily wear.",
            default => 'Outfit dipilih berdasarkan kombinasi produk terbaik yang tersedia saat ini.'
        };

        $tips = match ($style) {
            'streetwear' => 'Gunakan sneakers andalan Anda dan minimalisir aksesoris mencolok agar fokus pada pakaian.',
            'oversized' => 'Jika atasan sangat longgar, pastikan bagian bawahan memiliki potongan tapered atau pas di pinggang agar proporsi seimbang.',
            default => 'Gunakan kombinasi warna netral atau earth-tone agar tampilan terlihat premium.'
        };

        return view(
            'frontend.customer.ai-stylist.result',
            compact(
                'style',
                'gender',
                'tinggi',
                'berat',
                'size',
                'score',
                'tips',
                'alasan',
                'total',
                'kaos',
                'celana',
                'jaket'
            )
        );
    }
}