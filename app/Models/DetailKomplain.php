<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKomplain extends Model
{
    protected $table = 'detail_komplain';

    protected $primaryKey = 'id_detail_komplain';

    protected $fillable = [
        'id_komplain',
        'id_produk',
        'qty',
        'foto',
    ];

    public function komplain()
    {
        return $this->belongsTo(
            Komplain::class,
            'id_komplain',
            'id_komplain'
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

    public function getFotoArrayAttribute()
    {
        if (!$this->foto) return [];
        $decoded = json_decode($this->foto, true);
        return is_array($decoded) ? $decoded : [$this->foto];
    }
}
