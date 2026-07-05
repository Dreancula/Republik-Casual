<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chats';

    protected $primaryKey = 'id_chat';

    protected $fillable = [
        'id_user',
        'teks',
        'kategori_chat',
        'is_admin',
        'foto_chat',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }
}
