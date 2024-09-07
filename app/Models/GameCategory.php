<?php

namespace App\Models;


class GameCategory extends Model
{
    protected $fillable = [
        'name',
        'sort',
        'status'
    ];

    public function games()
    {
        return $this->hasMany(Game::class, 'category_id');
    }
}
