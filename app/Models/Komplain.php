<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komplain extends Model
{
    protected $table = 'komplain';

    protected $primaryKey = 'id_komplain';

    protected $fillable = [
        'id_pesanan',
        'id_user',
        'id_produk',
        'qty',
        'jenis_komplain',
        'foto',
        'no_resi_return',
        'foto_return',
        'status_komplain',
        'id_pesanan_retur',
        'deskripsi',
    ];

    protected $attributes = [
        'status_komplain' => 'pending',
        'qty' => 1,
    ];

    public function pesanan()
    {
        return $this->belongsTo(
            Pesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
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

    public function detailKomplain()
    {
        return $this->hasMany(
            DetailKomplain::class,
            'id_komplain',
            'id_komplain'
        );
    }

    public function returPesanan()
    {
        return $this->belongsTo(
            Pesanan::class,
            'id_pesanan_retur',
            'id_pesanan'
        );
    }

    public function getFotoArrayAttribute()
    {
        if (!$this->foto) return [];
        $decoded = json_decode($this->foto, true);
        return is_array($decoded) ? $decoded : [$this->foto];
    }
}
