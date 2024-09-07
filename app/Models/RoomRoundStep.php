<?php

namespace App\Models;


class RoomRoundStep extends Model
{
    const STATUS_RUNNING = 'RUNNING'; // 进行中

    const STATUS_ENDED = 'ENDED'; // 已结束

    protected $casts = [
        'data' => 'json'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function round()
    {
        return $this->belongsTo(RoomRound::class, 'round_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
