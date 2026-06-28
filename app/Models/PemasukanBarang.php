<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemasukanBarang extends Model
{
    protected $table = 'pemasukan_barang';

    protected $primaryKey = 'id_pemasukan';

    protected $fillable = [
        'id_user',
        'tgl_pemasukan',
        'no_faktur'
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }

    public function detailPemasukanBarang()
    {
        return $this->hasMany(
            DetailPemasukanBarang::class,
            'id_pemasukan',
            'id_pemasukan'
        );
    }
}