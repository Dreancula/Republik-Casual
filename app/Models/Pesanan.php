<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $primaryKey = 'id_pesanan';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pesanan',
        'id_user',
        'tgl_pesanan',
        'status_pesanan',
        'total_harga_produk',
        'total_ongkir',
        'total_bayar',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }

    public function detailPesanan()
    {
        return $this->hasMany(
            DetailPesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    public function pembayaran()
    {
        return $this->hasOne(
            Pembayaran::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    public function pengiriman()
    {
        return $this->hasMany(
            PengirimanProduk::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    public function pengirimanUtama()
    {
        return $this->hasOne(
            PengirimanProduk::class,
            'id_pesanan',
            'id_pesanan')
            ->where('jenis_pengiriman', 'utama');
    }

    public function komplain()
    {
        return $this->hasMany(
            Komplain::class,
            'id_pesanan',
            'id_pesanan'
        );
    }
}
