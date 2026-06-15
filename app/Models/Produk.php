<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    public $timestamps = true;
    protected $table = "produk";
    protected $primaryKey = 'id_produk';
    protected $guarded = ['id_produk'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function fotoProduk()
    {
        return $this->hasMany(FotoProduk::class, 'id_produk', 'id_produk');
    }
}
