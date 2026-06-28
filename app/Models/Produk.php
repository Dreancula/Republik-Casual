<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    // Beritahu Laravel primary key kustom tabel ini
    protected $primaryKey = 'id_produk';

    // Izinkan semua kolom diisi secara massal saat memanggil Produk::create()
    protected $fillable = [
        'id_kategori',
        'id_brand',
        'nama_produk',
        'foto_produk',
        'berat',
        'deskripsi_produk',
        'status_produk',
        'stok',
        'size_produk',
        'harga'
    ];

    /**
     * Relasi ke tabel Kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Relasi ke tabel Brand
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'id_brand', 'id_brand');
    }
}