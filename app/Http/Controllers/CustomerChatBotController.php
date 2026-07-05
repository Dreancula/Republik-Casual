<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Brand;

class CustomerChatBotController extends Controller
{
    public function getBotResponse(Request $request)
    {
        $userMessage = strtolower(trim($request->input('message')));

        $reply = $this->getStaticReply($userMessage);
        if ($reply) {
            return response()->json(['reply' => $reply]);
        }

        $reply = $this->getProductReply($userMessage);
        if ($reply) {
            return response()->json(['reply' => $reply]);
        }

        $reply = $this->searchProductsGeneric($userMessage);
        if ($reply) {
            return response()->json(['reply' => $reply]);
        }

        return response()->json([
            'reply' => 'Maaf Kak, saya kurang paham maksudnya. Kakak bisa coba ketik nama produk yang dicari (misal: kaos, celana, crewneck) atau tanya soal stok, harga, cara order, dan pengiriman ya!'
        ]);
    }

    private function getStaticReply($message)
    {
        if (str_contains($message, 'promo')) {
            return "Halo Kak! Untuk info promo terbaru dari Republik Casual, Kakak bisa cek langsung di halaman utama website kami ya. Promo spesial biasanya diumumkan di sana. Ada yang bisa kami bantu lagi Kak?";
        }

        if (str_contains($message, 'cara order') || str_contains($message, 'cara beli') || str_contains($message, 'gimana cara')) {
            return "Tentu Kak, cara order di Republik Casual gampang banget:\n\n1. Pilih produk yang Kakak suka\n2. Klik 'Beli Sekarang' atau masukkan ke keranjang\n3. Pilih ukuran dan quantity\n4. Lanjut ke checkout & isi alamat pengiriman\n5. Pilih metode pembayaran\n6. Selesai! Pesanan akan diproses setelah pembayaran terverifikasi.\n\nAda yang ingin ditanyakan lagi Kak?";
        }

        if (preg_match('/\bwa\b/', $message) || str_contains($message, 'whatsapp') || str_contains($message, 'kontak') || str_contains($message, 'admin')) {
            return "Halo Kak! Untuk menghubungi admin Republik Casual, silakan chat WhatsApp kami di nomor yang tertera di halaman Kontak website ya. Admin kami siap bantu Kakak dari jam 08.00 - 21.00 WIB. Ada yang bisa kami bantu lagi Kak?";
        }

        if (str_contains($message, 'pengiriman') || str_contains($message, 'kurir') || str_contains($message, 'sampai')) {
            return "Untuk pengiriman, Republik Casual bekerja sama dengan berbagai kurir seperti JNE, J&T, SiCepat, dan Anteraja Kak. Estimasi pengiriman biasanya 2-7 hari kerja tergantung lokasi Kakak. Ada yang ingin ditanyakan lagi?";
        }

        if (str_contains($message, 'ukuran') || str_contains($message, 'size') || str_contains($message, 'fit')) {
            return "Kakak bisa cek panduan ukuran (size chart) di halaman detail setiap produk ya Kak. Kalau masih ragu, boleh konsultasi dengan admin via WhatsApp agar kami bisa bantu rekomendasikan ukuran yang pas!";
        }

        if (str_contains($message, 'bayar') || str_contains($message, 'pembayaran') || str_contains($message, 'transfer')) {
            return "Kakak bisa melakukan pembayaran melalui transfer bank (BCA, Mandiri, BRI) atau melalui Midtrans (kartu kredit, GoPay, OVO, Dana) Kak. Pembayaran akan langsung terverifikasi setelah masuk. Ada yang bisa kami bantu lagi?";
        }

        if (str_contains($message, 'return') || str_contains($message, 'tukar') || str_contains($message, 'komplain') || str_contains($message, 'retur')) {
            return "Untuk pengajuan return/komplain, silakan pilih kategori 'Pengajuan Return (Komplain)' di atas ya Kak, lalu jelaskan kendalanya. Admin kami akan segera merespon. Atau bisa juga hubungi via WhatsApp untuk proses lebih cepat!";
        }

        return null;
    }

