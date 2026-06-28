<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPemasukanBarang extends Model
{
    protected $table = 'detail_pemasukan_barang';

    protected $primaryKey = 'id_detail_pemasukan';

    protected $fillable = [
        'id_pemasukan',
        'id_produk',
        'jumlah_masuk',
        'harga_beli'
    ];

    public function pemasukanBarang()
    {
        return $this->belongsTo(
            PemasukanBarang::class,
            'id_pemasukan',
            'id_pemasukan'
        );
    }

    public function produk()
    {
        return $this->belongsTo(
            Produk::class,
            'id_produk',
            'id_produk'
        );
    }
}