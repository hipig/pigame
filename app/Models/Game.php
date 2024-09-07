<?php

namespace App\Models;


class Game extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'key',
        'icon',
        'min_people',
        'max_people',
        'sort',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(GameCategory::class, 'category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'key';
    }
}