    private function getProductReply($message)
    {
        $colorMap = [
            'hitam' => 'black', 'putih' => 'white', 'merah' => 'red',
            'biru' => 'blue', 'hijau' => 'green', 'kuning' => 'yellow',
            'coklat' => 'brown', 'abu' => 'grey', 'abu-abu' => 'grey',
            'ungu' => 'purple', 'pink' => 'pink', 'orange' => 'orange',
            'krem' => 'cream', 'navy' => 'navy', 'tosca' => 'teal',
            'emas' => 'gold', 'perak' => 'silver',
        ];

        $synonymMap = [
            'baju' => 'kaos', 'kemeja' => 'shirt', 'celana' => 'pants',
            'tas' => 'bag', 'topi' => 'cap', 'jaket' => 'jacket',
        ];

        $isStockQuery = str_contains($message, 'stok') || str_contains($message, 'ready') || str_contains($message, 'tersedia') || str_contains($message, 'adaan');
        $isPriceQuery = str_contains($message, 'harga') || str_contains($message, 'berapa');
        $isCatalogQuery = str_contains($message, 'produk') || str_contains($message, 'katalog') || str_contains($message, 'jual') || str_contains($message, 'ada');
        $isSearchQuery = $isStockQuery || $isPriceQuery || $isCatalogQuery;

        if (!$isSearchQuery) {
            return null;
        }

        $stopWords = ['stok', 'ready', 'tersedia', 'adaan', 'harga', 'berapa', 'produk', 'katalog', 'jual', 'ada', 'ya', 'yang', 'di', 'dan', 'masih', 'gak', 'nggak', 'enggak', 'kak', 'sih', 'dong', 'nya', 'tolong', 'bisa', 'saja', 'cek', 'lihat', 'info', 'informasi', 'mau', 'min', 'mas', 'mbak', 'bro', 'gan', 'om', 'tante', 'pakai', 'pake'];

        $words = explode(' ', $message);
        $keywords = array_filter($words, function ($w) use ($stopWords) {
            $w = trim($w);
            return strlen($w) > 1 && !in_array($w, $stopWords);
        });

        $categoryKeywords = Kategori::pluck('nama_kategori')->map(fn($v) => strtolower($v))->toArray();
        $brandKeywords = Brand::pluck('nama_brand')->map(fn($v) => strtolower($v))->toArray();

        $matchedCategory = null;
        $matchedBrand = null;
        $productKeywords = [];

        foreach ($keywords as $word) {
            $found = false;
            foreach ($categoryKeywords as $cat) {
                if (str_contains($word, $cat) || str_contains($cat, $word)) {
                    $matchedCategory = $cat;
                    $found = true;
                    break;
                }
            }
            if ($found) continue;
            foreach ($brandKeywords as $b) {
                if (str_contains($word, $b) || str_contains($b, $word)) {
                    $matchedBrand = $b;
                    $found = true;
                    break;
                }
            }
            if ($found) continue;
            $translated = $colorMap[$word] ?? $synonymMap[$word] ?? null;
            if ($translated) {
                $productKeywords[] = $translated;
            }
            $productKeywords[] = $word;
        }

        $query = Produk::with('kategori')->where('status_produk', 'aktif');

        if ($matchedCategory) {
            $kategori = Kategori::where('nama_kategori', 'like', "%$matchedCategory%")->first();
            if ($kategori) {
                $query->where('id_kategori', $kategori->id_kategori);
            }
        }

        if ($matchedBrand) {
            $brand = Brand::where('nama_brand', 'like', "%$matchedBrand%")->first();
            if ($brand) {
                $query->where('id_brand', $brand->id_brand);
            }
        }

        if (!empty($productKeywords)) {
            $query->where(function ($q) use ($productKeywords) {
                foreach ($productKeywords as $kw) {
                    $q->orWhere('nama_produk', 'like', "%$kw%")
                      ->orWhere('deskripsi_produk', 'like', "%$kw%");
                }
            });
        }

        $products = $query->limit(5)->get();

        if ($products->isEmpty()) {
            $fallback = Produk::with('kategori')
                ->where('status_produk', 'aktif')
                ->inRandomOrder()
                ->limit(3)
                ->get();

            if ($fallback->isEmpty()) {
                return "Maaf Kak, saat ini belum ada produk yang tersedia. Silakan cek kembali nanti ya!";
            }

            $reply = "Maaf Kak, produk yang Kakak cari tidak ditemukan. Ini beberapa produk andalan kami:\n\n";
            foreach ($fallback as $p) {
                $reply .= "• {$p->nama_produk} — Rp " . number_format($p->harga, 0, ',', '.') . "\n";
            }
            $reply .= "\nAtau Kakak bisa lihat katalog lengkap di halaman Produk website kami ya! Ada yang bisa dibantu lagi?";
            return $reply;
        }

        $reply = '';
        foreach ($products as $i => $p) {
            $reply .= ($i + 1) . '. ' . strtoupper($p->nama_produk) . "\n";
            if ($p->kategori) {
                $reply .= "   Kategori: {$p->kategori->nama_kategori}";
            }
            $reply .= " | Rp " . number_format($p->harga, 0, ',', '.') . "\n";
            $reply .= "   Stok: {$p->stok} pcs";
            if ($p->size_produk) {
                $reply .= " | Ukuran: {$p->size_produk}";
            }
            $reply .= "\n\n";
        }

        if ($products->count() == 1) {
            $p = $products->first();
            if ($p->stok > 0) {
                $reply .= "Stok {$p->nama_produk} masih tersedia {$p->stok} pcs Kak! Yuk langsung order sebelum habis.";
            } else {
                $reply .= "Maaf Kak, stok {$p->nama_produk} sedang kosong. Silakan cek lagi nanti ya!";
            }
        } else {
            $reply .= "Produk di atas masih tersedia Kak! Ada yang mau ditanyakan lagi?";
        }

        return $reply;
    }

