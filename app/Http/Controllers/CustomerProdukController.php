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
}