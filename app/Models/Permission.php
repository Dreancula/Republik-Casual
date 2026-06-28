<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permission';

    protected $primaryKey = 'id_permission';

    protected $fillable = [
        'nama_permission',
        'deskripsi'
    ];

    public function rolePermissions()
    {
        return $this->hasMany(
            RolePermission::class,
            'id_permission',
            'id_permission'
        );
    }
}