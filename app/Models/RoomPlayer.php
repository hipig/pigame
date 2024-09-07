<?php

namespace App\Models;


class RoomPlayer extends Model
{
    protected $fillable = [
        'sort'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
