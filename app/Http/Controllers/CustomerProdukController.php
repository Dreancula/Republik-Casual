<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Brand;

class CustomerProdukController extends Controller
{

    public function index()
    {
        $query = Produk::with([
            'kategori',
            'brand'
        ])
            ->where('status_produk', 'aktif');

        if (request('kategori')) {
            $query->where('id_kategori', request('kategori'));
        }

        if (request('brand')) {
            $query->where('id_brand', request('brand'));
        }

        if (request('search')) {
            $query->where('nama_produk', 'like', '%' . request('search') . '%');
        }

        $produk = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $kategori = Kategori::orderBy('nama_kategori')->get();
        $brand = Brand::orderBy('nama_brand')->get();

        return view(
            'frontend.produk.index',
            compact(
                'produk',
                'kategori',
                'brand'
            )
        );
    }

    public function show($id)
    {
        $produk = Produk::with([
            'kategori',
            'brand'
        ])->findOrFail($id);

        return view(
            'frontend.produk.show',
            compact('produk')
        );
    }

    public function searchApi()
    {
        $q = request('q');
        if (!$q || strlen(trim($q)) < 1) {
            return response()->json([]);
        }

        $produk = Produk::with('kategori')
            ->where('status_produk', 'aktif')
            ->where(function ($query) use ($q) {
                $query->where('nama_produk', 'like', "%{$q}%")
                      ->orWhere('deskripsi_produk', 'like', "%{$q}%");
            })
            ->limit(8)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id_produk,
                    'nama' => $p->nama_produk,
                    'harga' => 'Rp ' . number_format($p->harga, 0, ',', '.'),
                    'stok' => $p->stok,
                    'kategori' => $p->kategori?->nama_kategori,
                    'url' => route('produk.show', $p->id_produk),
                ];
            });

        return response()->json($produk);
    }
}