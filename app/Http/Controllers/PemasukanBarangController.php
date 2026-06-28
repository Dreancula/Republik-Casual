<?php

namespace App\Http\Controllers;

use App\Models\PemasukanBarang;
use App\Models\DetailPemasukanBarang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemasukanBarangController extends Controller
{
    public function index()
    {
        $pemasukan = PemasukanBarang::with(['user', 'detailPemasukanBarang'])->orderBy('tgl_pemasukan', 'desc')->get();
        return view('backend.pemasukan.index', compact('pemasukan'));
    }

    public function show($id)
    {
        $pemasukan = PemasukanBarang::with(['user', 'detailPemasukanBarang.produk'])->findOrFail($id);
        return view('backend.pemasukan.show', compact('pemasukan'));
    }

    // ◄--- TAMBAHKAN METHOD CREATE UNTUK MENAMPILKAN FORM ---
    public function create()
    {
        // Mengambil semua produk untuk pilihan drop-down di form detail
        $produk = Produk::orderBy('nama_produk', 'asc')->get();

        // Membuat nomor faktur otomatis, contoh: INV-20260616-0001
        $tanggal = now()->format('Ymd');
        $hitung = PemasukanBarang::whereDate('created_at', now()->toDateString())->count() + 1;
        $noFakturOtomatis = 'INV-' . $tanggal . '-' . str_pad($hitung, 4, '0', STR_PAD_LEFT);

        return view('backend.pemasukan.create', compact('produk', 'noFakturOtomatis'));
    }

    // ◄--- TAMBAHKAN METHOD STORE UNTUK MENYIMPAN DATA TRANSACTION HEADER-DETAIL ---
    public function store(Request $request)
    {
        $request->validate([
            'no_faktur' => 'required|unique:pemasukan_barang,no_faktur',
            'tgl_pemasukan' => 'required|date',
            'id_produk' => 'required|array',
            'id_produk.*' => 'required|exists:produk,id_produk',
            'jumlah_masuk' => 'required|array',
            'jumlah_masuk.*' => 'required|integer|min:1',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'required|numeric|min:0',
        ]);

        // Menggunakan Database Transaction agar jika satu detail error, seluruh data dibatalkan (aman)
        DB::beginTransaction();

        try {
            // 1. Simpan ke tabel Induk (Header)
            $pemasukan = PemasukanBarang::create([
                'id_user' => Auth::id(), // Petugas yang sedang login
                'no_faktur' => $request->no_faktur,
                'tgl_pemasukan' => $request->tgl_pemasukan,
            ]);

            // 2. Simpan ke tabel Anak (Detail) & Update Stok Produk Sekaligus
            foreach ($request->id_produk as $index => $idProduk) {
                DetailPemasukanBarang::create([
                    'id_pemasukan' => $pemasukan->id_pemasukan,
                    'id_produk' => $idProduk,
                    'jumlah_masuk' => $request->jumlah_masuk[$index],
                    'harga_beli' => $request->harga_beli[$index],
                ]);

                // Opsional: Otomatis tambahkan stok ke tabel produk
                $produk = Produk::find($idProduk);
                if ($produk) {
                    $produk->increment('stok', $request->jumlah_masuk[$index]);
                }
            }

            DB::commit();
            return redirect()->route('admin.pemasukan-barang.index')->with('success', 'Faktur pemasukan barang berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }
}