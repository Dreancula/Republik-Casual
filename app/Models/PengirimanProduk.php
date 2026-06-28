<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengirimanProduk extends Model
{
    protected $table = 'pengiriman_produk';

    protected $primaryKey = 'id_pengiriman';

    protected $fillable = [
        'id_pesanan',
        'nama_kurir',
        'layanan',
        'no_resi',
        'jenis_pengiriman',
        'tgl_pengiriman',
        'status_pengiriman'
    ];

    public function pesanan()
    {
        return $this->belongsTo(
            Pesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }
}
