<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brand';

    protected $primaryKey = 'id_brand';

    protected $fillable = [
        'nama_brand'
    ];

    public function produk()
    {
        return $this->hasMany(
            Produk::class,
            'id_brand',
            'id_brand'
        );
    }
}