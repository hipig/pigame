<?php

namespace App\Models;


use Illuminate\Support\Str;

class Room extends Model
{
    const STATUS_WAITING = 'WAITING';
    const STATUS_PLAYING = 'PLAYING';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->code) {
                $model->code = static::findAvailableCode();
                if (!$model->code) {
                    return false;
                }
            }
        });
    }


    public function game()
    {
        return $this->belongsTo(Game::class, 'game_key', 'key');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function players()
    {
        return $this->hasMany(RoomPlayer::class, 'room_id');
    }

    public function sessions()
    {
        return $this->hasMany(RoomRound::class, 'room_id');
    }

    public static function findAvailableCode()
    {
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 5 位字符串
            $code = Str::random(5);
            // 判断是否已经存在
            if (!static::query()->where('code', $code)->exists()) {
                return $code;
            }
        }
        \Log::warning('generated room code failed');

        return false;
    }

    public function getRouteKeyName(): string
    {
        return 'code';
    }
}
