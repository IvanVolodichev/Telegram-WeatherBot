<?php

namespace App\Models;

use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    protected $fillable = [
        'location',
        'chat_id',
    ];

    public function chat()
    {
        return $this->belongsTo(
            TelegraphChat::class, 
            'chat_id', 
            'chat_id'
        );
    }
}
