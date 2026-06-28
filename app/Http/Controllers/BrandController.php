<?php

namespace App\Http\Controllers;

use App\Models\Brand; // Pastikan namespace model Brand sesuai
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brand = Brand::orderBy('created_at', 'desc')->get();
        return view('backend.brand.index', compact('brand'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_brand' => 'required|string|max:255|unique:brand,nama_brand'
        ]);

        Brand::create([
            'nama_brand' => $request->nama_brand
        ]);

        return redirect()->back()->with('success', 'Brand baru berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        // Proteksi jika brand masih dipakai di produk
        if ($brand->produk()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal menghapus! Brand ini masih terikat dengan produk aktif.');
        }

        $brand->delete();
        return redirect()->back()->with('success', 'Brand berhasil dihapus!');
    }
}