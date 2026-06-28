<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKeranjang extends Model
{
    protected $table = 'detail_keranjang';

    protected $primaryKey = 'id_detail_keranjang';

    protected $fillable = [
        'id_keranjang',
        'id_produk',
        'quantity',
        'sub_total'
    ];

    public function keranjang()
    {
        return $this->belongsTo(
            Keranjang::class,
            'id_keranjang',
            'id_keranjang'
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