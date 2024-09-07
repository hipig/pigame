<?php

namespace App\Models;


class RoomRoundPlayer extends Model
{

    const STATUS_WIN = 'WIN'; // 胜利

    const STATUS_OUT = 'OUT'; // 淘汰

    const STATUS_RUNNING = 'RUNNING'; // 进行中

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
