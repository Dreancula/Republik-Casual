<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::with(['kategori', 'brand'])->orderBy('created_at', 'desc')->get();
        return view('backend.produk.index', compact('produk'));
    }

    /**
     * TAMPILKAN FORM TAMBAH PRODUK BARU
     */
    public function create()
    {
        $kategori = Kategori::all();
        $brand = Brand::all();

        return view('backend.produk.create', compact('kategori', 'brand'));
    }

    /**
     * PROSES SIMPAN PRODUK BARU KE DATABASE
     */
    public function store(Request $request)
    {
        // 1. Validasi Inputan Data Utama Produk
        $request->validate([
            'id_kategori' => 'required|integer',
            'id_brand' => 'required|integer',
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'berat' => 'required|integer|min:1',
            'size_produk' => 'required|string',
            'deskripsi_produk' => 'nullable|string',
            'stok' => 'required|integer',
        ]);

        // 2. Eksekusi simpan ke database dengan status awal 'Nonaktif' (Sesuai ENUM database)
        // Nilai diubah dari 'non-aktif' menjadi 'Nonaktif' agar sinkron dengan MySQL

        $foto = $request->file('foto_produk')
            ->store('produk', 'public');
        Produk::create([
            'id_kategori' => $request->id_kategori,
            'id_brand' => $request->id_brand,
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'berat' => $request->berat,
            'size_produk' => $request->size_produk,
            'deskripsi_produk' => $request->deskripsi_produk ?? '-',
            'foto_produk' => $foto,
            'stok' => $request->stok,
            'status_produk' => 'nonaktif',
        ]);

        // 3. Redirect kembali ke halaman index katalog
        return redirect()->route('admin.produk.index')->with('success', 'Artikel produk baru berhasil didaftarkan! Silakan isi stok melalui menu Pemasukan Barang.');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::all();
        $brand = Brand::all();

        return view('backend.produk.edit', compact('produk', 'kategori', 'brand'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'id_kategori' => 'required|integer',
            'id_brand' => 'required|integer',
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'berat' => 'required|integer|min:1',
            'size_produk' => 'required|string',
            'deskripsi_produk' => 'required|string',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $namaFoto = $produk->foto_produk;
        if ($request->hasFile('foto_produk')) {
            if ($produk->foto_produk && Storage::disk('public')->exists($produk->foto_produk)) {
                Storage::disk('public')->delete($produk->foto_produk);
            }
            $namaFoto = $request->file('foto_produk')->store('produk', 'public');
        }

        // Nilai diubah dari 'aktif' menjadi 'Aktif' agar sinkron dengan ENUM MySQL
        $produk->update([
            'id_kategori' => $request->id_kategori,
            'id_brand' => $request->id_brand,
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'berat' => $request->berat,
            'size_produk' => $request->size_produk,
            'deskripsi_produk' => $request->deskripsi_produk,
            'foto_produk' => $namaFoto,
            'status_produk' => strtolower($request->status_produk),
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dilengkapi dan telah tayang di katalog customer!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Hapus foto produk dari storage jika ada sebelum datanya dihapus
        if ($produk->foto_produk && Storage::disk('public')->exists($produk->foto_produk)) {
            Storage::disk('public')->delete($produk->foto_produk);
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Artikel produk berhasil dihapus dari sistem!');
    }
}