<?php

namespace App\Http\Controllers;

use App\Models\Kategori; // Pastikan namespace model Kategori sesuai
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::orderBy('created_at', 'desc')->get();
        return view('backend.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori'
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        // Proteksi jika kategori masih dipakai di produk
        if ($kategori->produk()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal menghapus! Kategori ini masih terikat dengan produk aktif.');
        }

        $kategori->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}