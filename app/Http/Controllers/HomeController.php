<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Brand;

class HomeController extends Controller
{
    public function index()
    {
        $kategori = Kategori::latest()
            ->take(8)
            ->get();

        $brand = Brand::latest()
            ->take(8)
            ->get();



        $produkTerbaru = Produk::with([
            'kategori',
            'brand'
        ])
            ->where('status_produk', 'aktif')
            ->latest()
            ->take(8)
            ->get();

        return view(
            'frontend.beranda.beranda',
            compact(
                'kategori',
                'brand',
                'produkTerbaru'
            )
        );
    }
}