<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'id_role',
        'nama',
        'email',
        'password',
        'no_telp',
        'alamat',
        'google_id',
        'avatar',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function role()
    {
        return $this->belongsTo(
            Role::class,
            'id_role',
            'id_role'
        );
    }

    public function keranjang()
    {
        return $this->hasOne(
            Keranjang::class,
            'id_user',
            'id_user'
        );
    }

    public function pesanan()
    {
        return $this->hasMany(
            Pesanan::class,
            'id_user',
            'id_user'
        );
    }

    public function pemasukanBarang()
    {
        return $this->hasMany(
            PemasukanBarang::class,
            'id_user',
            'id_user'
        );
    }

    public function komplain()
    {
        return $this->hasMany(
            Komplain::class,
            'id_user',
            'id_user'
        );
    }
}