<?php

namespace App\Models;


class RoomRound extends Model
{
    const STATUS_PLAYING = 'PLAYING';
    const STATUS_ENDED = 'ENDED';

    protected $casts = [
        'data' => 'json'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_key');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function init()
    {
        $game = $this->game;

        // 第一步填充数据
        $step = $game->setup($this);
    }
}