    private function searchProductsGeneric($message)
    {
        $colorMap = [
            'hitam' => 'black', 'putih' => 'white', 'merah' => 'red',
            'biru' => 'blue', 'hijau' => 'green', 'kuning' => 'yellow',
            'coklat' => 'brown', 'abu' => 'grey', 'abu-abu' => 'grey',
            'ungu' => 'purple', 'pink' => 'pink', 'orange' => 'orange',
            'krem' => 'cream', 'navy' => 'navy', 'tosca' => 'teal',
            'emas' => 'gold', 'perak' => 'silver',
        ];

        $stopWords = ['ya', 'yang', 'di', 'dan', 'masih', 'gak', 'nggak', 'enggak', 'kak', 'sih', 'dong', 'nya', 'tolong', 'bisa', 'saja', 'cek', 'lihat', 'mau', 'min', 'mas', 'mbak', 'bro', 'gan', 'om', 'tante', 'pakai', 'pake', 'saya', 'aku', 'tolong', 'please', 'gan', 'bang', 'boss', 'kak'];

        $words = explode(' ', $message);
        $keywords = [];
        foreach ($words as $w) {
            $w = trim($w);
            if (strlen($w) <= 1) continue;
            if (in_array($w, $stopWords)) continue;
            $keywords[] = $w;
            $translated = $colorMap[$w] ?? null;
            if ($translated) $keywords[] = $translated;
        }

        $keywords = array_unique($keywords);
        if (empty($keywords)) return null;

        $query = Produk::with('kategori')->where('status_produk', 'aktif');

        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $kw) {
                $q->orWhere('nama_produk', 'like', "%$kw%")
                  ->orWhere('deskripsi_produk', 'like', "%$kw%");
            }
        });

        $products = $query->limit(5)->get();

        if ($products->isEmpty()) return null;

        $reply = "Nemu nih Kak! Ini produk yang cocok:\n\n";
        foreach ($products as $i => $p) {
            $reply .= ($i + 1) . '. ' . strtoupper($p->nama_produk) . "\n";
            if ($p->kategori) {
                $reply .= "   Kategori: {$p->kategori->nama_kategori}";
            }
            $reply .= " | Rp " . number_format($p->harga, 0, ',', '.') . "\n";
            $reply .= "   Stok: {$p->stok} pcs";
            if ($p->size_produk) {
                $reply .= " | Ukuran: {$p->size_produk}";
            }
            $reply .= "\n\n";
        }

        if ($products->count() == 1) {
            $p = $products->first();
            if ($p->stok > 0) {
                $reply .= "Stok {$p->nama_produk} masih tersedia {$p->stok} pcs Kak! Yuk langsung order.";
            } else {
                $reply .= "Maaf Kak, stok {$p->nama_produk} sedang kosong.";
            }
        } else {
            $reply .= "Ada yang menarik Kak? Bisa ketik nama produk untuk detail lebih lanjut.";
        }

        return $reply;
    }
}
